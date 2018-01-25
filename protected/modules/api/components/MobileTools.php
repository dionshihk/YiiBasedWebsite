<?php

class MobileTools
{
    public static function genSessionToken($userId)
    {
        do
        {
            $token = Tools::genCode(12, true);
        }
        while(MobileSessionToken::model()->exists('token = "'.$token.'"'));

        $tokenObj = new MobileSessionToken();
        $tokenObj->token = $token;
        $tokenObj->user_id = $userId;
        $tokenObj->create_time = Tools::now();
        $tokenObj->expire_time = $tokenObj->create_time;
        $tokenObj->create_client = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown Agent';
        Tools::saveModel($tokenObj);
        AdminTools::extendSessionTokenExpire($token);

        return $token;
    }

    public static function extendSessionTokenExpire($token)
    {
        MobileSessionToken::model()->updateByPk($token,
            array('expire_time'=>
                new CDbExpression('DATE_ADD(NOW(), INTERVAL '.UserConfig::$sessionTokenDuration.' HOUR)'))
        );
    }

}