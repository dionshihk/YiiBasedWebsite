<div class="accountNotifyBox">
    To earn more credits, invite more people to join us, with your ID
    <b id="accountSponsorLabel"><?=$this->user->uniq_id?></b>
    <a href="" target="_blank">Click here</a> to learn more the credits
</div>
<?php if(count($list) > 0):?>
    <div id="invitedMemberBox">
    <?php foreach($list as $l):?>
        <div class="item ilb" onclick="window.location=('/common/userProfile/<?=$l->id?>')">
            <img src="<?=$l->avatar_url?>">
            @<?=$l->nickname?>
        </div>
    <?php endforeach?>
    </div>
<?php else:?>
    <div id="noProductBox">No Invited Members Yet ...</div>
<?php endif?>