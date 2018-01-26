<div class="center" id="pageContentBox">
    <div class="content">
        <form method="post" id="resetForm" style="margin:20px 10px">
            <div class="formRow">
                <span class="left"><?=$this->t('email')?>: </span>
                <span class="right">
                    <input disabled readonly value="<?=$user->email?>" class="sys disable" type="text">
                </span>
            </div>
            <div class="formRow">
                <span class="left"><?=$this->t('password')?>: </span>
                <span class="right">
                    <input maxlength="16" name="password" class="sys" type="password" id="p1Input">
                </span>
            </div>
            <div class="formRow">
                <span class="left"><?=$this->t('confirmPassword')?>: </span>
                <span class="right">
                    <input maxlength="16" class="sys" type="password" id="p2Input">
                    <p class="tip"><?=$this->t('account.12')?></p>
                </span>
            </div>
            <div class="formRow">
                <span class="left"></span>
                <span class="right">
                    <div class="blackButton" onclick="goResetPassword()"><?=$this->t('submit')?></div>
                </span>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function goResetPassword()
{
    clearInputTip($('#resetForm'));

    var p1 = vl('#p1Input'), p2 = vl('#p2Input'), $p1 = $('#p1Input'), $p2 = $('#p2Input');
    if(!p1) { $p1.focus(); }
    else if(!p2) { $p2.focus(); }
    else if(p1 != p2) { addInputTip($p2, '<?=$this->t('account.14')?>'); }
    else if(p1.length < 5) { addInputTip($p1, '<?=$this->t('account.15')?>'); }
    else {
        pend();
        $('#resetForm').submit();
    }
}
</script>