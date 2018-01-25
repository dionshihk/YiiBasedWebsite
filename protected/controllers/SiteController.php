<?php

class SiteController extends BaseController
{
	public function init()
	{
		parent::init();
	}

    public function actionSigninPopup() { $this->renderPartial('signinPopup'); }
	public function actionAjaxLogin($name, $pass) { echo Tools::authUser($name, Tools::encryptPassword($pass)); }
    public function actionAjaxCheckEmailExist($email) { echo UserTools::checkEmailExist($email, $this->user) ? 1 : 0; }
    public function actionAjaxCheckNicknameExist($name) { echo UserTools::checkNicknameExist($name, $this->user) ? 1 : 0; }

	public function actionIndex($returnUrl = '/')
	{
        if(isset($_POST['LoginForm']))
        {
            if($this->doLogin($_POST['LoginForm']['name'], Tools::encryptPassword($_POST['LoginForm']['password']),
                isset($_POST['LoginForm']['remember'])))
            {
                $this->redirect($returnUrl);
            }
        }

        $this->pageTitle = 'Home';
		$this->render('index', array(

        ));
	}

    public function actionSignup()
    {
        if($this->user) $this->redirect('/');

        if(isset($_POST['DataForm']))
        {
            $u = UserTools::register('DataForm');
            if($u)
            {
                $this->doLogin($u->email, $u->password);
                $this->redirect('/');
            }
            else
            {
                $this->fail();
            }
        }

        $this->pageTitle = 'Sign Up';
        $this->render('signup');
    }

    public function actionError()
    {
        $e = Yii::app()->errorHandler->error;
        if($e && isset($e['code']) && $e['code'] == 500)
        {
            if(isset($e['message']) && isset($e['file']) && isset($e['line']))
            {
                Tools::log('500: <i>'.$e['message'].' ['. $e['file'].' #'.$e['line'].']</i>', 'error');
                $this->fail();
            }

        }

        $this->error();
    }

    private function doLogin($name, $pass, $rememberLogin = true)
    {
        //$pass is encrypted
        //Return true or false indicating if success to sign in

        $identity = new UserIdentity($name, $pass);
        $identity->authenticate();

        if($identity->errorCode === UserIdentity::ERROR_NONE)
        {
            $durationDay = $rememberLogin ? 100 : 0;
            Yii::app()->user->login($identity, $durationDay * 24 * 3600);

            $this->user = User::model()->findByPk(Yii::app()->user->id);
            if($this->user)
            {
                $this->user->last_login_time = Tools::now();
                Tools::saveModel($this->user);

                return true;
            }
        }

        return false;
    }
}