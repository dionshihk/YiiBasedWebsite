<?php

class PageController extends AdminBaseController
{
    public function init()
    {
        parent::init();
    }

    public function actionPrivacy() { $this->renderPageEditor('privacy', 'Privacy Policy', '/page/privacy'); }
    public function actionTerms() { $this->renderPageEditor('terms', 'Terms Conditions', '/page/terms'); }
    public function actionFaq() { $this->renderPageEditor('faq', 'FAQ', '/page/faq'); }
    public function actionAbout() { $this->renderPageEditor('about', 'Terms Conditions', '/page/about'); }
}