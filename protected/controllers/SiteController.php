<?php

class SiteController extends BaseController
{
	public function init()
	{
		parent::init();
	}

	public function actionAjaxLogin($name, $pass) { echo Tools::authUser($name, $pass); }
    public function actionAjaxCheckEmailExist($email)
    {
        $condition = 'email = "'.Tools::purifySqlContent($email).'"';
        if($this->user)
        {
            $condition .= ' AND id <> '.$this->user->id;
        }

        echo User::model()->exists($condition) ? 1 : 0;
    }
	
	public function actionIndex($returnUrl = '/')
	{
        if(isset($_POST['LoginForm']))
        {
            if($this->doLogin($_POST['LoginForm']['name'], $_POST['LoginForm']['password'],
                isset($_POST['LoginForm']['remember'])))
            {
                $this->redirect($returnUrl);
            }
        }


        $this->pageTitle = '';
		$this->render('index', array(

        ));
	}

    public function actionSignup()
    {
        if($this->user) $this->redirect('/account');

        if(isset($_POST['DataForm']))
        {
            try
            {
                $email = $_POST['DataForm']['email'];
                $password = $_POST['DataForm']['password'];

                $u = new User();
                $u->attributes = Tools::processPostInput('DataForm', array('password'));
                $u->avatar_url = '/assets/avatar.jpg';
                $u->join_time = Tools::now();
                $u->password = md5($u->password);

                Tools::saveModel($u);

                $this->doLogin($email, $password);
                Tools::log('Sign up new user <i>'.$email.'</i>');

                $this->redirect('/account');
            }
            catch(Exception $ex)
            {
                Tools::log('Sign up exception: '.$ex->getMessage());
                $this->error('');
            }
        }

        $this->pageTitle = $this->t('nav.8');
        $this->render('signup');
    }

    public function actionSigninPopup() { $this->renderPartial('signinPopup'); }
    public function actionError() { $this->error(); }

    private function doLogin($name, $pass, $rememberLogin = false)
    {
        //Return true or false indicating if success to sign in

        $identity = new UserIdentity($name, $pass);
        $identity->authenticate();

        if($identity->errorCode === UserIdentity::ERROR_NONE)
        {
            $duration = $rememberLogin ? (UserConfig::$signInRememberDays * 24 * 3600) : 0;
            Yii::app()->user->login($identity, $duration);

            $this->user = User::model()->findByPk(Yii::app()->user->id);
            if($this->user)
            {
                $this->user->last_login_time = Tools::now();
                $this->user->prefer_lang = $this->lang->langCode;
                Tools::saveModel($this->user);

                return true;
            }
        }

        return false;
    }

}