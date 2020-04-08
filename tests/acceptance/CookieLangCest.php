<?php

use Codeception\Template\Acceptance;

class CookieLangCest
{
    public function _before(AcceptanceTester $I) {
    }

    public function testDefaultLang(AcceptanceTester $I) {
        $I->deleteHeader('Accept-Language');
        $I->resetCookie('language');
        $I->amOnPage('/');

        $langSett = new app\components\LanguageSetter(); 
        $langSett->bootstrap(\Yii::$app);

        $I->amOnPage('/');
        $I->see('Anonymous');
    }

    public function testCookieLang(AcceptanceTester $I) {
        $I->deleteHeader('Accept-Language');
        $I->setCookie('language', 'pl-PL');
        $I->amOnPage('/');

        $langSett = new app\components\LanguageSetter(); 
        $langSett->bootstrap(\Yii::$app);

        $I->amOnPage('/');
        $I->see('Anonimie');

        $I->deleteHeader('Accept-Language');
        $I->setCookie('language', 'de-DE');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonym');

        $I->setCookie('language', 'de');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->setCookie('language', '*');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->setCookie('language', '');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->setCookie('language', null);
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');
    }

    public function testHeaderLang(AcceptanceTester $I) {
        $I->haveHttpHeader('Accept-Language', 'pl-PL');
        $I->amOnPage('/');

        $langSett = new app\components\LanguageSetter(); 
        $langSett->bootstrap(\Yii::$app);

        $I->amOnPage('/');
        $I->see('Anonimie');

        $I->haveHttpHeader('Accept-Language', 'de-DE');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonym');

        $I->haveHttpHeader('Accept-Language', 'en');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->haveHttpHeader('Accept-Language', 'bu');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->haveHttpHeader('Accept-Language', '');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->haveHttpHeader('Accept-Language', '*');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');

        $I->haveHttpHeader('Accept-Language', '');
        $I->amOnPage('/');
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('Anonymous');
    }

    public function testUserLang(AcceptanceTester $I) {
        $I->deleteHeader('Accept-Language');
        $I->resetCookie('language');
        \Yii::$app->User->setIdentity(app\models\User::findIdentity(100));
        unset(\Yii::$app->User->getIdentity()->language);
        $I->amOnPage('/');

        $langSett = new app\components\LanguageSetter(); 
        $langSett->bootstrap(\Yii::$app);

        $I->amOnPage('/');
        $I->see('en-US');

        \Yii::$app->User->setIdentity(app\models\User::findIdentity(102));
        \Yii::$app->User->getIdentity()->language = 'pl-PL';
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('pl-PL');

        \Yii::$app->User->getIdentity()->language = null;
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('en-US');

        \Yii::$app->User->getIdentity()->language = 'null';
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('en-US');

        \Yii::$app->User->getIdentity()->language = 'pl_pl';
        $langSett->bootstrap(\Yii::$app);
        $I->amOnPage('/');
        $I->see('pl-PL');
    }
}
