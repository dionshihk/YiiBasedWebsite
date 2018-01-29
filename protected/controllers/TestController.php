<?php

class TestController extends BaseController
{

    public function actionA()
    {
        echo sha1('12345');
        echo '<br>';
        echo md5('12345');
        echo '<br>';
        echo Tools::encryptPassword('12345');
    }
}