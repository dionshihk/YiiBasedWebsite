<?php

class AdminBaseController extends BaseController
{
    public $moduleList = array(

        'basic'=>array('基本功能', array(
            'index'=>'管理首頁',
        )),

        'system'=>array('高級功能', array(
            'logging'=>'操作日誌',
            'status'=>'服務器狀態',
            'dirtyWord'=>'敏感詞列表',
        )),

        'file'=>array('文件管理', array(
            'upload'=>'上載文件',
            'list'=>'查看已上載文件',
        )),

        'user'=>array('會員管理', array(
            'list'=>'正常會員列表',
            'disabled'=>'封禁會員列表',
            'needVerify'=>'待驗證用戶列表',
        )),

        'page'=>array('頁面管理', array(
            'privacy'=>'Privacy Policy',
            'terms'=>'Terms Conditions',
            'faq'=>'FAQ',
            'about'=>'About Us',
        )),
    );

    public $pageIntroduction = '';

    public function init()
    {
        parent::init();

        if(!$this->user || $this->user->admin_status == 0)
        {
            echo '<h1>No Authorization</h1>';
            die();
        }

        $this->layout = '/layouts/admin';
        $this->css[] = 'css/admin';
        $this->js[] = 'js/admin';

    }

    public function beforeAction($action)
    {
        if(!$this->can($this->id)) $this->noAuthorization();

        return parent::beforeAction($action);
    }

    protected function can($moduleName)
    {
        if($this->isSuperAdmin()) return true;
        if($moduleName == 'system') return false;
        if($moduleName == 'basic') return true;

        return AdminAuth::model()->exists('admin_id = '.$this->user->id.' AND auth_module = "'.$moduleName.'"');
    }

    protected function isSuperAdmin()
    {
        return $this->user->admin_status == 1;
    }

    protected function noAuthorization()
    {
        $this->error('Sorry, your account is not allowed to visit <b>Module #'.$this->id.'</b>');
    }

    protected function error($info = 'Parameter Invalid', $title = 'Error', $buttonName = null, $buttonLink = null)
    {
        //The last 2 parameters does not affect

        Tools::log('Admin runtime error: <i>'.$info.'</i>');

        $this->pageTitle = $title;
        $this->layout = '//admin/layouts/admin';
        $this->render('//admin/basic/error', array('text'=>$info));
        Yii::app()->end();
    }

    protected function echoUserLink($u)
    {
        if($u)
        {
            if(is_string($u))
            {
                $userId = $u;
                $userName = UserTools::getNickname($userId);
            }
            else
            {
                $userId = $u->id;
                $userName = $u->nickname;
            }

            //If the admin cannot visit User module, link to the public profile
            $linkUrl = $this->can('User') ? '/admin/user/detail/'.$userId : '/user/'.$userId;

            $text = '<a href="'.$linkUrl.'" class="link">'.$userName.'</a>';
        }
        else
        {
            $text = '-';
        }

        echo $text;
    }

    protected function renderPageEditor($key, $title, $previewPageUrl = '')
    {
        $fullKey = $key;
        $model = DynamicPage::model()->findByPk($fullKey);
        if(!$model)
        {
            $model = new DynamicPage();
            $model->key = $fullKey;
        }

        if(isset($_POST['content']))
        {
            $content = $_POST['content'];
            $model->content = $content;
            $model->last_modified_time = Tools::now();
            Tools::saveModel($model);

            $this->setTip('頁面內容更新成功');
            Tools::log('Update dynamic page: <i>'.$fullKey.'</i>');
        }

        if($model->last_modified_time)
        {
            $this->pageIntroduction = '頁面上次修改時間：'.$model->last_modified_time.'<br>'.$this->pageIntroduction;
        }

        $this->importFroalaEditor(1);
        $this->pageTitle = '修改: '.$title;
        $this->render('/basic/page', array(
            'model'=>$model,
            'key'=>$key,
            'previewPageUrl'=>$previewPageUrl,
        ));
    }

    protected function renderPhpEditor($file, $title, $relValue = '')
    {
        //$file is a relative path, with PHP extension

        $submittedCode = null;
        $submittedLmt = null;
        $fullFile = Tools::getAbsUrl(UserConfig::$adminConfigRoot.$file);

        if(!is_readable($fullFile)) $this->error('Fail to load file: '.$file);

        if(isset($_POST['code']) && isset($_POST['last_version']))
        {
            $latestLmt = date("Y-m-d H:i:s", filemtime($fullFile));
            $submittedLmt = $_POST['last_version'];
            $submittedCode = $_POST['code'];
            $submittedCode = str_replace(" ", " ", $submittedCode);       //In case of special space

            if(!is_writable($fullFile))
            {
                Tools::log('Disallowed to modify: <i>'.$file.'</i>', 'error');
                $this->setTip('修改失敗，文件系統禁止修改（Read-Only File Access），請聯絡管理員處理');
            }
            elseif($latestLmt != $submittedLmt)
            {
                Tools::log('Inconsistent version: <i>'.$file.'</i>, earlier/latest: <i>'.$submittedLmt.'</i> / <i>'.$latestLmt.'</i>', 'error');
                $this->setTip('修改失敗，該文件已不是最新版本，請 refresh 頁面後重新編輯');
            }
            else
            {
                $result = Tools::checkPhpSyntax($submittedCode);

                if($result)
                {
                    Tools::log('Modify <i>' . $file . '</i>: <i>'.$result.'</i>');
                    $this->setTip('修改失敗，語法有誤（錯誤信息已 logging），請檢查修改後再次保存', 'error');
                }
                else
                {
                    fwrite(fopen($fullFile, 'w'), $submittedCode);
                    Tools::log('Modify <i>' . $file . '</i>, OK');
                    $this->setTip('保存成功');

                    $submittedLmt = null;
                }
            }
        }

        $code = $submittedCode ? $submittedCode : fread(fopen($fullFile, 'r'), filesize($fullFile));
        $lmt = $submittedLmt ? $submittedLmt : date("Y-m-d H:i:s", filemtime($fullFile));
        $this->pageTitle = '修改: '.$title;
        $this->pageIntroduction = '該文件為 PHP Code，不熟悉請勿修改，上次修改時間：'.$lmt;
        $this->render('/basic/php', array(
            'code'=>$code,
            'file'=>$file,
            'relValue'=>$relValue,
            'lmt'=>$lmt,
        ));
    }
}