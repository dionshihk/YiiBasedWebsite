<!DOCTYPE html>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if lte IE 8]><script>alert("For better performance, please upgrade your browser.");</script><![endif]-->
    <?php foreach($this->css as $c):?>
        <link rel="stylesheet" type="text/css" href="<?=UserConfig::$staticFileRoot?>/<?=$c?>.css"/>
    <?php endforeach ?>
    <link href="/favicon.ico" rel="shortcut icon">
    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/js/core.js"></script>
    <?php foreach($this->js as $j) Yii::app()->clientScript->registerScriptFile(UserConfig::$staticFileRoot.'/'.$j.'.js', CClientScript::POS_END); ?>
    <title>Administration Panel</title>
</head>
<body>
<div id="bodyLeft">
    <img id="adminLogo" src="/assets/logo.png">
    <?php foreach($this->moduleList as $k=>$v):?>
        <div class="l0">
            <div link="/admin<?=$k?>" class="l1">›› <?=$v[0]?></div>
            <?php foreach($v[1] as $k1=>$v1):?>
                <?php if(strpos($k1, '*') !== 0 || $this->canModify):?>
                    <div link="/admin<?=$k?>/<?=str_replace('*', '', $k1)?>" class="l2"><?=$v1?></div>
                <?php endif?>
            <?php endforeach?>
        </div>
    <?php endforeach?>
</div>
<div id="bodyRight">
    <div id="rightHeader">
        <h1><?=$this->pageTitle?></h1>
        <h2><?=$this->pageIntroduction ? $this->pageIntroduction : ''?></h2>
        <strong onclick="window.location='/'">Main Site ››</strong>
    </div>
    <div id="adminCore">
        <?php $sessionTip = Yii::app()->user->getFlash('tip'); ?>
        <?php if($sessionTip):?>
            <div class="tipBox"> &nbsp; <?=$sessionTip?></div>
        <?php endif?>
        <?php echo $content; ?>
    </div>
</div>
<script type="text/javascript">
    <?php UITools::echoJsTextOfI18n(); ?>
</script>
</body>
</html>