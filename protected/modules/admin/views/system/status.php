<div class="simpleBox">
    <h3>Server IP：<b><?=$_SERVER['SERVER_ADDR']?></b></h3>
    <h3>Server System：<b><?=php_uname()?></b></h3>
    <h3>Server Time：<b><?=Tools::now()?></b></h3>
    <h3>HTTP Server：<b><?=$_SERVER['SERVER_SOFTWARE']?></b></h3>

    <br>
    <h3>Root Folder：<b><?=Tools::getFolderSize(Tools::getAbsUrl('/'))?></b></h3>
    <h3>
        - User Upload: <b><?=Tools::getFolderSize(Tools::getAbsUrl('/upload/'))?></b><br>
        - Admin Upload: <b><?=Tools::getFolderSize(Tools::getAbsUrl(UserConfig::$adminUploadFolder))?></b><br>
    </h3>

    <br>
    <h3>PHP Version：<b><?=PHP_VERSION?></b></h3>
    <h3>PHP Config File：<b><?=php_ini_loaded_file()?></b></h3>
    <h3>PHP Framework: <b>Yii <?=Yii::getVersion()?></b></h3>
    <h3>PHP Upload Limit: <b><?=ini_get("upload_max_filesize")?></b></h3>
    <h3>PHP Loaded Extensions:</h3>
    <h3>
        <?php foreach(get_loaded_extensions() as $k):?>
            <b><?=$k?></b> /
        <?php endforeach;?>
    </h3>

    <br>
    <h3>MySQL Connection：<b><?=Yii::app()->db->connectionString?></b></h3>
    <h3>MySQL Info：<b><?=Yii::app()->db->getServerInfo()?></b></h3>
    <h3>MySQL Version：<b><?=Yii::app()->db->getServerVersion()?></b></h3>
</div>