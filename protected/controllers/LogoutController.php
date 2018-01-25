<?php

class LogoutController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        Yii::app()->user->logout();
        Yii::app()->request->cookies->clear();
        $this->redirect('/');
    }

}