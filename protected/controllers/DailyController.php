<?php

class DailyController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    //crontab -e 之後增加內容：0 1 * * * curl http://xxx.com/daily 之後 service crond restart
    //      -L: auto handle redirection
    //      -k: auto handle SSL
    //之後 (sudo) service crond restart
    //crontab -l 可查看是否已添加該任務

    public function actionIndex()
    {
        $output = array();
        $output[] = $this->clearExpiredMobileSessions();
        $output[] = $this->clearExpiredAutoEmails();

        $result = implode('<br>', $output);
        Tools::log($result);
        echo $result;
    }

    private function clearExpiredMobileSessions()
    {
        $count = MobileSessionToken::model()->deleteAll('expire_time < DATE_ADD(NOW(), INTERVAL -1 MINUTE)');
        return 'Remove expired-mobile-session: <i>'.$count.'</i>';
    }

    private function clearExpiredAutoEmails()
    {
        $count = UserAutoEmail::model()->deleteAll('create_time < DATE_ADD(NOW(), INTERVAL -12 HOUR)');
        return 'Remove user-auto-email: <i>'.$count.'</i>';
    }
}