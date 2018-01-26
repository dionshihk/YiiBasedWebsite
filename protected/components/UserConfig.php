<?php

class UserConfig
{
    public static $baseUrl = null;
    public static $websiteName = null;

    public static $staticFileRoot = '/static/';
    public static $adminConfigRoot = '/adminConfig/';
    public static $adminUploadFolder = '/uploadOfAdmin/';
    public static $defaultAvatarFile = '/static/image/avatar.jpg';
    public static $defaultAvatarFileForFemale = '/static/image/avatarFemale.jpg';

    public static $adminPageSize = 30;

    public static $adminRoles = array(
        1 => 'Super Admin',
        2 => 'Authorized Admin',
    );

    //Mobile part
    public static $sessionTokenDuration = 100;   //In hours
    public static $apnsCertificateFile = "/assets/app/apns.pem";
    public static $apnsPrivateKey = "";

    //Third-party API part
    public static $awsAppKey = "";
    public static $awsSecretKey = "";
    public static $awsSender = 'no-reply@tourway.org';

    public static $googleAnalyticsId = "UA-88936959-1";

}

$runningMode = Tools::mode();
if($runningMode == 1)
{
    UserConfig::$baseUrl = 'http://tourway.site';
    UserConfig::$websiteName = 'Tourway (Local)';
}
elseif($runningMode == 2)
{
    UserConfig::$baseUrl = 'https://tourway.org';
    UserConfig::$websiteName = 'Tourway';
}