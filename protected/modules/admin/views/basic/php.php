<form method="post" id="codeForm">
    <div class="infoBox">
        <textarea id="codeInput" onchange="preventPageLeave(1)" name="code"><?=$code?></textarea>
        <input type="hidden" name="last_version" value="<?=$lmt?>">
        <br><div class="blackButton" onclick="pend();preventPageLeave(0);$('#codeForm').submit();">Save</div>
    </div>
</form>