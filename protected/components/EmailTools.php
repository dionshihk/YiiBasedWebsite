<?php
class EmailTools
{
    public static function checkEmail($email) { return preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/", $email) > 0; }

    public static function send($email, $templateName, $userId, $para1 = '', $para2 = '')
    {
        //$para1/2 should be simple string (like ID, instead of name), otherwise we cannot pass through console

        if(!$email) return;

        Tools::asyncCallByCurl("/email/send",
            "email=".$email."&template=".$templateName."&userId=".$userId."&para1=".$para1."&para2=".$para2);
    }

    public static function sendGroupEmail($groupEmailId)
    {
        Tools::asyncCallByCurl("/email/sendAll", "emailId=".$groupEmailId);
    }
} 