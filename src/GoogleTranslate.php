<?php

namespace Acms\Plugins\GoogleTranslate;

use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;

class GoogleTranslate
{
    /**
     * @var TranslationServiceClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $parent;

    /**
     * @var string
     */
    protected $targetLanguage;

    /**
     * @var array
     */
    protected $textTranslations = [];

    /**
     * @var array
     */
    protected $htmlTranslations = [];

    /**
     * @var array
     */
    protected $textTranslated = [];

    /**
     * @var array
     */
    protected $htmlTranslated = [];

    /**
     * @var array
     */
    protected $textKeys = [];

    /**
     * @var array
     */
    protected $htmlKeys = [];

    /**
     * GoogleTranslate constructor.
     * @param TranslationServiceClient $client
     * @param string $parent
     */
    public function __construct($client, $parent)
    {
        $this->client = $client;
        $this->parent = $parent;
    }

    /**
     * @param string $targetLang
     */
    public function setTargetLanguage($targetLang)
    {
        $this->targetLanguage = $targetLang;
    }

    /**
     * @param string $key
     * @param string $txt
     */
    public function addText($key, $txt)
    {
        if ($txt) {
            $this->textKeys[] = $key;
            $this->textTranslations[] = $txt;
        }
    }

    /**
     * @param string $key
     * @param string $txt
     */
    public function addHtml($key, $txt)
    {
        if ($txt) {
            $this->htmlKeys[] = $key;
            $this->htmlTranslations[] = $txt;
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function getText($key)
    {
        $index = array_search($key, $this->textKeys);
        if ($index === false) {
            return false;
        }
        if (isset($this->textTranslated[$index]['text'])) {
            return $this->textTranslated[$index]['text'];
        }
        return false;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHtml($key)
    {
        $index = array_search($key, $this->htmlKeys);
        if ($index === false) {
            return false;
        }
        if (isset($this->htmlTranslated[$index]['text'])) {
            return $this->htmlTranslated[$index]['text'];
        }
        return false;
    }

    /**
     * Translate
     */
    public function translate()
    {
        if (count($this->textTranslations) > 0) {
            $request = new TranslateTextRequest();
            $request->setParent($this->parent);
            $request->setContents($this->textTranslations);
            $request->setMimeType('text/plain');
            $request->setTargetLanguageCode($this->targetLanguage);

            $response = $this->client->translateText($request);
            $translations = $response->getTranslations();

            $this->textTranslated = [];
            foreach ($translations as $translation) {
                $this->textTranslated[] = ['text' => $translation->getTranslatedText()];
            }
        }
        if (count($this->htmlTranslations) > 0) {
            $request = new TranslateTextRequest();
            $request->setParent($this->parent);
            $request->setContents($this->htmlTranslations);
            $request->setMimeType('text/html');
            $request->setTargetLanguageCode($this->targetLanguage);

            $response = $this->client->translateText($request);
            $translations = $response->getTranslations();

            $this->htmlTranslated = [];
            foreach ($translations as $translation) {
                $this->htmlTranslated[] = ['text' => $translation->getTranslatedText()];
            }
        }
    }
}
