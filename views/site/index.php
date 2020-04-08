<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?= \Yii::t('app', 'Welcome'); ?> <b><?= \Yii::$app->User->isGuest ? \Yii::t('app', 'Anonymous') : \Yii::$app->User->getIdentity()->username ?></b>
    <br/>
    <?= \Yii::t('app', 'Selected language');?>: <b><?= \Yii::$app->language ?></b>
    
</div>
