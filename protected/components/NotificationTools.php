<?php

class NotificationTools
{

    public static function generate($userId, $content, $relLink = '')
    {
        $n = new UserNotification();
        $n->user_id = $userId;
        $n->create_time = Tools::now();
        $n->content = $content;
        $n->rel_link = $relLink;
        Tools::saveModel($n);
    }
}