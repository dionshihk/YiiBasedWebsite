<?php

class VerifyEmailController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionIndex($validateKey)
    {
        $user = null;

        try
        {
            $obj = UserAutoEmail::model()->findByPk($validateKey);
            if(!$obj) throw new Exception('Invalid UserAutoEmail #'.$validateKey);
            if($obj->type != 1) throw new Exception('UserAutoEmail is not for verification');

            $user = User::model()->findByPk($obj->user_id);
            if(!$user) throw new Exception('Invalid User #'.$obj->user_id);
            if($user->account_status != 3) throw new Exception('Invalid account status #'.$user->account_status);

            $user->account_status = 1;
            Tools::saveModel($user);

            $obj->delete();
            Tools::log('Email verification finished', $user);

            $this->error($this->t('account.11', [$user->email]), UserConfig::$websiteName);
        }
        catch (Exception $ex)
        {
            Tools::logException($ex, $user);
            $this->error();
        }
    }
}