<?php

class LogoutController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionBlocked()
    {
        $this->error('Sorry, your account is currently disabled and cannot be signed in at this moment.<br>
            If you have any question about it, please feel free to contact us.',
            null, 'Logout', '/logout');
    }

    public function actionIndex()
    {
        Yii::app()->user->logout();
        Yii::app()->request->cookies->clear();
        $this->redirect('/');
    }

}