<?php

class PageController extends BaseController
{
    public function init()
    {
        parent::init();
    }

	public function actionFaq()
    {
        $this->pageTitle = $this->t('faq');
        $this->render('faq');
    }

    public function actionAbout()
    {
        $this->pageTitle = $this->t('about');
        $this->render('about');
    }

    public function actionTerms()
    {
        $this->pageTitle = $this->t('terms');
        $this->render('terms');
    }

    public function actionPrivacy()
    {
        $this->pageTitle = $this->t('privacy');
        $this->render('privacy');
    }
}