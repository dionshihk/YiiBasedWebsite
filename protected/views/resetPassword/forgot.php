<div class="center" id="pageContentBox">
    <div class="content">
        <form method="post" id="forgotForm" style="margin:20px 10px">
            <div class="formRow">
                <span class="left"><?=$this->t('email')?>: </span>
                <span class="right">
                    <input maxlength="35" name="email" id="emailInput" value="<?=$email?>" class="sys" type="text">
                    <p class="tip"><?=$this->t('account.5')?></p>
                </span>
            </div>
            <div class="formRow" style="margin-top:22px">
                <span class="left"></span>
                <span class="right">
                    <div class="blackButton" onclick="submitResetPasswordRequest()"><?=$this->t('submit')?></div>
                </span>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function submitResetPasswordRequest()
    {
        clearInputTip($('#forgotForm'));

        if(!mailRegex.test(vl('#emailInput'))) { addInputTip($('#emailInput'), '<?=$this->t('account.6')?>'); }
        else { pend(); $('#forgotForm').submit(); }
    }
</script>