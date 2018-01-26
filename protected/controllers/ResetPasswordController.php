<?php

class ResetPasswordController extends BaseController
{
    public function init()
    {
        parent::init();

        $this->pageTitle = $this->t('account.7');
    }

    public function actionIndex($validateKey)
    {
        $user = null;

        try
        {
            $obj = UserAutoEmail::model()->findByPk($validateKey);
            if(!$obj) throw new Exception('Invalid UserAutoEmail #'.$validateKey);
            if($obj->type != 2) throw new Exception('UserAutoEmail is not for password reset');

            $user = User::model()->findByPk($obj->user_id);
            if(!$user) throw new Exception('Invalid User #'.$obj->user_id);

            if(isset($_POST['password']))
            {
                $user->password = Tools::encryptPassword($_POST['password']);
                Tools::saveModel($user);

                $obj->delete();
                Tools::log('Password reset finished', $user);

                $this->error($this->t('account.10'), $this->t('account.7'));
            }
            else
            {
                $this->render('index', array("user"=>$user));
            }
        }
        catch (Exception $ex)
        {
            Tools::logException($ex, $user);
            $this->error();
        }
    }

    public function actionForgot()
    {
        $email = '';
        if(isset($_POST['email']))
        {
            $email = Tools::purifySqlContent($_POST['email']);
            $user = User::model()->find('email = "'.$email.'"');
            if($user)
            {
                UserTools::sendAutoEmail($user, 2);
                $this->error($this->t('account.9'), $this->t('account.7'));
            }
            else
            {
                $this->setTip($this->t('account.8'));
            }
        }

        $this->render('forgot', array('email'=>$email));
    }
}