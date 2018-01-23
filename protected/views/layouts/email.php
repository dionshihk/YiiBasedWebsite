<div style="font-family:'Helvetica Neue',Helvetica,'Microsoft YaHei','Hiragino Kaku Gothic Pro','Hiragino Sans GB',Sans-serif;font-size:14px;margin:9px;">
    <div style="background:#425866;padding:12px 17px;">
        <a href="<?=UserConfig::$websiteUrl?>">
            <img style="height:60px;" src="<?=UserConfig::$websiteUrl?>/assets/logo.png">
        </a>
    </div>
    <div style="border:1px solid #425866;line-height:15px;padding:7px 17px;">
        <p style="padding-bottom:8px">Hi, <?=$this->name?>,</p>

        <?php echo $content; ?>
        <p style="margin-top:18px">Best Regards, ...</p>
    </div>

</div>

