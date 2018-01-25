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

    public function actionAbout()
    {
        $this->pageTitle = 'About US';
        $this->render('about');
    }

    public function actionTerms()
    {
        $this->pageTitle = 'Terms & Conditions';
        $this->render('terms');
    }

    public function actionPrivacy()
    {
        $this->pageTitle = 'Privacy Policy';
        $this->render('privacy');
    }
}