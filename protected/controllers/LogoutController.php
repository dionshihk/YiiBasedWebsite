<?php

class LogoutController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionBlocked()
    {
        $this->error($this->t('account.2'), null, $this->t('signout'), '/logout');
    }

    public function actionIndex()
    {
        Yii::app()->user->logout();
        Yii::app()->request->cookies->clear();
        $this->redirect('/');
    }

}