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
    <div id="header">
        <img src="/assets/image/logo.png">
    </div>

    <?=$content?>

    <div id="footer">

    </div>

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