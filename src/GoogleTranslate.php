<?php

namespace Acms\Plugins\GoogleTranslate;

class GoogleTranslate
{
    /**
     * @var \Google\Cloud\Translate\TranslateClient
     */
    protected $client;

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
     * @param \Google\Cloud\Translate\TranslateClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
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
        $this->textKeys[] = $key;
        $this->textTranslations[] = $txt;
    }

    /**
     * @param string $key
     * @param string $txt
     */
    public function addHtml($key, $txt)
    {
        $this->htmlKeys[] = $key;
        $this->htmlTranslations[] = $txt;
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
            $this->textTranslated = $this->client->translateBatch($this->textTranslations, [
                'target' => $this->targetLanguage,
                'format' => 'text',
            ]);
        }
        if (count($this->htmlTranslations) > 0) {
            $this->htmlTranslated = $this->client->translateBatch($this->htmlTranslations, [
                'target' => $this->targetLanguage,
                'format' => 'html',
            ]);
        }
    }
}
