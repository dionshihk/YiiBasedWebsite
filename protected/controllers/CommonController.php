<?php

class CommonController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionCkEditorUpload()
    {
        header('Content-Type: text/html; charset=UTF-8');
        $funcNum = $_GET['CKEditorFuncNum'];
        $url = '';
        $msg = '';

        $f = Tools::saveFile("upload", null);
        if($f == null)
        {
            $msg = 'Upload failure';
        }
        else
        {
            try
            {
                ImageTools::resizeImageInScale($f, 200, 1500);
                $url = $f;

                Tools::log('CKEditor image uploaded: <i>'.$url.'</i>');
            }
            catch(Exception $ex)
            {
                unlink(Tools::getAbsUrl($f));
                $msg = $ex->getMessage();
                Tools::log('Fail to upload into CKEditor: <i>'.$ex->getMessage().'</i>, deleted file: <i>'.$f.'</i>');
            }
        }

        echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$funcNum.
            ', "'.$url.'", "'.$msg.'");</script>';
    }
}