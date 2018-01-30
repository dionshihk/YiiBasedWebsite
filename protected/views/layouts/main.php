<?php
    $runningMode = Tools::mode();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="/favicon.ico" rel="shortcut icon">
    <?php if($runningMode == 1):?>
        <meta name="robots" content="noindex,nofollow">
    <?php endif?>

    <link href="/favicon.ico" rel="shortcut icon">
    <?php foreach($this->css as $c):?>
        <link rel="stylesheet" type="text/css" href="<?=UserConfig::$staticFileRoot?>/<?=$c?>.css"/>
    <?php endforeach ?>
    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/js/jquery.1.11.min.js"></script>
    <title><?=$this->pageTitle?> ›› <?=UserConfig::$websiteName?></title>
</head>
<body>
    <header>
        <div class="center">
            <a href="/"><img id="headerLogo" src="/assets/image/logo.png"></a>
            <div class="right">
                <?php if($this->user):?>
                    <span class="dropDown" style="z-index:44">
                        <a><?=$this->user->nickname?> <i class="fa fa-angle-down"></i></a>
                        <div class="hidden defaultStyleHiddenList">
                            <div class="list">
                                <a class="entry" href="/1">Sub 1</a>
                                <a class="entry" href="/2">Sub 2</a>
                            </div>
                            <div class="arrow"></div>
                        </div>
                    </span>
                <?php else:?>
                    <a class="icon" popup="/site/signinPopup" popupTitle="<?=$this->t('signin')?>"><i class="fa fa-lg fa-user-o"></i></a>
                <?php endif?>
            </div>
        </div>

    </header>
    <div id="headerHolder"></div>

    <?=$content?>

    <footer class="center">
        <a href="/page/about" class="nav"><?=$this->t('about')?></a>
        <a href="/page/terms" class="nav"><?=$this->t('terms')?></a>
        <a href="/page/privacy" class="nav"><?=$this->t('privacy')?></a>
        <a href="/page/faq" class="nav"><?=$this->t('faq')?></a>
        <div class="copyright">
            <i class="fa fa-copyright"></i> 2018 <?=UserConfig::$websiteName?>
        </div>
    </footer>

    <script type="text/javascript">
        var currentUserId = <?=$this->user?$this->user->id:0?>;
        $('[relHighlightKey="<?=$this->currentHighlightKey?>"]').addClass('sel');

        $(function() {
            <?php
                $sessionTip = Yii::app()->user->getFlash('tip');
                if($sessionTip) { echo 'pop('.json_encode($sessionTip).');'; }
            ?>
        });
    </script>
    <?php foreach($this->js as $j):?>
        <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/<?=$j?>.js"></script>
    <?php endforeach?>
    <?php UITools::echoStat();?>
</body>
</html>