<?php
namespace app\components;

use yii\base\BootstrapInterface;
use app\helpers\Language;
use yii\web\Cookie;

/**
 * Component LanguageSetter is used to check current language set.
 * It runs automatically at Yii bootstrap Init().
 */

class LanguageSetter implements BootstrapInterface {
    private $app;

    /**
     * Function get language from cookie.
     * If cookie does not specify language or is not correct, function try to get language from browser header.
     * If cookie or browser does not send supported language, then default language is in use.
     * 
     * @return string Code of supported language.
     */
    private function setLangFromCookies(): string {
        $preferedLang = $this->getCookieLang();
        if($preferedLang === null) $preferedLang = $this->getBrowserLang();
        if($preferedLang === null) $preferedLang = Language::getDefaultLang();

        return $preferedLang;
    }

    /**
     * Function get language from user's identity.
     * If identity does not have supported language, then language is extracted from setLangFromCookies() function.
     * 
     * @return string Code of supported language.
     */
    private function setLangFromUser(): string {
        $preferedLang = null;
        $identity = $this->app->User->getIdentity();
        
        if(is_a($identity, 'app\models\User')) {
            if(isset($identity->language) == true && $identity->hasProperty('language') == true) {
                $preferedLang = $identity->language;
            }
        } else $preferedLang = null;

        if($preferedLang !== null) {
            $preferedLang = Language::findLanguage($preferedLang);
        }

        if($preferedLang === null) $preferedLang = $this->setLangFromCookies();
        return $preferedLang;
    }

    /** 
     * Function set new supported language for the application.
     * 
     * @param string $lang Supported language code.
     */
    private function languageSet(string $lang) {
        $this->app->language = $lang;
        $this->app->response->cookies->add(new Cookie([
            'name' => 'language',
            'value' => $lang
        ]));
        setlocale(LC_ALL, str_replace('-', '_', $lang) . '.utf8');
    }

    /**
     * Function extract supported language code from browser headers.
     * 
     * @return string|null Function return supported language code, or null if it does not founded in headers.
     */
    private function getBrowserLang() {
        $lang = $this->app->getRequest()->getAcceptableLanguages();
        if( !isset($lang) || count($lang) === 0) return null;

        $l = null;
        foreach($lang as $k) {
            $l = Language::findLanguage($k);
            if(isset($l)) {
                return $l;
            } else {
                $l = Language::getLangFromShortCode($k);
                if(isset($l)) return $l;
            }
        }

        return $l;
    }

    /**
     * Function extract language code from cookie.
     * 
     * @param string|null Function return supported language code, or null if it does not founded in cookie.
     */
    private function getCookieLang() {
        $lang = $this->app->getRequest()->cookies->getValue('language');
        if($lang === null) return null;
        return Language::findLanguage($lang);
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app) {
        $this->app = $app;
        $lang = null;

        try {
            if($this->app->User->isGuest) {
                $lang = $this->setLangFromCookies();
            } else {
                $lang = $this->setLangFromUser();
            }

            if(!Language::languageExists($lang)) throw new \Exception('Undefined language');
        }
        catch(\Exception $e) {
            $this->app->error('Class ' . __CLASS__ . ' - Error Message:\r\n' . $e->getMessage());
            $lang = Language::getDefaultLang();
        }

        $this->languageSet($lang);
        return $lang;    
    }

}

?>