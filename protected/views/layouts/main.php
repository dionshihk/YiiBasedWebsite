<!DOCTYPE html>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--[if lte IE 7]><script>alert("<?=$this->t('error.ie')?>");</script><![endif]-->
    <link href="/favicon.ico" rel="shortcut icon">
    <?php foreach($this->css as $c):?><link rel="stylesheet" type="text/css" href="<?=UserConfig::$staticFileRoot?>/<?=$c?>.css"/><?php endforeach ?>
    <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="<?=UserConfig::$staticFileRoot?>/css/ie8.css"/><![endif]-->
    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/js/core.js"></script>
    <?php foreach($this->js as $j) Yii::app()->clientScript->registerScriptFile(UserConfig::$staticFileRoot.'/'.$j.'.js', CClientScript::POS_END); ?>
    <title><?=$this->pageTitle?> ›› <?=UserConfig::$websiteName?></title>
</head><body>
    <div id="header">
        <div class="center">
            <a href="/"><img src="/assets/logo.png" id="headerLogo"></a>
            <div id="headerRight">
                <div id="headerLogin">
                    <?php if($this->user):?>

                    <?php else:?>

                    <?php endif?>
                </div>
            </div>
        </div>
    </div>
    <div id="headerNav">

    </div>

    <?=$content?>
    <div id="footer">

    </div>
    <script type="text/javascript">
        var currentUserId = <?=$this->user?$this->user->id:0?>';
        $('[relHighlightKey="<?=$this->currentHighlightKey?>"]').addClass('sel');
        <?php $sessionTip = Yii::app()->user->getFlash('tip'); ?>
        <?php if($sessionTip):?>
            pop('<?=$sessionTip?>');
        <?php endif?>
    </script>
<?php UITools::echoLiveChat($this->user) ?>
</body></html>