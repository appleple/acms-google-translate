<?php

namespace Acms\Plugins\GoogleTranslate;

use Acms\Services\Facades\Application;
use Acms\Services\Facades\Common;
use Acms\Services\Facades\Config;
use Acms\Services\Facades\Entry;
use Acms\Services\Facades\Database as DB;
use SQL;
use ACMS_RAM;

class DuplicateEntry
{
    use \Acms\Traits\Common\AssetsTrait;

    protected $config;

    public function __construct()
    {
        $this->config = Config::loadBlogConfig(BID);
    }

    public function dupe($eid, $newEid, $targetBid)
    {
        $DB = DB::singleton(dsn());
        $bid = ACMS_RAM::entryBlog($eid);
        if (empty($targetBid)) {
            $targetBid = $bid;
        }
        $approval = ACMS_RAM::entryApproval($eid);
        $sourceRvid = null;
        if ($approval === 'pre_approval') {
            $sourceRvid = 1;
        }

        //-------
        // unit
        $unitRepository = Application::make('unit-repository');
        $collection = $unitRepository->loadUnits(
            eid: $eid,
            rvid: $sourceRvid,
            options: ['setPrimaryImage' => true]
        );
        $newCollection = clone $collection;
        $newCollection->walk(function (\Acms\Services\Unit\Contracts\Model $unit) use ($newEid, $targetBid) {
            if (is_null($unit->getId())) {
                throw new \RuntimeException(
                    'Unit ID must not be null. Unexpected state: unit data already exists in database.'
                );
            }
            $unit->setEntryId($newEid);
            $unit->setBlogId($targetBid);
            $unit->handleDuplicate();
            $unit->insertDataTrait($unit, false);
        });

        //-------
        // entry
        $entryRepository = Application::make('entry.repository');
        assert($entryRepository instanceof \Acms\Services\Entry\EntryRepository);
        $SQL    = SQL::newSelect('entry');
        $SQL->addWhereOpr('entry_id', $eid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $row = $DB->query($SQL->get(dsn()), 'row');
        $title  = $row['entry_title'] . config('entry_title_duplicate_suffix');
        $code   = ('on' == config('entry_code_title')) ?
            stripWhitespace($title) :
            config('entry_code_prefix') . $newEid;
        if (!!config('entry_code_extension') and !strpos($code, '.')) {
            $code .= ('.' . config('entry_code_extension'));
        }
        $uid = intval($row['entry_user_id']);
        if (!($cid = intval($row['entry_category_id']))) {
            $cid = null;
        };

        // sort
        $esort = $entryRepository->nextSort($targetBid);
        $usort = $entryRepository->nextUserSort($uid, $targetBid);
        $csort = $entryRepository->nextCategorySort($cid, $targetBid);

        $row['entry_id'] = $newEid;
        $row['entry_status'] = 'close';
        $row['entry_approval'] = 'none';
        $row['entry_title'] = $title;
        $row['entry_code'] = $code;
        if (config('update_datetime_as_duplicate_entry') !== 'off') {
            $row['entry_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        }
        $row['entry_posted_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_updated_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_hash'] = md5(SYSTEM_GENERATED_DATETIME . date('Y-m-d H:i:s', REQUEST_TIME));
        $primaryImageUnit = $collection->getPrimaryImageUnit();
        $row['entry_primary_image'] = $primaryImageUnit ? $primaryImageUnit->getId() : null;
        $row['entry_sort'] = $esort;
        $row['entry_user_sort'] = $usort;
        $row['entry_category_sort'] = $csort;
        $row['entry_user_id'] = SUID;
        $row['entry_blog_id'] = $targetBid;
        $SQL = SQL::newInsert('entry');
        foreach ($row as $fd => $val) {
            if ($fd === 'entry_current_rev_id' || $fd === 'entry_reserve_rev_id') {
                continue;
            }
            $SQL->addInsert($fd, $val);
        }
        $DB->query($SQL->get(dsn()), 'exec');

        //-----
        // tag
        $SQL = SQL::newSelect('tag');
        $SQL->addWhereOpr('tag_entry_id', $eid);
        $SQL->addWhereOpr('tag_blog_id', $bid);
        $q = $SQL->get(dsn());
        $statement = $DB->query($q, 'exec');

        if ($statement && ($row = $DB->next($statement))) {
            $insert = SQL::newBulkInsert('tag');
            do {
                $row['tag_entry_id'] = $newEid;
                $row['tag_blog_id'] = $targetBid;
                $insert->addInsert($row);
            } while ($row = $DB->next($statement));
            if ($insert->hasData()) {
                $DB->query($insert->get(dsn()), 'exec');
            }
        }

        //--------------
        // sub category
        $subCategory = loadSubCategories($eid);
        Entry::saveSubCategory($newEid, $cid, implode(',', $subCategory['id']), $targetBid);

        //-------
        // field
        $Field  = loadEntryField($eid);
        $this->duplicateFieldsTrait($Field);
        foreach ($Field->listFields() as $fd) {
            $this->conversionId($Field, $fd, $targetBid);
        }
        Common::saveField('eid', $newEid, $Field, null, null, '', $targetBid);
        Common::saveFulltext('eid', $newEid, Common::loadEntryFulltext($newEid), $targetBid);

        //---------------
        // related entry
        $this->relationDupe($eid, $newEid);

        //----------
        // geo data
        $this->geoDuplicate($eid, $newEid, $targetBid);
    }

    public function approvalDupe($eid, $newEid, $targetBid)
    {
        $DB = DB::singleton(dsn());
        $bid = ACMS_RAM::entryBlog($eid);
        $approval = ACMS_RAM::entryApproval($eid);
        $sourceRev = false;

        if ($approval === 'pre_approval') {
            $sourceRev = true;
        }

        //------
        // unit
        $unitRepository = Application::make('unit-repository');
        assert($unitRepository instanceof \Acms\Services\Unit\Repository);
        $rvid = $sourceRev ? 1 : null;
        $collection = $unitRepository->loadUnits(
            eid: $eid,
            rvid: $rvid,
            options: ['setPrimaryImage' => true]
        );
        $newCollection = clone $collection;
        $newCollection->walk(function (\Acms\Services\Unit\Contracts\Model $unit) use ($newEid, $targetBid) {
            if (is_null($unit->getId())) {
                throw new \RuntimeException(
                    'Unit ID must not be null. Unexpected state: unit data already exists in database.'
                );
            }
            $unit->setEntryId($newEid);
            $unit->setRevId(1);
            $unit->setBlogId($targetBid);
            $unit->handleDuplicate();
            $unit->insertDataTrait($unit, true);
        });

        //-------
        // entry
        $entryRepository = Application::make('entry.repository');
        assert($entryRepository instanceof \Acms\Services\Entry\EntryRepository);
        if ($sourceRev) {
            $SQL = SQL::newSelect('entry_rev');
            $SQL->addWhereOpr('entry_rev_id', 1);
        } else {
            $SQL = SQL::newSelect('entry');
        }
        $SQL->addWhereOpr('entry_id', $eid);
        $SQL->addWhereOpr('entry_blog_id', $bid);
        $row = $DB->query($SQL->get(dsn()), 'row');
        $title = $row['entry_title'] . config('entry_title_duplicate_suffix');
        $code = ('on' == config('entry_code_title')) ? stripWhitespace($title) : config('entry_code_prefix') . $newEid;
        if (!!config('entry_code_extension') and !strpos($code, '.')) {
            $code .= ('.' . config('entry_code_extension'));
        }
        $uid = intval($row['entry_user_id']);
        if (!($cid = intval($row['entry_category_id']))) {
            $cid = null;
        };

        //------
        // sort
        $esort = $entryRepository->nextSort($bid);
        $usort = $entryRepository->nextUserSort($uid, $bid);
        $csort = $entryRepository->nextCategorySort($cid, $bid);

        $row['entry_id'] = $newEid;
        $row['entry_status'] = 'close';
        $row['entry_title'] = $title;
        $row['entry_code'] = $code;
        if (config('update_datetime_as_duplicate_entry') !== 'off') {
            $row['entry_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        }
        $row['entry_posted_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_updated_datetime'] = date('Y-m-d H:i:s', REQUEST_TIME);
        $row['entry_hash'] = md5(SYSTEM_GENERATED_DATETIME . date('Y-m-d H:i:s', REQUEST_TIME));
        $primaryImageUnit = $collection->getPrimaryImageUnit();
        $row['entry_primary_image'] = $primaryImageUnit ? $primaryImageUnit->getId() : null;
        $row['entry_sort'] = $esort;
        $row['entry_user_sort'] = $usort;
        $row['entry_category_sort'] = $csort;
        $row['entry_user_id'] = SUID;
        $row['entry_blog_id'] = $targetBid;
        $SQL = SQL::newInsert('entry');
        foreach ($row as $fd => $val) {
            if (
                !in_array($fd, [
                    'entry_approval',
                    'entry_approval_public_point',
                    'entry_approval_reject_point',
                    'entry_last_update_user_id',
                    'entry_rev_id',
                    'entry_rev_status',
                    'entry_rev_memo',
                    'entry_rev_user_id',
                    'entry_rev_datetime',
                    'entry_current_rev_id',
                    'entry_reserve_rev_id',
                    'entry_lock_datetime',
                    'entry_lock_uid',
                ], true)
            ) {
                $SQL->addInsert($fd, $val);
            }
        }
        $SQL->addInsert('entry_approval', 'pre_approval');
        $SQL->addInsert('entry_last_update_user_id', SUID);
        $DB->query($SQL->get(dsn()), 'exec');

        $SQL = SQL::newInsert('entry_rev');
        foreach ($row as $fd => $val) {
            if (
                !in_array($fd, [
                    'entry_current_rev_id',
                    'entry_reserve_rev_id',
                    'entry_last_update_user_id',
                    'entry_rev_id',
                    'entry_rev_user_id',
                    'entry_rev_datetime'
                ], true)
            ) {
                $SQL->addInsert($fd, $val);
            }
        }
        $SQL->addInsert('entry_rev_id', 1);
        $SQL->addInsert('entry_rev_user_id', SUID);
        $SQL->addInsert('entry_rev_datetime', date('Y-m-d H:i:s', REQUEST_TIME));
        $DB->query($SQL->get(dsn()), 'exec');

        //-----
        // tag
        $SQL = SQL::newSelect($sourceRev ? 'tag_rev' : 'tag');
        $SQL->addWhereOpr('tag_entry_id', $eid);
        $SQL->addWhereOpr('tag_blog_id', $bid);
        if ($sourceRev) {
            $SQL->addWhereOpr('tag_rev_id', 1);
        }
        $q = $SQL->get(dsn());
        $statement = $DB->query($q, 'exec');
        if ($statement && ($row = $DB->next($statement))) {
            $insert = SQL::newBulkInsert('tag_rev');
            do {
                $row['tag_entry_id'] = $newEid;
                if (!$sourceRev) {
                    $row['tag_rev_id'] = 1;
                    $row['tag_blog_id'] = $targetBid;
                }
                $insert->addInsert($row);
            } while ($row = $DB->next($statement));
            if ($insert->hasData()) {
                $DB->query($insert->get(dsn()), 'exec');
            }
        }

        //--------------
        // sub category
        if ($sourceRev) {
            $subCategory = loadSubCategories($eid, 1);
        } else {
            $subCategory = loadSubCategories($eid);
        }
        Entry::saveSubCategory($newEid, $cid, implode(',', $subCategory['id']), $targetBid, 1);

        //-------
        // field
        if ($sourceRev) {
            $Field = loadEntryField($eid, 1);
        } else {
            $Field = loadEntryField($eid);
        }
        $this->duplicateFieldsTrait($Field);
        foreach ($Field->listFields() as $fd) {
            $this->conversionId($Field, $fd, $targetBid);
        }
        Common::saveField('eid', $newEid, $Field, null, null, '', $targetBid);
        Entry::saveFieldRevision($newEid, $Field, 1);
        Common::saveFulltext('eid', $newEid, Common::loadEntryFulltext($newEid), $targetBid);
    }

    /**
     * 関連エントリーの複製
     * @param int $eid 複製元のエントリーID
     * @param int $newEid 複製先のエントリーID
     * @return void
     */
    protected function relationDupe($eid, $newEid)
    {
        $SQL = SQL::newSelect('relationship');
        $SQL->addWhereOpr('relation_id', $eid);
        $all = DB::query($SQL->get(dsn()), 'all');

        $sql = SQL::newBulkInsert('relationship');
        foreach ($all as $row) {
            $sql->addInsert([
                'relation_id' => $newEid,
                'relation_eid' => $row['relation_eid'],
                'relation_type' => $row['relation_type'],
                'relation_order' => $row['relation_order'],
            ]);
        }
        if ($sql->hasData()) {
            DB::query($sql->get(dsn()), 'exec');
        }
    }

    /**
     * 位置情報の複製
     * @param int $eid 複製元のエントリーID
     * @param int $newEid 複製先のエントリーID
     * @param int $targetBid 複製先のブログID
     * @return void
     */
    protected function geoDuplicate($eid, $newEid, $targetBid = BID)
    {
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('geo');
        $SQL->addWhereOpr('geo_eid', $eid);
        if ($row = $DB->query($SQL->get(dsn()), 'row')) {
            $SQL = SQL::newInsert('geo');
            $SQL->addInsert('geo_eid', $newEid);
            $SQL->addInsert('geo_geometry', $row['geo_geometry']);
            $SQL->addInsert('geo_zoom', $row['geo_zoom']);
            $SQL->addInsert('geo_blog_id', $targetBid);
            $DB->query($SQL->get(dsn()), 'exec');
        }
    }

    /**
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionId(&$Field, $fd, $targetBid)
    {
        $this->conversionEntryId($Field, $fd, $targetBid);
        $this->conversionBlogId($Field, $fd, $targetBid);
        $this->conversionCategoryId($Field, $fd, $targetBid);
    }

    /**
     * フィールドのeidデータを変換
     *
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionEntryId(&$Field, $fd, $targetBid)
    {
        $translationEidFields = $this->config->getArray('translationEidFieldName');
        if (in_array($fd, $translationEidFields, true)) {
            $eidValue = $Field->getArray($fd);

            $Field->delete($fd);
            foreach ($eidValue as $eid) {
                $SQL = SQL::newSelect('google_translate_entry');
                $SQL->addSelect('relation_eid');
                $SQL->addWhereOpr('base_entry_id', $eid);
                $SQL->addWhereOpr('relation_bid', $targetBid);
                if ($translationEid = DB::query($SQL->get(dsn()), 'one')) {
                    $Field->add($fd, $translationEid);
                } else {
                    $Field->add($fd, $eid);
                }
            }
        }
    }

    /**
     * フィールドのbidデータを変換
     *
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionBlogId(&$Field, $fd, $targetBid)
    {
        $translationBidFields = $this->config->getArray('translationBidFieldName');
        if (in_array($fd, $translationBidFields, true)) {
            $bidValue = $Field->getArray($fd);
            $Field->delete($fd);

            $SQL = SQL::newSelect('google_translate_blog');
            $SQL->addSelect('lang_code');
            $SQL->addWhereOpr('base_blog_id', BID);
            $SQL->addWhereOpr('relation_bid', $targetBid);
            $langCode = DB::query($SQL->get(dsn()), 'one');

            foreach ($bidValue as $bid) {
                $bid = intval($bid);
                $SQL = SQL::newSelect('google_translate_blog');
                $SQL->addSelect('relation_bid');
                $SQL->addWhereOpr('base_blog_id', $bid);
                $SQL->addWhereOpr('lang_code', $langCode);
                if ($translationBid = DB::query($SQL->get(dsn()), 'one')) {
                    $Field->add($fd, $translationBid);
                } else {
                    $Field->add($fd, $bid);
                }
            }
        }
    }

    /**
     * フィールドのcidデータを変換
     *
     * @param $Field
     * @param $fd
     * @param $targetBid
     */
    protected function conversionCategoryId(&$Field, $fd, $targetBid)
    {
        $engine = Application::make('google_translate.engine');
        $translationCidFields = $this->config->getArray('translationCidFieldName');
        if (in_array($fd, $translationCidFields, true)) {
            $cidValue = $Field->getArray($fd);
            $Field->delete($fd);
            foreach ($cidValue as $cid) {
                $cid = intval($cid);
                if ($targetCid = $engine->checkCategory($cid, $targetBid)) {
                    $Field->add($fd, $targetCid);
                } else {
                    $Field->add($fd, $cid);
                }
            }
        }
    }
}
