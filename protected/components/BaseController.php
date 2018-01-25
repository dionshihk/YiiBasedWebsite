<?php

class BaseController extends CController
{
	/** @var User $user */
	public $user = null;

	public $currentHighlightKey = null;

	public $css = array('css/core', 'fontawesome.4.7/font-awesome.min');
	public $js = array('js/core');	    //jQuery included in HTML top

	public function init()
	{
        $this->layout = '/layouts/main';
		
		if(!Yii::app()->user->isGuest) 
		{
			$this->user = User::model()->findByPk(Yii::app()->user->id);

			if($this->user)
            {
                //Check account status: 1 normal, 2 disabled, 3 need verified

                if($this->user->status == 1 && $this->id != 'logout') { $this->redirect('/logout'); }
            }
		}
	}

	protected function fail()
    {
        $this->error("We are unable to process your request now.<br>Please check your network and retry later.");
    }


	protected function error($info = null, $title = null, $buttonName = null, $buttonLink = '/')
	{
        if(!$info) $info = 'Sorry, the page that you are visiting does not exist.';
        if(!$title) $title = 'Error';
        if(!$buttonName) $buttonName = 'Back To Home';

        $this->pageTitle = $title;
        $this->layout = '/layouts/main';
		$this->render('//site/error', array('info'=>$info, 'button'=>array($buttonName, $buttonLink)));
		Yii::app()->end();
	}

    protected function setTip($msg)
    {
        $sessionName = 'tip';
        if(!Yii::app()->user->hasFlash($sessionName))
        {
            Yii::app()->user->setFlash($sessionName, $msg);
        }
    }
    
	private $isEditorImported = false;
	private $froalaEditorFolder = 'froala.editor.2.7.3';

	//$mode:
    //  1 for admin, with all features
    //  2 for limited feature
    protected function importFroalaEditor($mode)
    {
        if(!$this->isEditorImported)
        {
            $mode = intval($mode);
            $this->isEditorImported = true;

            //Core lib
            $this->css[] = $this->froalaEditorFolder.'/css/froala_editor.min';
            $this->css[] = $this->froalaEditorFolder.'/css/froala_style.min';
            $this->js[] = $this->froalaEditorFolder.'/js/froala_editor.min';

            //Others
            // Ref: https://www.froala.com/wysiwyg-editor/docs/options#toolbarButtons

            $this->css[] = $this->froalaEditorFolder.'/css/plugins/colors.min';
            $this->css[] = $this->froalaEditorFolder.'/css/plugins/image.min';

            $this->js[] = $this->froalaEditorFolder.'/js/plugins/image.min';
            $this->js[] = $this->froalaEditorFolder.'/js/plugins/link.min';
            $this->js[] = $this->froalaEditorFolder.'/js/plugins/url.min';

            if($mode === 1)
            {
                $this->css[] = $this->froalaEditorFolder.'/css/plugins/video.min';
                $this->css[] = $this->froalaEditorFolder.'/css/plugins/table.min';
                $this->css[] = $this->froalaEditorFolder.'/css/plugins/fullscreen.min';

                $this->js[] = $this->froalaEditorFolder.'/js/plugins/colors.min';
                $this->js[] = $this->froalaEditorFolder.'/js/plugins/lists.min';
                $this->js[] = $this->froalaEditorFolder.'/js/plugins/video.min';
                $this->js[] = $this->froalaEditorFolder.'/js/plugins/font_size.min';
                $this->js[] = $this->froalaEditorFolder.'/js/plugins/table.min';
                $this->js[] = $this->froalaEditorFolder.'/js/plugins/align.min';
                $this->js[] = $this->froalaEditorFolder.'/js/plugins/fullscreen.min';
            }
        }
    }

    public function assureLogin()
	{
		if($this->user == null)
		{
		    $this->setTip('You have to sign in before proceeding');
            $this->redirect('/');
		} 
	}
}