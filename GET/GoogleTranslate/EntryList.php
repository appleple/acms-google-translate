<?php

namespace Acms\Plugins\GoogleTranslate\GET\GoogleTranslate;

use App;
use ACMS_GET;
use SQL;
use DB;
use ACMS_RAM;
use Template;
use ACMS_Corrector;

class EntryList extends ACMS_GET
{
    function get()
    {
        if (empty(EID)) {
            return '';
        }
        $tpl = new Template($this->tpl, new ACMS_Corrector());
        $baseBid = $this->getBaseBlogId();
        $baseEid = $this->getBaseEntryId();
        $engine = App::make('google_translate.engine');
        $baseLangCode = $engine->getBaseLangCode();
        $baseLangLabel = $baseLangCode;
        switch ($baseLangCode) {
            case 'ja':
                $baseLangLabel = '日本語';
                break;
            case 'en':
                $baseLangLabel = '英語';
                break;
            case 'zh-CN':
                $baseLangLabel = '简体中文';
                break;
            case 'zh-TW':
                $baseLangLabel = '繁体中文';
                break;
            case 'ko':
                $baseLangLabel = '韓国語';
                break;
            case 'es':
                $baseLangLabel = 'スペイン語';
                break;
            case 'id':
                $baseLangLabel = 'インドネシア';
                break;
            case 'th':
                $baseLangLabel = 'タイ語';
                break;
            case 'fr':
                $baseLangLabel = 'フランス語';
                break;
        }
        $sql = SQL::newSelect('google_translate_blog');
        $sql->addWhereOpr('base_blog_id', $baseBid);
        $langList = DB::query($sql->get(dsn()), 'all');
        array_unshift($langList, array(
            'relation_bid' => $baseBid,
            'lang_label' => $baseLangLabel,
            'lang_code' => $baseLangCode,
        ));
        $managedGoogleTranslate = false;

        foreach ($langList as $lang) {
            $targetBid = $lang['relation_bid'];
            $lang = array(
                'label' => $lang['lang_label'],
                'lang_code' => $lang['lang_code'],
                'relation_bid' => $targetBid,
                'base_bid' => $baseBid,
                'base_eid' => $baseEid,
            );
            if ($entry = $this->searchLocalizationData($baseEid, $targetBid)) {
                $lang = $this->addVars($lang, $entry);
                $managedGoogleTranslate = true;
            } else if ($targetBid === BID) {
                $entry = ACMS_RAM::entry(EID);
                $entry['status'] = 'original';
                $lang = $this->addVars($lang, $entry);
                $managedGoogleTranslate = true;
            } else {
                $lang['status'] = '未作成';
                $lang['status-code'] = '';
            }
            $tpl->add('lang:loop', $lang);
        }
        if (!$managedGoogleTranslate) {
            return '';
        }
        return $tpl->get();
    }

    protected function addVars($lang, $entry)
    {
        $status = $this->getStatusLabel($entry['status']);
        $lang['title'] = $entry['entry_title'];
        $lang['relation_eid'] = $entry['entry_id'];
        $lang['code'] = $entry['entry_code'];
        $lang['entry_status'] = $entry['entry_status'];
        $lang['status'] = $status['label'];
        $lang['status_code'] = $status['code'];
        $lang['category_label'] = ACMS_RAM::categoryName($entry['entry_category_id']);
        $lang['url'] = acmsLink(array(
            'eid' => $entry['entry_id'],
        ));
        $lang['edit_url'] = acmsLink(array(
            'bid' => $entry['entry_blog_id'],
            'eid' => $entry['entry_id'],
            'cid' => $entry['entry_category_id'],
            'admin' => 'entry_editor',
        ));
        $lang['category_editUrl'] = acmsLink(array(
            'bid' => $entry['entry_blog_id'],
            'cid' => $entry['entry_category_id'],
            'admin' => 'category_edit',
        ));
        return $lang;
    }

    protected function getStatusLabel($status)
    {
        $label = array(
            'label' => '依頼前',
            'code' => 'danger',
        );
        switch ($status) {
            case 'original':
                $label['label'] = 'オリジナル';
                $label['code'] = 'info';
                break;
            case 'candidate':
                $label['label'] = '人力翻訳';
                $label['code'] = 'warning';
                break;
            case 'complete':
                $label['label'] = '機械翻訳';
                $label['code'] = 'success';
                break;
        }
        return $label;
    }

    protected function searchLocalizationData($eid, $targetBid)
    {
        $sql = SQL::newSelect('google_translate_entry');
        $sql->addLeftJoin('entry', 'entry_id', 'relation_eid');
        $sql->addWhereOpr('base_entry_id', $eid);
        $sql->addWhereOpr('relation_bid', $targetBid);
        if ($entry = DB::query($sql->get(dsn()), 'row')) {
            if ($entry['entry_id']) {
                return $entry;
            }
            if ($relationEid = $entry['relation_eid']) {
                $sql = SQL::newDelete('google_translate_entry');
                $sql->addWhereOpr('relation_eid', $relationEid);
                $sql->addWhereOpr('relation_bid', $targetBid);
                DB::query($sql->get(dsn()), 'exec');
            }
        }
        return false;
    }

    protected function getBaseBlogId()
    {
        $sql = SQL::newSelect('google_translate_blog');
        $sql->addSelect('base_blog_id');
        $sql->addWhereOpr('relation_bid', BID);
        $baseBid = DB::query($sql->get(dsn()), 'one');
        if (empty($baseBid)) {
            $baseBid = BID;
        }
        return $baseBid;
    }

    protected function getBaseEntryId()
    {
        $sql = SQL::newSelect('google_translate_entry');
        $sql->addWhereOpr('relation_eid', EID);
        $sql->addWhereOpr('relation_bid', BID);
        $baseEid = DB::query($sql->get(dsn()), 'one');
        if (empty($baseEid)) {
            $baseEid = EID;
        }
        return $baseEid;
    }
}
