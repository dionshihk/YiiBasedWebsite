<?php

class UserController extends AdminBaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionToggleEnable($id)
    {
        $u = User::model()->findByPk($id);
        if($u && $u->id != $this->user->id)
        {
            $u->account_status = $u->account_status == 1 ? 2 : 1;
            Tools::saveModel($u);
            Tools::log('Alter user #'.$id.' account status to '.$u->account_status);

            $this->setTip('修改成功');
        }
    }

    public function actionResetPassword($id, $password)
    {
        $u = User::model()->findByPk($id);
        if($u)
        {
            $u->password = Tools::encryptPassword($password);
            Tools::saveModel($u);
            Tools::log('Reset password of #'.$id);
            $this->setTip('密碼重設成功');
        }
    }

    public function actionUpdateField($id, $fieldName, $value)
    {
        $u = User::model()->findByPk($id);
        if($u)
        {
            if($u->hasAttribute($fieldName) && !in_array($fieldName, array('id', 'score')))
            {
                $oldValue = $u[$fieldName];
                $u[$fieldName] = $value;
                try
                {
                    Tools::saveModel($u);
                    Tools::log('Alter #'.$id.' <i>'.$fieldName . '</i> from <i>'.$oldValue.'</i> to <i>'.$value.'</i>');
                    $this->setTip('修改成功');
                }
                catch(Exception $ex)
                {
                    Tools::log('Fail to update #'.$id.' <i>'.$fieldName.'</i> to '.$value.': <i>'.$ex->getMessage().'</i>');
                    $this->setTip('修改失敗，請查看日誌文件檢查錯誤原因');
                }
            }
            else
            {
                Tools::log('Invalid field to update: '.$fieldName);
            }
        }
    }

    public function actionCreateNotif($id, $content, $link)
    {
        NotificationTools::admin($id, $content, $link);
        Tools::log('Issue notification to #'.$id.' by admin: <i>'.$content.'</i>');
        $this->setTip('通知下發成功');
    }

    public function actionCreateNotifPopup($id)
    {
        $u = User::model()->findByPk($id);
        if($u) $this->renderPartial('createNotif', array('user' => $u));
    }

    public function actionUpdateFieldPopup($id, $fieldName)
    {
        $u = User::model()->findByPk($id);
        if($u)
        {
            $this->renderPartial('updateField', array(
                'user' => $u,
                'field' => $fieldName,
            ));
        }
    }

    public function actionDetail($id)
    {
        $u = User::model()->findByPk($id);
        if(!$u) $this->error();

        $this->pageTitle = '會員資料';
        $this->render('detail', array('user'=>$u));
    }

	public function actionList($page = 0)
    {
        $fc = new CDbCriteria();
        $fc->addCondition('account_status = 1');

        $this->pageTitle = '正常會員列表';
        $this->renderList($fc, $page);
    }

    public function actionDisabled($page = 0)
    {
        $fc = new CDbCriteria();
        $fc->addCondition('account_status = 2');

        $this->pageTitle = '被封鎖會員列表';
        $this->renderList($fc, $page);
    }

    public function actionNeedVerify($page = 0)
    {
        $fc = new CDbCriteria();
        $fc->addCondition('account_status = 3');

        $this->pageTitle = '待驗證會員列表';
        $this->renderList($fc, $page);
    }

    public function actionSearch($keyword, $page = 0)
    {
        $keyword = Tools::purifySqlContent($keyword);
        if(!$keyword)
        {
            $this->redirect('/admin/user/list');
        }

        $this->pageTitle = '會員搜尋：'.$keyword;

        $fc = new CDbCriteria();
        $fc->distinct = true;
        $fc->addCondition('(nickname LIKE "%'.$keyword.'%") OR (first_name LIKE "%'.$keyword.'%") OR (last_name LIKE "%'.$keyword.
            '%") OR (id = "'.$keyword.'") OR (email = "'.$keyword.'")');
        $this->renderList($fc, $page);
    }

    public function actionNotification($id)
    {
        $u = User::model()->findByPk($id);
        if(!$u) $this->error();

        $fc = new CDbCriteria();
        $fc->order = 'id desc';
        $fc->addCondition('user_id = '.$id);
        $list = UserNotification::model()->findAll($fc);

        $this->pageTitle = '通知管理';
        $this->pageIntroduction = '會員帳號: '.$u->first_name.' '.$u->last_name.'，已收到通知數: '.count($list);
        $this->render('notification', array(
            'user'=>$u,
            'list'=>$list,
        ));
    }

    private function renderList($fc, $page)
    {
        $fc->order = 'id desc';

        $total = User::model()->count($fc);
        $fc->offset = $page * UserConfig::$adminPageSize;
        $fc->limit = UserConfig::$adminPageSize;

        $this->pageIntroduction = '符合條件用戶數：'.$total;
        $this->render('list', array(
            'page'=>$page,
            'total'=>$total,
            'pageSize'=>UserConfig::$adminPageSize,
            'list'=>User::model()->findAll($fc),
        ));
    }
}