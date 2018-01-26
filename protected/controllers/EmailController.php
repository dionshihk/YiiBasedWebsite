<?php

class EmailController extends CController
{
    protected $name;

    public function init()
    {
        parent::init();

        $this->layout = '/layouts/email';
        $this->name = null;
    }

    public function actionSend($email, $template, $userId, $para1 = '', $para2 = '')
    {
        $currentUser = null;

        try
        {
            $currentUser = User::model()->findByPk($userId);
            if($currentUser) $this->name = $currentUser->nickname;

            list($emailTitle, $emailBody) = $this->getEmailTemplateData($template, $para1, $para2);
            if($email)
            {
                $result = AWSTools::sendEmail($email, $emailTitle, $emailBody);
                Tools::log('Email #'.$template.' to '.$email.': <i>'.$result.'</i>', 'info', $currentUser);
            }
            else
            {
                //For debug purpose

                echo '<h1>'.$emailTitle.'</h1>';
                echo $emailBody;
            }
        }
        catch(Exception $ex)
        {
            Tools::logException($ex, $currentUser);
        }
    }

    private function getEmailTemplateData($template, $para1, $para2)
    {
        //Return [title, full-content], or throw exception

        if(!is_readable($this->getViewPath().'/'.$template.'.php')) throw new Exception('Invalid Template #'.$template);

        $emailBody = $this->render($template, array('para1'=>$para1, 'para2'=>$para2), true);
        $titleRegex = '/###(.*)###/';

        if(preg_match($titleRegex, $emailBody, $matches))
        {
            $emailTitle = trim($matches[1]);

            //Clean the title in body
            $emailBody = preg_replace($titleRegex, '', $emailBody);
        }
        else
        {
            throw new Exception("Cannot retrieve ### part for title");
        }

        return array($emailTitle, $emailBody);
    }

    protected function link($url, $name = null)
    {
        if(strpos($url, '/') === 0) $url = UserConfig::$baseUrl.$url;
        if(!$name) $name = $url;

        return '<a href="'.$url.'">'.$name.'</a>';
    }

    protected function b($text)
    {
        return '<b style="margin:0 3px;">'.$text.'</b>';
    }

    public function actionTestAll()
    {
        $this->name = 'Jack';

        $code = Tools::genCode(20, true);
        $list = array(
            array('verify', $code, ''),
            array('resetPassword', $code, ''),
        );

        foreach($list as $item)
        {
            try
            {
                list($emailTitle, $emailBody) = $this->getEmailTemplateData($item[0], $item[1], $item[2]);
                echo '<h2>'.$emailTitle.'</h2>';
                echo $emailBody;
            }
            catch (Exception $ex)
            {
                echo 'Exception for #'.$item[0].': '.$ex->getMessage();
            }

            echo '<br><br>';
        }

    }
}