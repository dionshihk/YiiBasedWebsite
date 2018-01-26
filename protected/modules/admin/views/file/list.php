<div style="margin:18px" id="uploadImgListBox">
    <?php foreach($list as $l=>$time):?>
        <?php
            $url = $urlPrefix.$l;
            $fileCreateTime = date("Y-m-d H:i", filemtime(Tools::getAbsUrl($url)));
        ?>
        <div class="ilb" tipText="<?=$fileCreateTime?>">
            <?php if(ImageTools::isImageExtension($url)):?>
                <div class="img file bgImg" style="background-image:url('<?=$url?>')"
                     onclick="window.open('<?=$url?>')"></div>
            <?php else:?>
                <div class="other file" onclick="window.open('<?=$url?>')">
                    <b><?=$l?></b>Click To Download
                </div>
            <?php endif?>
            <input readonly="readonly" value="<?=$url?>">
            <a class="link" href="javascript:deleteAdminFile('<?=$l?>')">Delete</a>
        </div>
    <?php endforeach?>

</div>
<?php UITools::echoPaging($page, $total, $pageSize); ?>
<script type="text/javascript">
    function deleteAdminFile(name)
    {
        if(confirm('Are you sure to delete the file:\n' + name +
                '\n\nOnce deleted, the file cannot be recovered and all referred links will become invalid.'))
        {
            pend();
            $.get('/admin/file/ajaxDeleteFile', {name:name}, function() { location.reload(); });
        }
    }
    $('#uploadImgListBox input').focus(function(){
       $(this).select();
    });
</script>
