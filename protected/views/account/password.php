<form method="post" id="passwordForm">
    <div class="formRow">
        <span class="left"><?=$this->t('account.i19')?>: </span>
        <span class="right"><input maxlength="16" type="password" class="sys" id="p1Input" name="DataForm[p1]"></span>
    </div>
    <div class="formRow">
        <span class="left"><?=$this->t('account.i20')?>: </span>
        <span class="right"><input maxlength="16" type="password" class="sys" id="p2Input" name="DataForm[p2]"></span>
    </div>
    <div class="formRow">
        <span class="left"><?=$this->t('account.i21')?>: </span>
        <span class="right"><input maxlength="16" type="password" class="sys" id="p3Input"></span>
    </div>
    <div class="formRow">
        <span class="left"></span>
        <span class="right"><div class="blackButton" onclick="submitPassword()"><?=$this->t('update')?></div></span>
    </div>
</form>
<script type="text/javascript">
function submitPassword()
{
    var v1 = $('#p1Input').val(), v2 = $('#p2Input').val(), v3 = $('#p3Input').val();

    if(!v1 || !v2 || !v3) { pop('<?=$this->t('account.22')?>'); }
    else if(v2.length < 5) { pop('<?=$this->t('account.23')?>'); }
    else if(v2 != v3) { pop('<?=$this->t('account.24')?>'); }
    else { pend(); $('#passwordForm').submit(); }
}
</script>
