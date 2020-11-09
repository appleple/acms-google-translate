<?php

namespace Acms\Plugins\GoogleTranslate;

use Acms\Plugins\GoogleTranslate\Contracts\Translate;

class DeepLTranslate extends Translate
{
    /**
     * @var \BabyMarkt\DeepL\DeepL
     */
    protected $client;

    /**
     * GoogleTranslate constructor.
     * @param \BabyMarkt\DeepL\DeepL $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Translate
     */
    public function translate()
    {
        if (count($this->textTranslations) > 0) {
            $this->textTranslated = $this->client->translate($this->textTranslations, $this->sourceLanguage, $this->targetLanguage);
        }
        if (count($this->htmlTranslations) > 0) {
            $this->htmlTranslated = $this->client->translate($this->htmlTranslations, $this->sourceLanguage, $this->targetLanguage, 'xml');
        }
    }
}
