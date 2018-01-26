<!DOCTYPE html>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=0.8">
    <link href="/favicon.ico" rel="shortcut icon">
    <?php foreach($this->css as $c):?>
        <link rel="stylesheet" type="text/css" href="<?=UserConfig::$staticFileRoot?>/<?=$c?>.css"/>
    <?php endforeach ?>

    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/js/jquery.1.11.min.js"></script>
    <title>Administration ›› <?=UserConfig::$websiteName?></title>
</head>
<body>
<div id="bodyLeft">
    <div id="mobileExpander" onclick="$('#bodyLeftMainMenu').slideToggle()"><i class="fa fa-list fa-lg"></i><?=UserConfig::$websiteName?> Menu</div>
    <div id="bodyLeftMainMenu">
        <a class="title" href="/"><?=UserConfig::$websiteName?></a>
        <?php foreach($this->moduleList as $k=>$v):?>
            <?php if(!$this->can($k)) continue;  ?>
            <div class="l0" relModule="<?=$k?>">
                <div class="l1">›› <?=$v[0]?></div>
                <?php foreach($v[1] as $k1=>$v1):?>
                    <a href="/admin/<?=$k?>/<?=str_replace('*', '', $k1)?>" class="l2"><?=$v1?></a>
                <?php endforeach?>
            </div>
        <?php endforeach?>
    </div>
</div>
<div id="bodyRight">
    <div id="rightHeader">
        <h1><?=$this->pageTitle?></h1>
        <h2><?=$this->pageIntroduction ? $this->pageIntroduction : ''?></h2>
    </div>
    <div id="adminCore">
        <?php $sessionTip = Yii::app()->user->getFlash('tip'); ?>
        <?php if($sessionTip):?>
            <div class="tipBox"><i class="fa fa-info-circle"></i><?=$sessionTip?></div>
        <?php endif?>
        <?php echo $content; ?>
    </div>

</div>
<div id="goToTopLabel" style="opacity:1" onclick="scrollToTop()"><i class="fa fa-lg fa-fw fa-chevron-up"></i></div>

<script type="text/javascript">
    var isAdminMode = true, currentUserId = <?=$this->user->id?>;
</script>
<?php foreach($this->js as $j):?>
    <script type="text/javascript" src="<?=UserConfig::$staticFileRoot?>/<?=$j?>.js"></script>
<?php endforeach?>
</body>
</html>