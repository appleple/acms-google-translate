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
    protected $translations = array();

    /**
     * @var array
     */
    protected $translated = array();

    /**
     * @var array
     */
    protected $keys = array();

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
        $this->keys[] = $key;
        $this->translations[] = $txt;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getText($key)
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            return false;
        }
        if (isset($this->translated[$index]['text'])) {
            return $this->translated[$index]['text'];
        }
        return false;
    }

    /**
     * Translate
     */
    public function translate()
    {
        $this->translated = $this->client->translateBatch($this->translations, array(
            'target' => $this->targetLanguage,
            'format' => 'text',
        ));
    }
}
