<?php

class BasicController extends AdminBaseController
{
    public function init()
    {
        parent::init();
    }

	public function actionIndex()
    {
        $this->pageTitle = 'Management Center';
        $this->render('index');
    }
}