<?php

namespace tests\unit\components;

use app\components\LanguageSetter;
use app\helpers\Language;
use Yii;

class LanguageSetterTest extends \Codeception\Test\Unit
{
    public function testLanguageSetterDefaultLang() {
        $lang = Language::getDefaultLang();
        $setter = new LanguageSetter();
        expect_that($setter->bootstrap(Yii::$app) === $lang);
    }
}