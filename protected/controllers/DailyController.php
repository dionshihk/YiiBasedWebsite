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

        $result = 'Daily routine:<br>'.implode('<br>', $output);
        Tools::log($result);
        echo $result;
    }

}