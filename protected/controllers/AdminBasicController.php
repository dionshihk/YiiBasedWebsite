<?php

class AdminBasicController extends AdminController
{
    public function init()
    {
        parent::init();
    }

	public function actionIndex()
    {
        $this->pageTitle = '管理中心';
        $this->render('index');
    }

    public function actionStatus()
    {
        $this->pageTitle = '服務器信息';
        $this->pageIntroduction = '服務器主機同網站的信息配置';
        $this->render('status');
    }

    public function actionLogging($id = -1, $page = 0, $keyword = null)
    {
        $logPageSize = 30;

        $fc = new CdbCriteria();
        $fc->order = 'id desc';
        if($id >= 0) $fc->addCondition('user_id = '.$id);

        $keyword = Tools::purifySqlContent($keyword);
        if($keyword)
        {
            $fc->addCondition('log LIKE "%'.$keyword.'%"');
        }

        $total = Log::model()->count($fc);
        $fc->offset = $page * $logPageSize;
        $fc->limit = $logPageSize;

        $this->pageTitle = '操作日誌';

        if($keyword)
        {
            $this->pageTitle .= '搜尋：'.$keyword;
            $this->pageIntroduction = '找到符合要求的日誌記錄 '.$total.' 条';
        }

        $this->render('logging', array(
            'page'=>$page,
            'total'=>$total,
            'pageSize'=>$logPageSize,
            'list'=>Log::model()->findAll($fc),
            'keyword'=>$keyword,
            'userId'=>$id,
        ));
    }

    public function actionConfig()
    {
        $this->renderPhp('EditableConfig.php', '網站參數配置文件');
    }

    public function actionLocation()
    {
        $this->renderPhp('Location.php', '地點配置文件');
    }

    public function actionDirtyWord()
    {
        $this->renderPhp('DirtyWord.php', ' 評論敏感詞列表文件');
    }

    public function actionNews()
    {
        $this->renderPhp('NewsConfig.php', '新聞配置文件');
    }

    public function actionGenReviewThumb($id)
    {
        $r = ProductReview::model()->findByPk($id);
        if($r)
        {
            $thumbUrl = ImageTools::generateThumbInDb($r, true);
            if($thumbUrl)
            {
                Tools::log('Review #'.$id.' thumbed manually: <i>'.$thumbUrl.'</i>');
                $this->setTip('縮略圖重建成功');
            }
        }
    }

    public function actionGenCompWorkThumb($id)
    {
        $c = CompetitionWork::model()->findByPk($id);
        if($c)
        {
            $thumbUrl = ImageTools::generateThumbInDb($c, false);
            if($thumbUrl)
            {
                Tools::log('Competition Work #'.$id.' thumbed manually: <i>'.$thumbUrl.'</i>');
                $this->setTip('縮略圖重建成功');
            }
        }
    }

    public function actionUploadList($page = 0)
    {
        $uploadFiles = array();
        $this->pageTitle = '已上載文件';

        $uploadFolder = '/uploadAdminBasic/';
        $pageSize = 16;
        $folderUrl = Tools::getAbsUrl($uploadFolder);

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

        $this->render('uploadList', array(
            'page'=>$page,
            'pageSize'=>$pageSize,
            'total'=>$total,
            'urlPrefix'=>$uploadFolder,
            'list'=>$uploadFiles,
        ));
    }

    public function actionUploadCustom($genThumb = 0)
    {
        $uploadFolder = '/uploadAdminBasic/';
        $this->pageTitle = '上載文件';
        $this->pageIntroduction = '上載文件會保存於 '.Tools::getAbsUrl($uploadFolder);

        if(isset($_POST['isUpload']))
        {
            $successCount = 0;
            for($i = 1; $i <= 10; $i++)
            {
                $f = Tools::saveFile('file'.$i, null, $uploadFolder);
                if($f)
                {
                    $successCount++;
                }
            }

            $this->setTip('成功上載 '.$successCount.' 個文件');
            $this->redirect('/adminBasic/uploadList');
        }
        else
        {
            $this->render('uploadCustom');
        }
    }

    public function actionI18n($table, $field, $id, $language, $mode)
    {
        //$mode: 1 for simple text, 2 for HTML, 3 for competition work itinerary

        $table = Tools::purifySqlContent($table);
        $field = Tools::purifySqlContent($field);
        $id = intval($id);
        $lang = Tools::purifySqlContent($language);
        $mode = intval($mode);

        if(!isset(UserConfig::$languages[$lang])) $this->error();

        $m = I18nExt::model()->find('record_id = '.$id.' AND table_name = "'.$table.
            '" AND field_name = "'.$field.'" AND lang_code = "'.$lang.'"');
        $isNew = false;

        if(!$m)
        {
            $isNew = true;
            $m = new I18nExt();
            $m->record_id = $id;
            $m->table_name = $table;
            $m->field_name = $field;
            $m->lang_code = $lang;

            if($mode == 3)
            {
                //Copy from original mode
                $originalModel = $table::model()->findByPk($id);
                $m->content = $originalModel[$field];
            }
        }

        if(isset($_POST['content']))
        {
            if($mode == 3)
            {
                $data = array();
                for($i = 0; $i < 10; $i++)
                {
                    if(isset($_POST['c'.$i]) && isset($_POST['p'.$i]))
                    {
                        $item = array(
                            'text'=>$_POST['c'.$i],
                            'pic'=>$_POST['p'.$i],
                        );
                        $data[] = $item;
                    }
                    else
                    {
                        break;
                    }
                }

                $m->content = json_encode($data);
            }
            else
            {
                $m->content = $_POST['content'];
                if($mode == 1) $m->content = Tools::processSingleInput($m->content);
            }

            $m->update_time = Tools::now();
            Tools::saveModel($m);

            $this->setTip('修改成功');
            Tools::log('Update i18n content <i>'.$table.'.'.$field.'</i>');
        }

        if($mode == 2)
        {
            $this->js[] = 'ckeditor.4.5.7/ckeditor';
        }

        $this->pageTitle = '多語言修改';
        $this->pageIntroduction = '數據: '.$m->table_name.'.'.$m->field_name.'，目標語言: '.$lang;
        $this->render('i18n', array(
            'model'=>$m,
            'isNew'=>$isNew,
            'mode'=>$mode,
        ));
    }
}