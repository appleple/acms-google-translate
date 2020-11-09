<?php

namespace Acms\Plugins\GoogleTranslate;

use App;
use ACMS_App;
use BabyMarkt\DeepL\DeepL;
use Google\Cloud\Translate\TranslateClient;
use Acms\Services\Facades\Storage;
use Acms\Services\Facades\Config;
use Acms\Services\Common\HookFactory;
use Acms\Services\Common\InjectTemplate;

class ServiceProvider extends ACMS_App
{
    /**
     * @var string
     */
    public $version = '1.0.5';

    /**
     * @var string
     */
    public $name = 'Google Translate';

    /**
     * @var string
     */
    public $author = 'com.appleple';

    /**
     * @var bool
     */
    public $module = false;

    /**
     * @var bool|string
     */
    public $menu = 'google_translate';

    /**
     * @var string
     */
    public $desc = 'Google Translate API を利用した多言語化プラグインです。';

    protected $installTable = array(
        'google_translate_blog',
        'google_translate_entry',
    );

    /**
     * サービスの初期処理
     */
    public function init()
    {
        require_once dirname(__FILE__) . '/vendor/autoload.php';

        $engine = new Engine();
        $baseBid = $engine->getBaseBlogId(BID);
        $baseBlogConfig = loadBlogConfig($baseBid);
        if ($code = $baseBlogConfig->get('google_translate_base_lang')) {
            $engine->setBaseLangCode($code);
        }
        App::singleton('google_translate.engine', function () use ($engine) {
            return $engine;
        });
        App::singleton('google_translate.duplicate', '\Acms\Plugins\GoogleTranslate\DuplicateEntry');
        App::singleton('google_translate.import', function () {
            return new Import(dirname(__FILE__) . '/schema/schema.json');
        });
        App::bind('google_translate.google.translate', function () use ($baseBlogConfig) {
            if ($baseBlogConfig->get('translate_service') === 'deepl') {
                $client = new DeepL($baseBlogConfig->get('google_translate_deepl_api_key'));
                return new DeepLTranslate($client);
            } else {
                $client = new TranslateClient([
                    'key' => $baseBlogConfig->get('google_translate_google_translate_api_key'),
                ]);
                return new GoogleTranslate($client);
            }
        });

        $hook = HookFactory::singleton();
        $hook->attach('google_translate.hook', new Hook);

        $inject = InjectTemplate::singleton();
        $inject->add('admin-entry-editor-top', PLUGIN_DIR . 'GoogleTranslate/template/entry-lang-check.html');
        $inject->add('admin-category-field', PLUGIN_DIR . 'GoogleTranslate/template/category-config.html');

        if (ADMIN === 'app_google_translate') {
            $inject->add('admin-main', PLUGIN_DIR . 'GoogleTranslate/template/config.html');
            $inject->add('admin-topicpath', PLUGIN_DIR . 'GoogleTranslate/template/topicpath.html');
        }
        /**
         * ToDo: Spoke連携
         * https://www.alleyoop.co.jp/spoke/
         */
//        if (preg_match('/^\/api\/spoke/', REQUEST_PATH)) {
//            require_once dirname(__FILE__) . '/bootstrap.php';
//        }
    }

    /**
     * インストールする前の環境チェック処理
     *
     * @return bool
     */
    public function checkRequirements()
    {
        return true;
    }

    /**
     * インストールするときの処理
     * データベーステーブルの初期化など
     *
     * @return void
     */
    public function install()
    {
        //------------
        // テーブル削除
        dbDropTables($this->installTable);

        //---------------------
        // テーブルデータ読み込み
        $yamlTable = preg_replace('/%{PREFIX}/', DB_PREFIX,
            Storage::get(dirname(__FILE__) . '/schema/db-schema.yaml'));
        $tablesData = Config::yamlParse($yamlTable);
        if (!is_array($tablesData)) {
            $tablesData = array();
        }
        if (!empty($tablesData[0])) {
            unset($tablesData[0]);
        }
        $tableList = array_merge(array_diff(array_keys($tablesData), array('')));

        $yamlIndex = preg_replace('/%{PREFIX}/', DB_PREFIX,
            Storage::get(dirname(__FILE__) . '/schema/db-index.yaml'));
        $indexData = Config::yamlParse($yamlIndex);
        if (!is_array($indexData)) {
            $indexData = array();
        }
        if (!empty($indexData[0])) {
            unset($indexData[0]);
        }
        //---------------
        // テーブル作成
        foreach ($tableList as $tb) {
            $index = isset($indexData[$tb]) ? $indexData[$tb] : null;
            dbCreateTables($tb, $tablesData[$tb], $index);
        }
    }

    /**
     * アンインストールするときの処理
     * データベーステーブルの始末など
     *
     * @return void
     */
    public function uninstall()
    {
        dbDropTables($this->installTable);
    }

    /**
     * アップデートするときの処理
     *
     * @return bool
     */
    public function update()
    {
        return true;
    }

    /**
     * 有効化するときの処理
     *
     * @return bool
     */
    public function activate()
    {
        return true;
    }

    /**
     * 無効化するときの処理
     *
     * @return bool
     */
    public function deactivate()
    {
        return true;
    }
}
