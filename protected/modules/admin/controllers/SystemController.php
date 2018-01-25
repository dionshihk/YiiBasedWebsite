<?php

class SystemController extends AdminBaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionAdmin($page = 0, $type = 0, $keyword = '')
    {
        $type = intval($type);
        $page = intval($page);
        $keyword = Tools::purifySqlContent($keyword);

        $fc = new CDbCriteria();
        if($type > 0) $fc->addCondition('admin_status = '.$type);
        else $fc->addCondition('admin_status IN (1,2)');
        if($keyword) $fc->addCondition('(nickname LIKE "%'.$keyword.'%") OR (id = "'.$keyword.'") OR (email = "'.$keyword.'")');

        $total = User::model()->count($fc);
        $fc->offset = $page * UserConfig::$adminPageSize;
        $fc->limit = UserConfig::$adminPageSize;

        $this->pageTitle = '管理員列表';

        $this->render('admin', array(
            'type'=>$type,
            'list'=>User::model()->findAll($fc),
            'total'=>$total,
            'pageSize'=>UserConfig::$adminPageSize,
            'page'=>$page,
        ));
    }

    public function actionAdminCreate($nickname, $adminType = 0)
    {
        $nickname = Tools::purifySqlContent($nickname);
        $user = User::model()->find('nickname = "'.$nickname.'"');
        if(!$user) $this->error('找不到 nickname 為 '.$nickname.' 的用戶');

        if(isset($_POST['access_param']))
        {
            $oldAdminStatus = $user->admin_status;
            $user->admin_status = $adminType;
            $user->admin_access_param = $_POST['access_param'];
            Tools::saveModel($user);

            Tools::log('Update user #'.$user->id.' admin status: <i>'.$oldAdminStatus.' to '.$adminType.'</i>');
            $this->setTip('管理員 '.$user->nickname.' 權限設置成功');
        }
        else
        {
            $this->pageTitle = '用戶 '.$user->nickname.' #'.$user->id.' 後台權限管理';
            $this->pageIntroduction = '超級管理員（Super Admin）可以執行任何操作, 請謹慎分配';
            $this->render('createAdmin', array(
                'user'=>$user,
            ));
        }
    }

    public function actionRemoveAdmin($id)
    {
        $u = User::model()->findByPk($id);
        if($u && $id != $this->user->id)
        {
            $u->admin_status = 0;
            $u->admin_access_param = '';
            Tools::saveModel($u);

            $this->setTip('成功移除管理員: '.$u->nickname);
            Tools::log('Remove admin #'.$id);
        }
    }

    public function actionDirtyWord()
    {
        $this->renderPhpEditor('DirtyWord.php', '敏感詞列表文件');
    }

    public function actionStatus()
    {
        $this->pageTitle = 'Server Status';
        $this->render('status');
    }

    public function actionLogging($id = -1, $page = 0, $keyword = null)
    {
        $fc = new CdbCriteria();
        $fc->order = 'id desc';
        if($id >= 0) $fc->addCondition('user_id = '.$id);

        $keyword = Tools::purifySqlContent($keyword);
        if($keyword)
        {
            $fc->addCondition('log LIKE "%'.$keyword.'%"');
        }

        $total = Log::model()->count($fc);
        $fc->offset = $page * UserConfig::$adminPageSize;
        $fc->limit = UserConfig::$adminPageSize;

        $this->pageTitle = 'System Log';
        if($keyword) $this->pageTitle .= '：'.$keyword;

        $this->render('logging', array(
            'page'=>$page,
            'total'=>$total,
            'pageSize'=>UserConfig::$adminPageSize,
            'list'=>Log::model()->findAll($fc),
            'keyword'=>$keyword,
            'userId'=>$id,
        ));
    }
}