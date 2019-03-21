<?php

namespace Acms\Plugins\GoogleTranslate\GET\GoogleTranslate;

use ACMS_GET;
use Template;
use ACMS_Corrector;

class EmptyModule extends ACMS_GET
{
    public function get()
    {
        $tpl = new Template($this->tpl, new ACMS_Corrector());

        return $tpl->get();
    }
}
