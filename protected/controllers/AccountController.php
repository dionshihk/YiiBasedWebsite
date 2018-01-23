<?php

class AccountController extends BaseController
{
    public function init()
    {
        parent::init();

        $this->assureLogin();
        $this->layout = '//layouts/account';
        $this->pageTitle = '';
    }

	public function actionIndex()
    {
        $this->currentHighlightKey = 'accountOverview';
        $this->render('index', array(
            'inviter'=>User::model()->findByPk($this->user->inviter_id),
        ));
    }


    public function actionInfo()
    {
        if(isset($_POST['DataForm']))
        {
            try
            {
                $this->user->attributes = Tools::processPostInput();
                $this->user->passport_number = strtoupper($this->user->passport_number);

                $avatar = Tools::saveFile('avatar', 'jpg', '/uploadAvatar/');
                if($avatar)
                {
                    ImageTools::resizeImage($avatar, 150, 150);
                    $this->user->avatar_url = $avatar;
                }

                Tools::saveModel($this->user);

                $this->setTip($this->t('account.c1'));
                Tools::log('Change account information');
            }
            catch(Exception $ex)
            {
                $this->setTip($this->t('account.c2').'<br>'.$this->t('detail').': '.$ex->getMessage());
                Tools::log('Fail to update information: '.$ex->getMessage());
            }
        }

        $this->currentHighlightKey = 'accountInfo';
        $this->render('info');
    }

    public function actionPassword()
    {
        if(isset($_POST['DataForm']))
        {
            $oldPass = $_POST['DataForm']['p1'];
            $newPass = $_POST['DataForm']['p2'];
            $oldPass = md5($oldPass);
            $newPass = md5($newPass);
            if($oldPass != $this->user->password)
            {
                $this->setTip($this->t('account.c3'));
                Tools::log('Fail to change password for incorrectness');
            }
            else
            {
                $this->user->password = $newPass;
                Tools::saveModel($this->user);

                $this->setTip($this->t('account.4'));
                Tools::log('Change account password');
            }
        }

        $this->currentHighlightKey = 'accountPassword';
        $this->render('password');
    }
}