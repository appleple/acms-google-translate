<?php

namespace Acms\Plugins\GoogleTranslate\Contracts;

abstract class Translate
{
    /**
     * @var string
     */
    protected $sourceLanguage;

    /**
     * @var string
     */
    protected $targetLanguage;

    /**
     * @var array
     */
    protected $textTranslations = array();

    /**
     * @var array
     */
    protected $htmlTranslations = array();

    /**
     * @var array
     */
    protected $textTranslated = array();

    /**
     * @var array
     */
    protected $htmlTranslated = array();

    /**
     * @var array
     */
    protected $textKeys = array();

    /**
     * @var array
     */
    protected $htmlKeys = array();

    /**
     * @param string $sourceLang
     */
    public function setSourceLanguage($sourceLang)
    {
        $this->sourceLanguage = $sourceLang;
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
    abstract public function translate();
}
