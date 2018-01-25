<?php

class UserTools
{
    public static function clearNotification($user, $notifId = 0)
    {
        //$notifId = 0 means Clear All

        $notifId = intval($notifId);
        if($notifId > 0)
        {
            $count = UserNotification::model()->deleteAll('id = '.$notifId.' AND user_id = '.$user->id);
        }
        else
        {
            $count = UserNotification::model()->deleteAll('user_id = '.$user->id);
        }

        Tools::log('Clear notification #'.$notifId.': <i>'.$count.' items</i>');
    }

    public static function checkNicknameExist($name, $currentUser)
    {
        //Include dirty word check

        $name = strtolower(Tools::purifySqlContent($name));
        if(DirtyWordTools::contains($name)) return true;

        $condition = 'nickname = "'.$name.'"';
        if($currentUser) $condition .= ' AND id <> '.$currentUser->id;
        return User::model()->exists($condition);
    }

    public static function checkEmailExist($email, $currentUser)
    {
        $email = strtolower(Tools::purifySqlContent($email));
        $condition = 'email = "'.$email.'"';
        if($currentUser) $condition .= ' AND id <> '.$currentUser->id;
        return User::model()->exists($condition);
    }

    public static function getUnreadCount($userId)
    {
        return UserNotification::model()->count('user_id = '.$userId.' AND has_read = 0');
    }

    public static function getNotificationList($userId, $page, $pageSize)
    {
        $pageSize = intval($pageSize);
        $page = intval($page);

        $fc = new CDbCriteria();
        $fc->order = 'id desc';
        $fc->addCondition('user_id = '.$userId);

        $total = UserNotification::model()->count($fc);
        $fc->offset = $page * $pageSize;
        $fc->limit = $pageSize;

        if($page == 0)
        {
            UserNotification::model()->updateAll(array('has_read' => 1), "user_id = ".$userId);
            NotificationMobileTools::changeMessageBadgeNumber($userId);
        }

        return array(
            'list'=>UserNotification::model()->findAll($fc),
            'total'=>$total,
        );
    }

    public static function getNickname($userId, $withAvatar = false)
    {
        $u = User::model()->find(array("select"=>"nickname,avatar_url", "condition"=>"id = ".intval($userId)));
        if($withAvatar) return $u ? array($u->nickname, $u->avatar_url) : null;
        else return $u ? $u->nickname : '-';
    }

    public static function getNicknameAndAvatarList($conditionSql, $page = 0, $pageSize = 500)
    {
        $ret = array();
        $list = User::model()->findAll(
            array(
                "select"=>"id, nickname,avatar_url",
                "condition"=>$conditionSql,
                "offset"=>$page * $pageSize,
                "limit"=>$pageSize,
            )
        );
        foreach($list as $l)
        {
            $ret[] = array(
                'id'=>$l->id,
                'nickname'=>$l->nickname,
                'avatar'=>$l->avatar_url,
            );
        }

        return $ret;
    }

    public static function register($postFormName)
    {
        //Return user object or null
        //Nickname, Email check should be performed before

        try
        {
            $u = new User();
            $u->attributes = Tools::processPostInput($postFormName, array('password', 'avatar_url'));
            $u->password = Tools::encryptPassword($u->password);
            $u->profile_cover_url = UserConfig::$defaultUserProfileCoverImage;
            $u->reg_time = Tools::now();
            $u->last_login_time = $u->reg_time;
            UserTools::normalizeUserAttributes($u);

            Tools::saveModel($u);
            Tools::log('Sign up #'.$u->id, 'info', $u);
            EmailTools::send($u->email, 'signup', $u->id);

            return $u;
        }
        catch(Exception $ex)
        {
            Tools::logException($ex);
            return null;
        }
    }

    public static function updatePassword($user, $oldPass, $newPass)
    {
        //Return true or false (old password incorrect)

        $oldPass = Tools::encryptPassword($oldPass);
        $newPass = Tools::encryptPassword($newPass);
        if($oldPass != $user->password)
        {
            Tools::log('Fail to change password due to incorrectness');
            return false;
        }
        else
        {
            $user->password = $newPass;
            Tools::saveModel($user);
            Tools::log('Change account password successfully');
            return true;
        }
    }

    public static function updateInfo($user, $postFormName)
    {
        //Return true or false (update failure)

        try
        {
            $postData = Tools::processPostInput($postFormName, array('avatar_url', 'mobile_data'));
            $forbiddenFields = array('id', 'join_time', 'admin_status', 'account_status');

            foreach($forbiddenFields as $f)
            {
                if(isset($postData[$f])) unset($postData[$f]);
            }

            $user->attributes = $postData;
            UserTools::normalizeUserAttributes($user);
            Tools::saveModel($user);
            Tools::log('Update personal information');

            return true;
        }
        catch(Exception $ex)
        {
            Tools::logException($ex);
            return false;
        }
    }

    public static function normalizeUserAttributes($user)
    {
        //$user has been populated from POST
        //No save here

        $user->nickname = trim(strtolower($user->nickname));
        $user->email = $user->email ? trim(strtolower($user->email)) : null;

        if(!$user->avatar_url)
            $user->avatar_url = $user->is_male ? UserConfig::$defaultAvatarFile : UserConfig::$defaultAvatarFileForFemale;
    }
}