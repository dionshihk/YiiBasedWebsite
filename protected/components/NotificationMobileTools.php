<?php

class NotificationMobileTools
{
    public static function registerDevice($deviceToken, $paramCode, $isDebug, $userId, $type)
    {
        $deviceToken = Tools::purifySqlContent($deviceToken);

        $isNew = false;
        $d = MobileNotificationDevice::model()->find('user_id = "'.$userId.'" AND device_type = '.$type);
        if(!$d)
        {
            $isNew = true;
            $d = new MobileNotificationDevice();
            $d->user_id = $userId;
            $d->device_type = $type;
        }

        $d->reg_time = Tools::now();
        $d->token = $deviceToken;
        $d->is_debug = $isDebug;
        $d->param = $paramCode;
        Tools::saveModel($d);

        Tools::log('Register device: <i>'.$deviceToken.'</i>'.($isNew ? '' : ' (Update)'));
    }

    public static function unregisterDevice($deviceToken, $type)
    {
        //Return false if $deviceToken not exists

        $deviceToken = Tools::purifySqlContent($deviceToken);
        $d = MobileNotificationDevice::model()->find('token = "'.$deviceToken.'" AND device_type = '.$type);
        if(!$d) return false;

        $d->delete();
        Tools::log('Un-register device: <i>'.$deviceToken.'</i>');

        return true;
    }

    public static function pushMessage($userId, $messageBody, $relInfo)
    {
        //$relInfo
        //  CHAT:           {chat_user_id, chat_user_nickname, chat_user_avatar}
        //  NOTIFICATION:   {rel_link}

        if($messageBody)
        {
            $isChat = isset($relInfo['chat_user_id']);
            $iOSParam = array(
                'action'=>$isChat ? 'CHAT' : 'NOTIFICATION',
                'object'=>$relInfo,
            );
            $badgeCount = UserTools::getUnreadCount($userId);

            foreach(MobileNotificationDevice::model()->findAll('user_id = '.$userId) as $device)
            {
                $isSilent = ($isChat && !$device->pm_flag) || (!$isChat && !$device->notif_flag);
                if($device->device_type == 1)
                {
                    NotificationMobileTools::sendSingleIOSMessage($device, $messageBody, $iOSParam, $isSilent, $badgeCount);
                }
            }
        }
    }

    public static function changeMessageBadgeNumber($userId, $targetNumber = 0)
    {
        $iOSParam = array(
            'action'=>'NOTIFICATION',
            'object'=>[],
        );

        foreach(MobileNotificationDevice::model()->findAll('user_id = '.$userId) as $device)
        {
            if($device->device_type == 1)
            {
                NotificationMobileTools::sendSingleIOSMessage($device, null, $iOSParam, true, $targetNumber);
            }
        }
    }

    private static function sendSingleIOSMessage($deviceTokenModel, $messageBody, $messageParameter, $isSilent, $badgeCount)
    {
        try
        {
            $serverUrl = $deviceTokenModel->is_debug ? 'ssl://gateway.sandbox.push.apple.com:2195' : 'ssl://gateway.push.apple.com:2195';
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', Tools::getAbsUrl(UserConfig::$apnsCertificateFile));
            stream_context_set_option($ctx, 'ssl', 'passphrase', UserConfig::$apnsPrivateKey);

            $fp = stream_socket_client($serverUrl, $errCode, $errMessage, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            if(!$fp) throw new Exception("Connection Error #".$errCode.': '.$errMessage);

            $body = array();
            $body['parameters'] = $messageParameter;
            $body['aps'] = $isSilent ?
                    array('content-available'=>'1', 'sound'=>'', 'badge'=>$badgeCount) :
                    array('alert'=>$messageBody, 'sound'=>'default', 'badge'=>$badgeCount);

            $payload = json_encode($body);
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceTokenModel->token) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));


            if($result === false) throw new Exception("fwrite() Error");
            fclose($fp);
        }
        catch(Exception $ex)
        {
            Tools::log('Fail to notify iOS: <i>'.$ex->getMessage().'</i>');
        }

    }
}