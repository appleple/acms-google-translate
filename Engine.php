<?php

namespace Acms\Plugins\GoogleTranslate;

use DB;
use SQL;
use ACMS_RAM;
use ACMS_Filter;
use Field;
use Entry;
use Storage;
use Common;
use Config;
use ACMS_Hook;
use ACMS_POST_Category;

class Engine
{
    /**
     * @var string
     */
    protected $baseLangCode = 'ja';

    /**
     * @param string $lang
     */
    public function setBaseLangCode($lang)
    {
        $this->baseLangCode = $lang;
    }

    /**
     * @return string
     */
    public function getBaseLangCode()
    {
        return $this->baseLangCode;
    }

    /**
     * @param int $bid
     * @return mixed
     */
    public function getBaseBlogId($bid)
    {
        $SQL = SQL::newSelect('google_translate_blog');
        $SQL->addSelect('base_blog_id');
        $SQL->addWhereOpr('relation_bid', $bid);
        if ($baseBid = DB::query($SQL->get(dsn()), 'one')) {
            return intval($baseBid);
        }
        return BID;
    }

    /**
     * @param int $bid
     * @return Field
     */
    public function loadConfig($bid = BID)
    {
        $config = new Field;

        $SQL = SQL::newSelect('google_translate_blog');
        $SQL->addWhereOpr('base_blog_id', $bid);
        $all = DB::query($SQL->get(dsn()), 'all');
        foreach ( $all as $row ) {
            $config->add('google_translate_relation_bid', $row['relation_bid']);
            $config->add('google_translate_lang_label', $row['lang_label']);
            $config->add('google_translate_lang_code', $row['lang_code']);
        }
        $config->add('@google_translate_blog', 'google_translate_relation_bid');
        $config->add('@google_translate_blog', 'google_translate_lang_label');
        $config->add('@google_translate_blog', 'google_translate_lang_code');

        return $config;
    }

    /**
     * @param $eid
     * @return mixed
     */
    public function getEntryData($eid)
    {
        $sql = SQL::newSelect('google_translate_entry', 'multi_lang_entry');
        $sql->addLeftJoin('entry', 'entry_id', 'relation_eid', 'entry', 'multi_lang_entry');
        $sql->addLeftJoin('google_translate_blog', 'relation_bid', 'relation_bid', 'multi_lang_blog', 'multi_lang_entry');
        $sql->addWhereOpr('entry_id', $eid);
        $entry = DB::query($sql->get(dsn()), 'row');
        if (empty($entry)) {
            throw new \RuntimeException('Not found entry.');
        }
        return $entry;
    }

    /**
     * 日本語ブログに設定されている、翻訳対象のフィールドリストを取得
     *
     * @param int $eid
     * @return array mixed
     */
    public function getTranslationFieldFromEid($eid)
    {
        $sql = SQL::newSelect('google_translate_entry', 'multi_lang_entry');
        $sql->setSelect('base_blog_id');
        $sql->addLeftJoin('google_translate_blog', 'relation_bid', 'relation_bid', 'multi_lang_blog', 'multi_lang_entry');
        $sql->addWhereOpr('relation_eid', $eid);
        $jaBid = DB::query($sql->get(dsn()), 'one');
        if (empty($jaBid)) {
            $jaBid = ACMS_RAM::entryBlog($eid);
        }
        $config = Config::loadBlogConfig($jaBid);
        return $config->getArray('translationFieldName');
    }

    /**
     * エントリーのレスポンスデータ組み立て
     *
     * @param int $entry
     * @param array $targetField
     * @param bool $detail
     * @return array
     */
    public function buildEntryData($entry, $targetField, $detail = true)
    {
        $eid = $entry['entry_id'];
        $item = array(
            'id' => intval($eid),
            'blog_id' => intval($entry['entry_blog_id']),
            'originalID' => intval($entry['base_entry_id']),
            'baseLangCode' => $this->baseLangCode,
            'langCode' => $entry['lang_code'] ? $entry['lang_code'] : $this->baseLangCode,
            'title' => $entry['entry_title'],
            'datetime' => $entry['entry_datetime'],
            'posted_datetime' => $entry['entry_posted_datetime'],
            'updated_datetime' => $entry['entry_updated_datetime'],
        );
        if ($detail) {
            $item['fields'] = $this->buildFieldData($eid, $targetField);
            $item['units'] = $this->buildUnitDate($eid);
        }
        return $item;
    }

