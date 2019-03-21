<?php

namespace Acms\Plugins\GoogleTranslate\POST\GoogleTranslate;

use DB;
use SQL;
use App;
use ACMS_POST_Entry_Duplicate;
use ACMS_RAM;

class CreateEntry extends ACMS_POST_Entry_Duplicate
{
    /**
     * @var \Acms\Plugins\GoogleTranslate\Engine
     */
    protected $engine;

    /**
     * @return \Field
     */
    function post()
    {
        $targetBid = $this->Post->get('target_bid');
        $originalEid = $this->Post->get('original_eid');
        $doGoogleTranslate = $this->Post->get('do_google_translate') === '1';
        $this->Post->set('backend', true);
        $this->engine = App::make('google_translate.engine');
        $duplicate = App::make('google_translate.duplicate');

        try {
            $this->customValidate($targetBid, $originalEid);
        } catch (\Exception $e) {
            $this->addError($e->getMessage());
            return $this->Post;
        }
        try {
            $DB = DB::singleton(dsn());
            $newEid = $DB->query(SQL::nextval('entry_id', dsn()), 'seq');
            if (enableApproval(BID, CID) && !sessionWithApprovalAdministrator(BID, CID)) {
                $duplicate->approvalDupe($originalEid, $newEid, $targetBid);
            } else {
                $duplicate->dupe($originalEid, $newEid, $targetBid);
            }
            $this->createGoogleTranslateMetaData($originalEid, $newEid, $targetBid);
            $this->fixCategory($newEid, CID, $targetBid);

            if ($doGoogleTranslate) {
                $this->googleTranslate($targetBid, $newEid);
            }
        } catch (\Exception $e) {
            $this->addError($e->getMessage());
        }
        return $this->Post;
    }

    /**
     * @param int $targetBid
     * @param int $newEid
     */
    protected function googleTranslate($targetBid, $newEid)
    {
        $code = $this->engine->getTargetLangCode(BID, $targetBid);

        $entry = $this->engine->getEntryData($newEid);
        $targetField = $this->engine->getTranslationFieldFromEid($newEid);
        $entryInfo = $this->engine->buildEntryData($entry, $targetField);

        $googleTranslate = App::make('google_translate.google.translate');
        $googleTranslate->setTargetLanguage($code);
        $googleTranslate->addText('title', $entryInfo['title']);
        foreach ($entryInfo['units'] as $i => $unit) {
            $googleTranslate->addText('unit_' . $i, $unit['text']);
        }
        foreach ($entryInfo['fields'] as $i => $field) {
            foreach ($field['value'] as $j => $value) {
                $googleTranslate->addText($field['key'] . '_'. $i, $value);
            }
        }
        $googleTranslate->translate();

        $entryInfo['title'] = $googleTranslate->getText('title');
        foreach ($entryInfo['units'] as $i => & $unit) {
            $unit['text'] = $googleTranslate->getText('unit_' . $i);
        }
        foreach ($entryInfo['fields'] as $i => & $field) {
            $temp = array();
            foreach ($field['value'] as $j => $value) {
                $temp[] = $googleTranslate->getText($field['key'] . '_'. $i);
            }
            $field['value'] = $temp;
        }

        $import = App::make('google_translate.import');
        $import->import($newEid, json_encode($entryInfo));
    }

    /**
     * @param int $eid
     * @param int $cid
     * @param int $targetBid
     */
    protected function fixCategory($eid, $cid, $targetBid)
    {
        if ($targetCid = $this->engine->checkCategory($cid, $targetBid)) {
            $sql = SQL::newUpdate('entry');
            $sql->addUpdate('entry_category_id', $targetCid);
            $sql->addWhereOpr('entry_id', $eid);
            DB::query($sql->get(dsn()), 'exec');
        }
    }

    /**
     * @param int $originalEid
     * @param int $newEid
     * @param int $targetBid
     */
    protected function createGoogleTranslateMetaData($originalEid, $newEid, $targetBid)
    {
        $sql = SQL::newSelect('google_translate_entry');
        $sql->addWhereOpr('base_entry_id', $originalEid);
        $sql->addWhereOpr('relation_eid', $originalEid);
        $sql->addWhereOpr('relation_bid', BID);
        $original = DB::query($sql->get(dsn()), 'row');

        if (!$original) {
            $sql = SQL::newInsert('google_translate_entry');
            $sql->addInsert('base_entry_id', $originalEid);
            $sql->addInsert('relation_eid', $originalEid);
            $sql->addInsert('relation_bid', BID);
            $sql->addInsert('status', 'original');
            DB::query($sql->get(dsn()), 'exec');
        }

        $sql = SQL::newInsert('google_translate_entry');
        $sql->addInsert('base_entry_id', $originalEid);
        $sql->addInsert('relation_eid', $newEid);
        $sql->addInsert('relation_bid', $targetBid);
        $sql->addInsert('status', 'candidate');
        DB::query($sql->get(dsn()), 'exec');
    }

    /**
     * @param int $targetBid
     * @param int $originalEid
     */
    protected function customValidate($targetBid, $originalEid)
    {
        if (empty($targetBid)) {
            throw new \RuntimeException('対応ブログがしていされていません');
        }
        if (empty($originalEid)) {
            throw new \RuntimeException('エントリーが指定されていません。');
        }
        if (BID != ACMS_RAM::entryBlog($originalEid)) {
            throw new \RuntimeException('不正なブログからの操作です。');
        }
        if (roleAvailableUser()) {
            if (!roleAuthorization('entry_edit', BID, $originalEid)) {
                throw new \RuntimeException('不正なアクセスです。');
            }
        } else {
            if (!sessionWithCompilation(BID, false)) {
                if (!sessionWithContribution(BID, false)) {
                    throw new \RuntimeException('不正なアクセスです。');
                }
                if (SUID <> ACMS_RAM::entryUser($originalEid) && !enableApproval(BID, CID)) {
                    throw new \RuntimeException('不正なアクセスです。');
                }
            }
        }
        if (!$this->validate($originalEid)) {
            throw new \RuntimeException('エントリーを複製する権限がありません。');
        }
        if ($this->engine->checkCategory(CID, $targetBid) === false) {
            throw new \RuntimeException('同じコードのカテゴリーがありません。もしくは、カテゴリのカスタムフィールドで例外設定をしてください。');
        }
    }
}
