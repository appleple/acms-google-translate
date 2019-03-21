<?php

namespace Acms\Plugins\GoogleTranslate;

use Slim\Http\Request;
use Slim\Http\Response;
use Acms\Plugins\GoogleTranslate\Exceptions\NotFoundException;
use App;
use DB;
use SQL;
use SQL_Select;
use ACMS_RAM;
use Acms\Services\Facades\Config;

class SpokeController
{
    /**
     * @var \Acms\Plugins\GoogleTranslate\Engine
     */
    protected $engine;

    /**
     * @var \Acms\Plugins\GoogleTranslate\Import
     */
    protected $import;

    /**
     * @var array
     */
    protected $targetField = array();

    public function __construct()
    {
        $this->engine = App::make('google_translate.engine');
        $this->import = App::make('google_translate.import');
    }

    /**
     * 翻訳対象エントリ一覧
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function index(Request $request, Response $response)
    {
        $json = array(
            'total_count' => 0,
            'entries' => array(),
        );
        $limit = 100;
        $offset = 0;
        $params = $request->getQueryParams();

        if (isset($params['limit']) && intval($params['limit']) > 0) {
            $limit = intval($params['limit']);
        }
        if (isset($params['offset']) && intval($params['offset']) > 0) {
            $offset = intval($params['offset']);
        }

        $db = DB::singleton(dsn());
        $sql = SQL::newSelect('google_translate_entry', 'multi_lang_entry');
        $sql->addLeftJoin('entry', 'entry_id', 'relation_eid', 'entry', 'multi_lang_entry');
        $sql->addLeftJoin('google_translate_blog', 'relation_bid', 'relation_bid', 'multi_lang_blog', 'multi_lang_entry');
        $sql->addWhereOpr('status', 'original');
        $sql->addWhereOpr('entry_title', null, '<>');

        $amount = new SQL_Select($sql);
        $amount->addSelect('DISTINCT(entry_id)', 'amount', null, 'COUNT');
        $json['total_count'] = intval($db->query($amount->get(dsn()), 'one'));

        $sql->addOrder('entry_id', 'asc');
        $sql->setLimit($limit, $offset);
        $q = $sql->get(dsn());
        $db->query($q, 'fetch');

        $items = array();
        $ids = array();
        while ($entry = $db->fetch($q)) {
            $items[] = $entry;
            $ids[] = $entry['entry_id'];
        }
        $translationsList = $this->getTranslationsList($ids);
        foreach ($items as $entry) {
            $jaId = intval($entry['entry_id']);
            $item = $this->engine->buildEntryData($entry, array(), false);
            $item['translations'] = null;
            if (isset($translationsList[$jaId])) {
                $item['translations'] = $translationsList[$jaId];
            }
            $json['entries'][] = $item;
        }
        return $response->withJson($json, 200);
    }

    /**
     * 翻訳対象エントリー詳細
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function show(Request $request, Response $response)
    {
        $eid = $request->getAttribute('eid');
        if (!is_numeric($eid)) {
            return $this->errorResponse($response, 'Invalid entry id.', 400);
        }
        try {
            $entry = $this->engine->getEntryData($eid);
            $targetField = $this->engine->getTranslationFieldFromEid($eid);
            return $response->withJson($this->engine->buildEntryData($entry, $targetField), 200);
        } catch (\Exception $e) {
            return $this->errorResponse($response, $e->getMessage(), 404);
        }
    }

    /**
     * 翻訳対象エントリー更新
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     * @throws \Webmozart\Json\ValidationFailedException
     */
    public function update(Request $request, Response $response)
    {
        try {
            $eid = $request->getAttribute('eid');
            $json = $request->getBody();

            $this->import->import($eid, $json);
        } catch (NotFoundException $e) {
            return $this->errorResponse($response, $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse($response, $e->getMessage(), 400);
        }
        return $response->withStatus(204);
    }

    /**
     * エラーレスポンス
     *
     * @param \Slim\Http\Response $response
     * @param string $message
     * @param int $code
     * @return \Slim\Http\Response
     */
    protected function errorResponse($response, $message, $code)
    {
        return $response->withJson(array(
            'message' => $message,
            'code' => $code,
        ), $code);
    }

    /**
     * @param array $ids
     * @return array
     */
    protected function getTranslationsList($ids)
    {
        $translationsList = array();

        $db = DB::singleton(dsn());
        $sql = SQL::newSelect('google_translate_entry', 'multi_lang_entry');
        $sql->addSelect('base_entry_id');
        $sql->addSelect('relation_eid');
        $sql->addSelect('lang_code');
        $sql->addLeftJoin('google_translate_blog', 'relation_bid', 'relation_bid', 'multi_lang_blog', 'multi_lang_entry');
        $sql->addWhereIn('base_entry_id', $ids);
        $translations = $db->query($sql->get(dsn()), 'all');

        foreach ($translations as $translation) {
            $jaId = intval($translation['base_entry_id']);
            $relationId = intval($translation['relation_eid']);
            if ($jaId === $relationId) {
                continue;
            }
            if (!isset($translationsList[$jaId])) {
                $translation[$jaId] = array();
            }
            $translationsList[$jaId][$translation['lang_code']] = $this->getDetailEndpoint($relationId);
        }
        return $translationsList;
    }


    protected function getDetailEndpoint($id)
    {
        return BASE_URL . 'api/spoke/entries/' . $id . '/';
    }
}
