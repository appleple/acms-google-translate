<?php

namespace Acms\Plugins\GoogleTranslate;

class Hook
{
    /**
     * 例: グローバル変数の拡張
     *
     * @param array &$globalVars
     */
    public function extendsGlobalVars(&$globalVars)
    {
        $engine = \App::make('google_translate.engine');
        $baseLangCode = $engine->getBaseLangCode();
        $baseBlogId = $engine->getBaseBlogId(BID);
        $langCode = $baseLangCode;

        if ($code = $engine->getLangCode(BID)) {
            $langCode = $code;
        }
        if (1
            && EID
            && $baseLangCode
            && $baseLangCode !== $langCode
        ) {
            $translatedEntry = $engine->getEntryData(EID);
            if ($translatedEntry['status'] === 'complete') {
                $langCode = $langCode . '-x-mtfrom-' . $baseLangCode;
                if (BID !== $baseBlogId && $translatedEntry['base_entry_id']) {
                    $globalVars->set('TRANSLATION_ORIGIN_URL', acmsLink(array(
                        'bid' => $baseBlogId,
                        'eid' => $translatedEntry['base_entry_id'],
                    )), false);
                }
                $globalVars->set('TRANSLATED_BY_GOOGLE', 'yes');
            }
        }
        $globalVars->set('TRANSLATION_LANG_BASE_CODE', $baseLangCode);
        $globalVars->set('TRANSLATION_LANG_CODE', $langCode);
    }
}
