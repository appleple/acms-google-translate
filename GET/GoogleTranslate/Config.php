<?php

namespace Acms\Plugins\GoogleTranslate\GET\GoogleTranslate;

use App;
use ACMS_GET;
use Template;
use ACMS_Corrector;
use Field;

class Config extends ACMS_GET
{
    public function get()
    {
        $tpl = new Template($this->tpl, new ACMS_Corrector());
        $engine = App::make('google_translate.engine');
        $tpl->add(null, $this->buildField($engine->loadConfig(BID), $tpl));
        return $tpl->get();
    }
}
