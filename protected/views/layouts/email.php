<div style="font-family:'Helvetica Neue',Helvetica,'Microsoft YaHei','Hiragino Kaku Gothic Pro','Hiragino Sans GB',Sans-serif;font-size:14px;">

    <div style="">

        <?php if($this->name):?>
            <p>Dear <?=$this->name?></p>
        <?php endif?>

        <?php echo $content; ?>
        <p style="margin-top:18px">Best Regards</p>
    </div>

</div>

