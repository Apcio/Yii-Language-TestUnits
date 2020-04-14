<?php

namespace tests\unit\helpers;

use app\helpers\Language;

class LanguageTest extends \Codeception\Test\Unit
{
    public function testLanguageSingleton() {
        $s1 = Language::getInstance();
        $s2 = Language::getInstance();
        
        expect_that($s1 == $s2);
        expect_that($s1 === $s2);

        $this->assertInstanceOf('\app\helpers\Language', $s1);
    }

    public function testLanguageExisted() {
        $lang = Language::getInstance()->getDefaultLang();
        expect_that(Language::getInstance()->languageExists($lang));
        expect_not(Language::getInstance()->languageExists('mm-AA') === null);
    }

    public function testLanguageShortCode() {
        expect_that(Language::getInstance()->getLangFromShortCode('en') === 'en-US');
        expect_that(Language::getInstance()->getLangFromShortCode('PL') === 'pl-PL');
        expect_that(Language::getInstance()->getLangFromShortCode('EU') === null);
    }
}
