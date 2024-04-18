<?php

namespace Acms\Plugins\GoogleTranslate\GET\GoogleTranslate;

use App;
use ACMS_GET;
use SQL;
use DB;
use Template;
use ACMS_Corrector;

class BlogList extends ACMS_GET
{
    public function get()
    {
        $tpl = new Template($this->tpl, new ACMS_Corrector());

        $sql = SQL::newSelect('blog');
        $all = DB::query($sql->get(dsn()), 'all');

        return $tpl->render(array(
            'blog' => $all,
        ));
    }
}
