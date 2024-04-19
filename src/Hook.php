<?php

namespace Acms\Plugins\GoogleTranslate;

class Hook
{
    /**
     * 例: グローバル変数の拡張
     *
     * @param \Field &$globalVars
     */
    public function extendsGlobalVars(&$globalVars)
    {
        if (ACMS_POST === 'App_Uninstall') {
            // アンインストール時はすでにacms_google_translate_blogテーブルが削除されているため、処理をスキップ
            return;
        }
        $engine = \App::make('google_translate.engine');
        assert($engine instanceof Engine);
        $baseLangCode = $engine->getBaseLangCode();
        $baseBlogId = $engine->getBaseBlogId(BID);
        $langCode = $baseLangCode;

        if ($code = $engine->getLangCode(BID)) {
            $langCode = $code;
        }
        if (
            1
            && EID
            && $baseLangCode
            && $baseLangCode !== $langCode
        ) {
            try {
                $translatedEntry = $engine->getEntryData(EID);
                if ($translatedEntry['status'] === 'complete') {
                    $langCode = $langCode . '-x-mtfrom-' . $baseLangCode;
                    if (BID !== $baseBlogId && $translatedEntry['base_entry_id']) {
                        $globalVars->set('TRANSLATION_ORIGIN_URL', acmsLink([
                            'bid' => $baseBlogId,
                            'eid' => $translatedEntry['base_entry_id'],
                        ]), false);
                    }
                    $globalVars->set('TRANSLATED_BY_GOOGLE', 'yes');
                }
            } catch (\Exception $e) {
            }
        }
        $globalVars->set('TRANSLATION_LANG_BASE_CODE', $baseLangCode);
        $globalVars->set('TRANSLATION_LANG_CODE', $langCode);
    }
}
