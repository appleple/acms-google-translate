<?php

namespace Acms\Plugins\GoogleTranslate;

use Acms\Plugins\GoogleTranslate\Contracts\Translate;

class GoogleTranslate extends Translate
{
    /**
     * @var \Google\Cloud\Translate\TranslateClient
     */
    protected $client;

    /**
     * GoogleTranslate constructor.
     * @param \Google\Cloud\Translate\TranslateClient $client
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
            $this->textTranslated = $this->client->translateBatch($this->textTranslations, array(
                'target' => $this->targetLanguage,
                'format' => 'text',
            ));
        }
        if (count($this->htmlTranslations) > 0) {
            $this->htmlTranslated = $this->client->translateBatch($this->htmlTranslations, array(
                'target' => $this->targetLanguage,
                'format' => 'html',
            ));
        }
    }
}
