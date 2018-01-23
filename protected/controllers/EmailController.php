<?php

class EmailController extends CController
{
    protected $user;
    protected $name;

    public function init()
    {
        parent::init();

        //But I don't know wtf, now it cannot send emails in local env
        //Works fine in production env

        error_reporting(E_ERROR | E_WARNING | E_PARSE);     //Otherwise SES library would trigger E_NOTICE
        $this->layout = '/layouts/email';
    }

    public function actionSend($email, $template, $userId, $para1 = '', $para2 = '')
    {
        try
        {
            $this->user = User::model()->findByPk($userId);
            if($this->user) $this->name = Tools::getUserName($this->user);
            else $this->name = '';

            $emailTitle = $this->getEmailTitle($template, $para1, $para2);

            if($emailTitle)
            {
                $content = $this->render($template,
                    array(
                        'para1'=>$para1,
                        'para2'=>$para2,
                    ), true);

                $result = $this->send($email, $emailTitle, $content);
                Tools::log('Email #'.$template.' to '.$email.': <i>'.$result.'</i>');
            }
            else
            {
                throw new Exception('Unknown template');
            }
        }
        catch(Exception $ex)
        {
            Tools::log('Fail to email #'.$template.': <i>'.$ex->getMessage().'</i>');
        }
    }

    public function actionTestRender($template, $para1 = '', $para2 = '')
    {
        $this->name = 'User';
        echo '<h2>'.$this->getEmailTitle($template, $para1, $para2).'</h2>';
        $this->render($template,
            array(
                'para1'=>$para1,
                'para2'=>$para2,
            ));
    }

    private function send($recipient, $title, $content)
    {
        require_once(Yii::getPathOfAlias('webroot').'/assets/ses/SimpleEmailService.php');
        require_once(Yii::getPathOfAlias('webroot').'/assets/ses/SimpleEmailServiceMessage.php');
        require_once(Yii::getPathOfAlias('webroot').'/assets/ses/SimpleEmailServiceRequest.php');

        $ses = new SimpleEmailService(UserConfig::$awsAppKey, UserConfig::$awsSecretKey, UserConfig::$awsServerAddress);

        $m = new SimpleEmailServiceMessage();
        $m->addTo($recipient);
        $m->setFrom(UserConfig::$sesEmailSenderName.'<'.UserConfig::$sesEmailSenderEmail.'>');
        $m->setSubject($title);
        $m->setMessageFromString('', $content);

        $result = $ses->sendEmail($m);
        $resultString = strval(var_export($result, true));

        return $resultString;
    }

    private function getEmailTitle($template, $para1, $para2)
    {
        //Return NULL if invalid

        $emailTitle = null;
        if($template == 'signup')
        {
            $emailTitle = '';
        }

        return $emailTitle;
    }

    protected function link($url, $name = null)
    {
        if(strpos($url, '/') === 0) $url = UserConfig::$websiteUrl.$url;
        if(!$name) $name = $url;

        return '<a target="_blank" style="color:#22c;text-decoration: underline;" href="'.$url.'">'.$name.'</a>';
    }

    protected function b($text)
    {
        return '<b>'.$text.'</b>';
    }

}