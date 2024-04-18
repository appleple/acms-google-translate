<?php

namespace Acms\Plugins\GoogleTranslate\GET\GoogleTranslate;

use App;
use ACMS_GET;
use SQL;
use DB;
use Template;
use ACMS_Corrector;

class CategoryList extends ACMS_GET
{
    function get()
    {
        if (empty(CID)) {
            return '';
        }
        $tpl = new Template($this->tpl, new ACMS_Corrector());

        $sql = SQL::newSelect('google_translate_blog');
        $sql->addWhereOpr('base_blog_id', BID);
        $langList = DB::query($sql->get(dsn()), 'all');

        if (empty($langList)) {
            return '';
        }
        foreach ($langList as $lang) {
            $targetBid = $lang['relation_bid'];
            $lang = array(
                'lang_label' => $lang['lang_label'],
                'lang_code' => $lang['lang_code'],
                'google_translate_relation_bid' => $targetBid,
            );
            $tpl->add('lang:loop', $lang);
        }
        return $tpl->get();
    }
}
