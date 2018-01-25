<form method="post" id="codeForm">
    <div class="infoBox">
        <textarea id="codeInput" style="height:500px" name="code"><?=$code?></textarea>
        <input type="hidden" name="last_version" value="<?=$lmt?>">
        <br><div class="blackButton" onclick="preventPageLeave(0);$('#codeForm').submit();">保存</div>
    </div>
</form>
<script type="text/javascript">
<?php if(!Tools::isMobileDevice()):?>
    $(function () {
        var editorRef = CodeMirror.fromTextArea(
            document.getElementById('codeInput'),
            {
                theme: 'bespin',
                mode: 'text/x-php',
                styleActiveLine: true,
                lineNumbers: true
            }
        );

        editorRef.on('change', function () { preventPageLeave(1, 1); });
    });
<?php endif?>

$('#phpFileSelect').val('<?=$relValue?>').change(function(){
    pend();
    window.location = '?ext=' + vl(this);
});
</script>