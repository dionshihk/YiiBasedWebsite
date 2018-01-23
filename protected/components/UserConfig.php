<?php
class UserConfig 
{
	//If debugging
	//	- Output original model save error
	public static $isDebugging = true;

    public static $websiteUrl = 'http://mask.club';
    public static $websiteName = 'Mask Club';
	public static $staticFileRoot = '/static/';
    public static $signInRememberDays = 15;

    public static $adminPageSize = 18;

    //SES part
    public static $sesEmailSenderEmail = "info@mask.club";
    public static $sesEmailSenderName = "Mask Team";
    public static $awsAppKey = "AKIAJXTIZANSYCLMISSQ";
    public static $awsSecretKey = "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";
    public static $awsServerAddress = "email.us-east-1.amazonaws.com";


}