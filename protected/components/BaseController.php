<?php

class BaseController extends CController
{
	/**
	 * @var User
	 */
	public $user = null;

	public $currentHighlightKey = null;
	public $css = array('css/core');
	public $js = array();	//Base JS auto included, others are appended at last

	public function init()
	{
        $this->layout = '/layouts/main';
		
		if(!Yii::app()->user->isGuest) 
		{
			$this->user = User::model()->findByPk(Yii::app()->user->id);
            if($this->user)
            {
                if(!$this->user->is_enabled && $this->id != 'logout')
                {
                    $this->redirect('/logout');
                }
            }
		}
	}

	protected function error($info = null, $title = null, $buttonName = null, $buttonLink = '/')
	{
        if(!$info) $info = "Sorry, the page you are visiting does not exist";
        if(!$title) $title = "Something Goes Wrong";
        if(!$buttonName) $buttonName = "Go Back Home";

        $this->pageTitle = $title;
        $this->layout = '/layouts/main';
		$this->render('//site/error', array('info'=>$info, 'button'=>array($buttonName, $buttonLink)));
		Yii::app()->end();
	}

    protected function setTip($msg)
    {
        if(!Yii::app()->user->hasFlash('tip'))
        {
            Yii::app()->user->setFlash('tip', $msg);
        }
    }

	protected function assureLogin()
	{
		if($this->user == null)
		{
			$this->error($this->t('error.login'));
		} 
	}
}