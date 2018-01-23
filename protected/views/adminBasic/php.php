<?php if($type == 2):?>
    <div id="tableTop" style="margin:15px 18px -10px">
        文件選擇:
        <select id="phpFileSelect">
            <?php foreach(UserConfig::$langFiles as $k=>$file):?>
                <option value="<?=$k?>"><?=$file?></option>
            <?php endforeach?>
        </select>
    </div>
<?php endif?>
<form method="post" id="codeForm">
    <div class="infoBox">
        <textarea id="codeInput" name="code"><?=$code?></textarea>
        <div class="blackButton" onclick="submitCode()">保存修改</div>
    </div>
</form>
<script type="text/javascript">
$('#phpFileSelect').val('<?=$relValue?>').change(function(){
    pend();
    window.location = '?ext=' + vl(this);
});
</script>