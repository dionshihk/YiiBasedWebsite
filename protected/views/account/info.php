<form method="post" id="infoForm" enctype="multipart/form-data">
    <div class="formRow" style="margin-bottom:24px">
        <span class="left"><?=$this->t('account.i1')?>: </span>
        <span class="right">
            <img style="border:1px solid #ccc;display:block;margin-bottom:5px" src="<?=$this->user->avatar_url?>">
            <input type="file" id="avatarFile" name="avatar">
            <p><?=$this->t('account.7')?></p>
        </span>
    </div>

    <?php $this->renderPartial('//common/infoCore', array('user'=>$this->user));?>

    <div class="formRow">
        <span class="left"><?=$this->t('account.i16')?>: </span>
            <span class="right">
                <input maxlength="35" type="text" class="sys" value="<?=$this->user->current_city?>" name="DataForm[current_city]">
            </span>
    </div>
    <div class="formRow">
        <span class="left"><?=$this->t('account.i17')?>: </span>
            <span class="right">
                <input maxlength="75" type="text" class="sys" value="<?=$this->user->address?>" name="DataForm[address]">
            </span>
    </div>
    <div class="formRow">
        <span class="left"><?=$this->t('account.i18')?>: </span>
        <span class="right">
            <?=UserConfig::$facebookUrlPrefix?>
            <input maxlength="40" type="text" class="sys" style="width:110px"
                value="<?=$this->user->facebook_account?>" name="DataForm[facebook_account]">
            <p><?=$this->t('account.21')?></p>
        </span>
    </div>

    <div class="formRow">
        <span class="left"></span>
        <span class="right"><div class="blackButton" onclick="submitInfo()"><?=$this->t('update')?></div></span>
    </div>
</form>
<script type="text/javascript">
function submitInfo()
{
    performBasicInfoCheck(function(){
        $('#infoForm').submit();
    });
}
</script>
