<?php

class BaseController extends CController
{
    /** @var User $user */
    public $user = null;

    /** @var LangTools $lang */
    public $lang = null;

    public $currentHighlightKey = null;
    public $css = array('css/core', 'fontawesome.4.7/font-awesome.min');
    public $js = array('js/core');	    //jQuery included in HTML top

    public function init()
    {
        $this->layout = '/layouts/main';
        $this->lang = new LangTools();

        if(!Yii::app()->user->isGuest)
        {
            $this->user = User::model()->findByPk(Yii::app()->user->id);

            if($this->user)
            {
                //Check account status: 1 normal, 2 disabled, 3 need verified

                if($this->user->account_status == 2 && $this->id != 'logout') { $this->redirect('/logout/blocked'); }
            }
        }
    }

    protected function fail()
    {
        $this->error($this->t('error.500'));
    }

    protected function error($info = null, $title = null, $buttonName = null, $buttonLink = '/')
    {
        if(!$info) $info = $this->t('error.404');
        if(!$title) $title = $this->t('error');
        if(!$buttonName) $buttonName = $this->t('error.button');

        $this->pageTitle = $title;
        $this->layout = '/layouts/main';
        $this->render('//site/error', array('info'=>$info, 'button'=>array($buttonName, $buttonLink)));
        Yii::app()->end();
    }

    protected function t($key, $replaceText = [])
    {
        return $this->lang->presentText($key, $replaceText);
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
            $this->error($this->t('error.login'));
        }
    }
}