    /**
     * @param int $jaBid
     * @param int $targetBid
     * @return bool
     */
    public function getTargetLangCode($jaBid, $targetBid)
    {
        $sql = SQL::newSelect('google_translate_blog');
        $sql->setSelect('lang_code');
        $sql->addWhereOpr('base_blog_id', $jaBid);
        $sql->addWhereOpr('relation_bid', $targetBid);
        $code = DB::query($sql->get(dsn()), 'one');
        if (empty($code)) {
            return false;
        }
        return $code;
    }

    /**
     * @param int $bid
     * @return bool | string
     */
    public function getLangCode($bid)
    {
        $sql = SQL::newSelect('google_translate_blog');
        $sql->setSelect('lang_code');
        $sql->addWhereOpr('relation_bid', $bid);
        $code = DB::query($sql->get(dsn()), 'one');
        if (empty($code)) {
            return false;
        }
        return $code;
    }

    public function getTranslateStatus($eid)
    {
        $sql = SQL::newSelect('google_translate_entry', 'multi_lang_entry');
        $sql->setSelect('status');
        $sql->addWhereOpr('relation_eid', $eid);
        return DB::query($sql->get(dsn()), 'one');
    }

    /**
     * 対応するカテゴリーが存在するかチェック
     * @param $cid
     * @param $targetBid
     * @return bool
     */
    public function checkCategory($cid, $targetBid)
    {
        if (empty($cid)) {
            return null;
        }
        $code = ACMS_RAM::categoryCode($cid);

        // 例外を検索
        $sql = SQL::newSelect('google_translate_blog');
        $sql->addSelect('lang_code');
        $sql->addWhereOpr('relation_bid', $targetBid);
        $lang = DB::query($sql->get(dsn()), 'one');
        $categoryField = loadCategoryField($cid);
        if ($exceptionCode = $categoryField->get("google_translate_relation_category_${lang}_code", false)) {
            $code = $exceptionCode;
        }

        // コードからカテゴリ検索
        $sql = SQL::newSelect('category');
        $sql->addSelect('category_id');
        $sql->addWhereOpr('category_blog_id', $targetBid);
        $sql->addWhereOpr('category_code', $code);
        if ($targetCid = DB::query($sql->get(dsn()), 'one')) {
            return $targetCid;
        }

        // グローバルカテゴリをチェック
        $categoryBid = ACMS_RAM::categoryBlog($cid);
        if (1
            && $categoryBid
            && ACMS_RAM::categoryScope($cid) === 'global'
            && ACMS_RAM::blogLeft($categoryBid) <= ACMS_RAM::blogLeft($targetBid)
            && ACMS_RAM::blogRight($categoryBid) >= ACMS_RAM::blogRight($targetBid)
        ) {
            return $cid;
        }

        if (config('google_translate_create_category') === 'create') {
            return $this->copyCategories($cid, $targetBid);
        }
        return false;
    }

    /**
     * @param int $cid
     * @param int $targetBid
     * @return int
     */
    protected function copyCategories($cid, $targetBid)
    {
        $SQL = SQL::newSelect('category');
        ACMS_Filter::categoryTree($SQL, $cid, 'self-ancestor');
        $all = DB::query($SQL->get(dsn()), 'all');

        $parentCid = 0;
        foreach ($all as $category) {
            $parentCid = $this->createCategory($category, $parentCid, $targetBid);
        }
        return $parentCid;
    }

