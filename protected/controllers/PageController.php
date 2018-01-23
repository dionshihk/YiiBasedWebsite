<?php

class PageController extends BaseController
{
    public function init()
    {
        parent::init();
    }

	public function actionFaq()
    {
        $this->pageTitle = 'FAQ';
        $this->render('faq');
    }
}