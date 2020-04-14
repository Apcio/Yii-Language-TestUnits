<?php

namespace app\helpers;

/**
 * Language class is a singleton contains information about available languages in application.
 */

class Language {
    private $langAvailable;
    private static $instance = null;

    /**
     * Constructor.
     */
    private function __construct() {
        $this->langAvailable = ["en-US", "pl-PL", "de-DE"];
    }

    /**
     * Return singleton instance.
     * 
     * @return Language Class instance.
     */
    public static function getInstance(): Language {
        if(self::$instance === null) self::$instance = new Language();
        return self::$instance;
    }

    /**
     * Get default language.
     * 
     * @return string Supported default language code.
     */
    public function getDefaultLang(): string {
        $lang = \Yii::$app->request->getPreferredLanguage(self::getInstance()->langAvailable);
        return $lang;
    }

    /**
     * Get array with available languages.
     * 
     * @return array Array of supported language codes.
     */
    public function getAvailabeLang(): array {
        return self::getInstance()->langAvailable;
    }

    /**
     * Verifying supported language code available in application.
     * 
     * @param $lang String language supported code to be searched.
     * @return bool Return true if language is available, false otherwise.
     */
    public function languageExists(string $lang) {
        $l = \Locale::lookup(self::getInstance()->getAvailabeLang(), $lang, false, '');
        return isset($l);
    }

    /**
     * Search input language in available languages.
     * 
     * @param string $lang Language code for comparing.
     * @return string|null Return best match language, null when cannot match.
     */
    public function findLanguage(string $lang) {
        $l = \Locale::lookup(self::getInstance()->getAvailabeLang(), $lang, false);
        if($l === "") $l = null;
        return $l;
    }

    /**
     * Function try to find supported language code from selected language.
     * 
     * @param string $lang Short code, contains only language.
     * @return string|null Function return supported language code, or null if not match.
     */
    public function getLangFromShortCode(string $lang) {
        if($lang === '') return null;
        $parsed = \Locale::parseLocale($lang);
        
        foreach(self::getAvailabeLang() as $k => $v) {
            $l = \Locale::parseLocale($v);
            if($parsed['language'] === $l['language']) return $v;
        }

        return null;
    }

}

?>