    /**
     * @param array $category
     * @param int $parentCid
     * @param int $targetBid
     * @return mixed
     */
    protected function createCategory($category, $parentCid, $targetBid)
    {
        $DB = DB::singleton(dsn());
        // 存在するか検索
        $SQL = SQL::newSelect('category');
        $SQL->setSelect('category_id');
        $SQL->addWhereOpr('category_blog_id', $targetBid);
        $SQL->addWhereOpr('category_code', $category['category_code']);
        $SQL->addWhereOpr('category_parent', $parentCid);
        if ($cid = $DB->query($SQL->get(dsn()), 'one')) {
            return $cid;
        }
        // 暫定位置を取得
        $SQL = SQL::newSelect('category');
        $SQL->addWhereOpr('category_blog_id', $targetBid);
        $SQL->setOrder('category_right', true);
        $SQL->setLimit(1);
        if ($row = $DB->query($SQL->get(dsn()), 'row')) {
            $sort   = $row['category_sort'] + 1;
            $left   = $row['category_right'] + 1;
            $right  = $row['category_right'] + 2;
        } else {
            $sort   = 1;
            $left   = 1;
            $right  = 2;
        }
        // カテゴリ作成
        $cid = $DB->query(SQL::nextval('category_id', dsn()), 'seq');
        $SQL = SQL::newInsert('category');
        $SQL->addInsert('category_id', $cid);
        $SQL->addInsert('category_parent', 0);
        $SQL->addInsert('category_sort', $sort);
        $SQL->addInsert('category_left', $left);
        $SQL->addInsert('category_right', $right);
        $SQL->addInsert('category_blog_id', $targetBid);
        $SQL->addInsert('category_status', $category['category_status']);
        $SQL->addInsert('category_name', $category['category_name']);
        $SQL->addInsert('category_scope', $category['category_scope']);
        $SQL->addInsert('category_indexing', $category['category_indexing']);
        $SQL->addInsert('category_code', $category['category_code']);
        $SQL->addInsert('category_config_set_id', $category['category_config_set_id']);
        $DB->query($SQL->get(dsn()), 'exec');

        $this->changeParentCategory($cid, $parentCid, $targetBid);
        Common::saveFulltext('cid', $cid, Common::loadCategoryFulltext($cid));

        return $cid;
    }

    /**
     * エントリーフィールドのレスポンスデータ組み立て
     *
     * @param int $eid
     * @param array $targetField
     * @return array
     */
    protected function buildFieldData($eid, $targetField)
    {
        $item = array();
        $fields = loadEntryField($eid);
        foreach ($fields->listFields() as $key) {
            $values = $fields->getArray($key);
            if (!in_array($key, $targetField)) {
                continue;
            }
            if (empty($values)) {
                continue;
            }
            $item[] = array(
                'key' => $key,
                'value' => $values,
            );
        }
        return $item;
    }

    /**
     * エントリーユニットのレスポンスデータ組み立て
     *
     * @param int $eid
     * @return array
     */
    protected function buildUnitDate($eid)
    {
        $item = array();
        $units = loadColumn($eid);
        foreach ($units as $unit) {
            if (detectUnitTypeSpecifier($unit['type']) !== 'text') {
                continue;
            }
            $item[] = $unit;
        }
        return $item;
    }

