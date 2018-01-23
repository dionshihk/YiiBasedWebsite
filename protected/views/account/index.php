<div style="font-size:15px;line-height:27px;color:#777;text-align: center">
    <img src="<?=$this->user->avatar_url?>" style="border:1px solid #ccc;border-radius:200px;margin-bottom:20px">
    <div style="margin:3px auto;">
        <p><?=$this->t('account.i2')?>: <b><?=$this->user->nickname?></b></p>
        <p><?=$this->t('maskpoint')?>: <b><?=$this->user->score?></b></p>
        <?php if($this->user->score > 0):?>
            <p><a style="font-size: 12px" class="link" popup="/account/creditDetailPopup"><?=$this->t('account.5')?></a></p>
        <?php endif?>
    </div>

    <?php if($inviter):?>
        <p><?=$this->t('account.6')?>:
            <a class="link" href="/common/userProfile/<?=$inviter->id?>"><?=$inviter->nickname?></a></p>
    <?php endif?>
</div>
