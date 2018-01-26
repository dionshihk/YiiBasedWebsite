<?php

class FileController extends AdminBaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionAjaxDeleteFile($name)
    {
        $fullUrl = Tools::getAbsUrl(UserConfig::$adminUploadFolder.$name);
        if(file_exists($fullUrl))
        {
            unlink($fullUrl);
            Tools::log('Remove AdminFile: <i>'.$fullUrl.'</i>');

            $this->setTip('文件刪除成功: '.$name);
        }
    }

    public function actionList($page = 0)
    {
        $uploadFiles = array();
        $pageSize = 16;
        $folderUrl = Tools::getAbsUrl(UserConfig::$adminUploadFolder);

        if(file_exists($folderUrl))
        {
            foreach(scandir($folderUrl) as $f)
            {
                if(substr($f, 0, 1) == '.') continue;
                $lastModifiedTime = date("YmdHis", filemtime($folderUrl.$f));
                $uploadFiles[$f] = $lastModifiedTime;
            }
        }

        $total = count($uploadFiles);
        arsort($uploadFiles);

        $uploadFiles = array_splice($uploadFiles, $page * $pageSize, $pageSize);

        $this->pageTitle = '已上載文件';
        $this->render('list', array(
            'page'=>$page,
            'pageSize'=>$pageSize,
            'total'=>$total,
            'urlPrefix'=>UserConfig::$adminUploadFolder,
            'list'=>$uploadFiles,
        ));
    }

    public function actionUpload()
    {
        if(isset($_FILES['files']))
        {
            $fileRef = $_FILES['files'];
            $successCount = 0;

            //Ref: http://php.net/manual/en/features.file-upload.multiple.php

            $fileCount = count($fileRef['name']);
            for ($i = 0; $i < $fileCount; $i++)
            {
                if($fileRef["error"][$i] == 0)
                {
                    $fileName = $fileRef["name"][$i];
                    $pathInfo = pathinfo($fileName);
                    $extension = strtolower($pathInfo["extension"]);

                    if($extension == 'php')
                    {
                        Tools::log('PHP File upload detected, rejected: <i>'.$fileName.'</i>', 'error');
                    }
                    else
                    {
                        $uniqueName = uniqid('', true);
                        $relUrl = UserConfig::$adminUploadFolder.$uniqueName.".".$extension;
                        $status = move_uploaded_file($fileRef["tmp_name"][$i], Tools::getAbsUrl($relUrl));

                        if($status)
                        {
                            $successCount++;
                            Tools::log('Upload AdminFile '.($i+1).'/'.$fileCount.' <i>'.$fileName.'</i> to <i>'.$relUrl.'</i>');
                        }
                    }
                }
            }

            $this->setTip('成功上載 '.$successCount.' / '.$fileCount.' 個文件');
            $this->redirect('/admin/file/list');
        }
        else
        {
            $this->pageTitle = '上載文件';
            $this->render('upload');
        }
    }
}