    protected function changeParentCategory($cid, $toPid, $targetBid)
    {
        if ( !$cid = idval($cid) ) return false;
        if ( $toPid == $cid ) return false;
        if ( $targetBid <> ACMS_RAM::categoryBlog($cid) ) return false;

        $DB = DB::singleton(dsn());

        //-----------------------------
        // from:left, right, pid, sort
        $SQL    = SQL::newSelect('category');
        $SQL->addSelect('category_left');
        $SQL->addSelect('category_right');
        $SQL->addSelect('category_parent');
        $SQL->addSelect('category_sort');
        $SQL->addWhereOpr('category_id', $cid);
        $SQL->addWhereOpr('category_blog_id', $targetBid);
        if ( !$row = $DB->query($SQL->get(dsn()), 'row') ) die();
        $fromLeft   = intval($row['category_left']);
        $fromRight  = intval($row['category_right']);
        $fromPid    = intval($row['category_parent']);
        $fromSort   = intval($row['category_sort']);

        //-------------
        // same parent
        if ( $toPid == $fromPid ) return false;

        //------------------------
        // to: left, right, sort
        if ( !empty($toPid) ) {
            $SQL    = SQL::newSelect('category');
            $SQL->addSelect('category_left');
            $SQL->addSelect('category_right');
            $SQL->addWhereOpr('category_id', $toPid);
            $SQL->addWhereOpr('category_blog_id', $targetBid);
            if ( !$row = $DB->query($SQL->get(dsn()), 'row') ) die();
            $toLeft     = $row['category_left'];
            $toRight    = $row['category_right'];

            if ( $toLeft > $fromLeft and $toRight < $fromRight ) return false;

            //-------
            // toSort
            $SQL    = SQL::newSelect('category');
            $SQL->setSelect('category_sort');
            $SQL->addWhereOpr('category_parent', $toPid);
            $SQL->addWhereOpr('category_blog_id', $targetBid);
            $SQL->setOrder('category_sort', 'DESC');
            $SQL->setLimit(1);
            $toSort = intval($DB->query($SQL->get(dsn()), 'one')) + 1;

        } else {

            $SQL    = SQL::newSelect('category');
            $SQL->addSelect('category_right');
            $SQL->addSelect('category_sort');
            $SQL->addWhereOpr('category_blog_id', $targetBid);
            $SQL->setOrder('category_right', 'DESC');
            $SQL->setLimit(1);
            if ( !$row = $DB->query($SQL->get(dsn()), 'row') ) die();
            $toLeft     = intval($row['category_right']);
            $toRight    = $toLeft   + 1;
            $toSort     = intval($row['category_sort']) + 1;;
        }

        //-----
        // gap
        $gap    = ($fromRight - $fromLeft) + 1;

        //-------
        // align
        $SQL    = SQL::newUpdate('category');
        $SQL->addWhereOpr('category_blog_id', $targetBid);
        if ( $fromRight > $toRight ) {
            //-------
            // upper
            $delta  = $fromLeft - $toRight;

            $Case   = SQL::newCase();
            $Case->add(
                SQL::newOprBw('category_left', $fromLeft, $fromRight)
                , SQL::newOpr('category_left', $delta, '-')
            );
            $Where  = SQL::newWhere();
            $Where->addWhereOpr('category_left', $toRight, '>=');
            $Where->addWhereOpr('category_left', $fromLeft, '<');
            $Case->add($Where, SQL::newOpr('category_left', $gap, '+'));
            $Case->setElse(SQL::newField('category_left'));
            $SQL->addUpdate('category_left', $Case);

            $Case   = SQL::newCase();
            $Case->add(
                SQL::newOprBw('category_right', $fromLeft, $fromRight)
                , SQL::newOpr('category_right', $delta, '-')
            );
            $Where  = SQL::newWhere();
            $Where->addWhereOpr('category_right', $toRight, '>=');
            $Where->addWhereOpr('category_right', $fromLeft, '<');
            $Case->add($Where, SQL::newOpr('category_right', $gap, '+'));
            $Case->setElse(SQL::newField('category_right'));
            $SQL->addUpdate('category_right', $Case);

        } else {
            //------
            // lower
            $delta  = $toRight - $fromRight - 1;

            $Case   = SQL::newCase();
            $Case->add(
                SQL::newOprBw('category_left', $fromLeft, $fromRight)
                , SQL::newOpr('category_left', $delta, '+')
            );
            $Where  = SQL::newWhere();
            $Where->addWhereOpr('category_left', $fromRight, '>');
            $Where->addWhereOpr('category_left', $toRight, '<');
            $Case->add($Where, SQL::newOpr('category_left', $gap, '-'));
            $Case->setElse(SQL::newField('category_left'));
            $SQL->addUpdate('category_left', $Case);

            $Case   = SQL::newCase();
            $Case->add(
                SQL::newOprBw('category_right', $fromLeft, $fromRight)
                , SQL::newOpr('category_right', $delta, '+')
            );
            $Where  = SQL::newWhere();
            $Where->addWhereOpr('category_right', $fromRight, '>');
            $Where->addWhereOpr('category_right', $toRight, '<');
            $Case->add($Where, SQL::newOpr('category_right', $gap, '-'));
            $Case->setElse(SQL::newField('category_right'));
            $SQL->addUpdate('category_right', $Case);

        }
        $DB->query($SQL->get(dsn()), 'exec');

        //--------
        // sort
        $SQL    = SQL::newUpdate('category');
        $SQL->setUpdate('category_sort', SQL::newOpr('category_sort', 1, '-'));
        $SQL->addWhereOpr('category_sort', $fromSort, '>');
        $SQL->addWhereOpr('category_parent', $fromPid);
        $SQL->addWhereOpr('category_blog_id', $targetBid);
        $DB->query($SQL->get(dsn()), 'exec');

        //--------
        // update
        $SQL    = SQL::newUpdate('category');
        $SQL->addUpdate('category_parent', $toPid);
        $SQL->addUpdate('category_sort', $toSort);
        $SQL->addWhereOpr('category_id', $cid);
        $SQL->addWhereOpr('category_blog_id', $targetBid);
        $DB->query($SQL->get(dsn()), 'exec');
    }
}
