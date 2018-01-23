<?php

class DailyController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    //crontab -e 之後增加內容：0 1 * * * curl http://mask.club/daily 之後 service crond restart
    //crontab -l 可查看是否已添加該任務
    public function actionIndex()
    {
        $output = array();


        $tip = 'Daily routine:<br>'.implode('<br>', $output);
        Tools::log($tip);
        echo $tip;
    }

}