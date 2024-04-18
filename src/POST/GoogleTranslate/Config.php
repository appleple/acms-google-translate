<?php

namespace Acms\Plugins\GoogleTranslate\POST\GoogleTranslate;

use ACMS_POST;
use DB;
use SQL;
use ACMS_Validator;
use Acms\Services\Facades\Config as ConfigHelper;

class Config extends ACMS_POST
{
    public function post()
    {
        if (!sessionWithAdministration()) {
            die();
        }
        $config = $this->extract('field');
        $systemConfig = $this->extract('config');
        $config->validate(new ACMS_Validator());
        $systemConfig->validate(new ACMS_Validator());

        if ($this->Post->isValidAll()) {
            $this->save($config, BID);
            ConfigHelper::saveConfig($systemConfig, BID);
            $this->addMessage('コンフィグを保存しました。');
        }
        return $this->Post;
    }

    protected function save($config, $bid = BID)
    {
        $this->reset($bid);

        foreach ($config->getArray('google_translate_relation_bid') as $i => $targetBid) {
            $label = $config->get('google_translate_lang_label', '', $i);
            $code = $config->get('google_translate_lang_code', '', $i);
            if (empty($targetBid) || empty($label) || empty($code)) {
                continue;
            }
            $sql = SQL::newInsert('google_translate_blog');
            $sql->addInsert('base_blog_id', $bid);
            $sql->addInsert('relation_bid', $targetBid);
            $sql->addInsert('lang_label', $label);
            $sql->addInsert('lang_code', $code);
            DB::query($sql->get(dsn()), 'exec');
        }
    }

    protected function reset($bid = BID)
    {
        $sql = SQL::newDelete('google_translate_blog');
        $sql->addWhereOpr('base_blog_id', $bid);
        DB::query($sql->get(dsn()), 'exec');
    }
}
