<?php

class AWSTools
{
    //All methods may throw

    public static function sendEmail($recipient, $title, $content, $senderAddress = null)
    {
        if(!$recipient) return '[Empty Email]';
        if(!EmailTools::checkEmail($recipient)) return '[Invalid Email]';
        if(!$senderAddress) $senderAddress = UserConfig::$awsSender;

        require_once(Tools::getAbsUrl('/assets/aws/aws-autoloader.php'));

        $client = new Aws\Ses\SesClient([
            'credentials' => [
                'key'    => UserConfig::$awsAppKey,
                'secret' => UserConfig::$awsSecretKey,
            ],
            'region' => 'us-east-1',
            'version' => 'latest',
        ]);

        $result = $client->sendEmail([
            'Destination' => [
                'ToAddresses' => [$recipient],
            ],
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => 'UTF-8',
                        'Data' => $content,
                    ],
                    'Text' => [
                        'Charset' => 'UTF-8',
                        'Data' => strip_tags($content),
                    ],
                ],
                'Subject' => [
                    'Charset' => 'UTF-8',
                    'Data' => $title,
                ],
            ],
            'Source' => $senderAddress,
        ]);

        $resultString = 'MessageID: '.$result->get('MessageId');

        return $resultString;
    }

    public static function sendSms($phoneNumber, $content, $isTransactional = true, $senderId = null)
    {
        if(!$phoneNumber) return '[Empty Phone]';
        if(!$senderId) $senderId = UserConfig::$websiteName;

        //Process the phone format
        $phoneArray = explode('-', $phoneNumber);
        if(count($phoneArray) !== 2) return '[Invalid Phone Format]';

        $regionCode = intval($phoneArray[0]);
        if(!isset(Country::$data[$regionCode])) return '[Invalid Region Code #'.$regionCode.']';
        if(!preg_match("/^\d{3,20}$/", $phoneArray[1])) return '[Invalid Phone Part #'.$phoneArray[1].']';

        if($regionCode === 0) $regionCode = 1;      //US +0 to +1
        $phoneNumber = '+'.$regionCode.$phoneArray[1];

        require_once(Tools::getAbsUrl('/assets/aws/aws-autoloader.php'));

        $client = new Aws\Sns\SnsClient([
            'credentials' => [
                'key'    => UserConfig::$awsAppKey,
                'secret' => UserConfig::$awsSecretKey,
            ],
            'region' => 'ap-northeast-1',
            'version' => 'latest',
        ]);

        $result = $client->publish([
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => $senderId,
                ],
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => $isTransactional ? 'Transactional' : 'Promotional',
                ],
            ],

            'Message' => $content,
            'MessageStructure' => 'SMS',
            'PhoneNumber' => $phoneNumber,
        ]);

        $resultString = 'MessageID: '.$result->get('MessageId');

        return $resultString;
    }
}