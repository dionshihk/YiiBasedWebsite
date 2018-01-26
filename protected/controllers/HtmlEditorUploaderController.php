<?php

class HtmlEditorUploaderController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionByFroala()
    {
        //Ref: https://www.froala.com/wysiwyg-editor/docs/server-integrations/php-image-upload

        $f = null;

        try
        {
            $f = Tools::createUpload("file");
            if($f == null) throw new Exception("No file detected");

            ImageTools::resizeImageInScale($f, 100, 1500);
            $response = new StdClass;
            $response->link = $f;
            echo stripslashes(json_encode($response));

            Tools::log('Froala image uploaded: <i>'.$f.'</i>');
        }
        catch(Exception $ex)
        {
            Tools::logException($ex);
            if($f)
            {
                unlink(Tools::getAbsUrl($f));
                Tools::log('Remove invalid file: <i>'.$f.'</i>');
            }
        }
    }
}