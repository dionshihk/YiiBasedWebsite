<?php

class AdminController extends BaseController
{
    public $moduleList = array(

        'Basic'=>array('網站管理', array(
            'index'=>'管理首頁',
            'uploadCustom'=>'上載文件',
            'uploadList'=>'查看已上載文件',
            'logging'=>'操作日誌',
            'status'=>'服務器信息',
            'config'=>'參數配置修改',

        )),
    );

    public $pageIntroduction = null;

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

    protected function renderPhp($file, $title, $relValue = '')
    {
        //$file is a relative path, with PHP extension
        //$type: 1 for normal, 2 for language files

        $fullFile = Tools::getAbsUrl('/adminConfig/'.$file);
        if(!file_exists($fullFile)) $this->error('Fail to load file: '.$file);

        if(isset($_POST['code']))
        {
            if(!is_writable($fullFile))
            {
                Tools::log('Disallowed to modify '.$file);
                $this->setTip('Fail to modify '.$title.': un-writable');
            }
            else
            {
                $fileHandler = fopen($fullFile, 'w');
                fwrite($fileHandler, $_POST['code']);

                $lintCheckOutput = exec('php -l ' . $fullFile, $output);
                $lintCheckOutput = trim($lintCheckOutput);
                $isSyntaxOk = strpos($lintCheckOutput, 'No syntax errors') === 0;

                Tools::log('Modify <i>' . $file . '</i>, ' .
                    ($isSyntaxOk ? 'OK' : 'Error: <i>'.$lintCheckOutput.'</i>'));
                $this->setTip('Update done' .
                    ($isSyntaxOk ? '' : (': <b>code syntax error</b>')));
            }
        }

        $fileHandler = fopen($fullFile, 'r');
        $code = fread($fileHandler, filesize($fullFile));
        $lmt = date("Y-m-d H:i:s", filemtime($fullFile));

        $this->pageTitle = $title;
        $this->pageIntroduction = 'Last Modified：'.$lmt;
        $this->render('//adminBasic/php', array(
            'code'=>$code,
            'file'=>$file,
            'relValue'=>$relValue,
        ));
    }
}
