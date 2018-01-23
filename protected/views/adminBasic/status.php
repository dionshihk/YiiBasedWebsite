<div class="simpleBox" id="statusInfoBox">
    <h3>Server IP：<b><?=$_SERVER['SERVER_ADDR']?></b></h3>
    <h3>Server Base URL：<b><?=UserConfig::$websiteUrl?></b></h3>
    <h3>Server System：<b><?=php_uname()?></b></h3>
    <h3>Server Time：<b><?=Tools::now()?></b></h3>

    <br>
    <h3>Root Folder：<b><?=Tools::getFolderSize(Tools::getAbsUrl('/'))?></b></h3>
    <h3>- Static Resource：<b><?=Tools::getFolderSize(Tools::getAbsUrl(UserConfig::$staticFileRoot))?></b><br>
        - By Admin: <b><?=Tools::getFolderSize(Tools::getAbsUrl('/uploadAdminBasic'))?></b><br>
    </h3>

    <br>
    <h3>PHP Version：<b><?=PHP_VERSION?></b></h3>
    <h3>PHP File：<b><?=php_ini_loaded_file()?></b></h3>
    <h3>PHP Framework: <b>Yii <?=Yii::getVersion()?></b></h3>
    <h3>MySQL Connection：<b><?=Yii::app()->db->connectionString?></b></h3>
    <h3>SAPI Interface：<b><?=php_sapi_name()?></b></h3>
    <h3>HTTP Server：<b><?=$_SERVER['SERVER_SOFTWARE']?></b></h3>
</div>