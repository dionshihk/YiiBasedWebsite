<?php
    $magicFieldTag = '~#';
    $data = array(
        'Profile Picture' => '<img src="'.$user->avatar_url.'">',
        'User ID' => $user->id,
        'Nick Name' => $magicFieldTag.'nickname',
        'Is Male' => $magicFieldTag.'is_male',
        'Email' => $magicFieldTag.'email',
        'Account Status' => $magicFieldTag.'account_status',
        'Admin Status' => $user->admin_status,
        'Register At' => $user->reg_time,
        'Last Login At' => $user->last_login_time,
        'Bio' => Tools::autoAddLink($user->introduction),
        'Extra Remark' => $magicFieldTag.'extra_data',
        'Mobile Remark' => $magicFieldTag.'mobile_data',
    );

    $appTokens = MobileSessionToken::model()->findAll('user_id = '.$user->id);
    $deviceTokens = MobileNotificationDevice::model()->findAll('user_id = '.$user->id);
?>
<div class="infoBox">
    <?php foreach($data as $k=>$v):?>
    <div class="item">
        <span class="leftItem"><?=$k?></span>
        <span class="rightItem label">
            <?php if(strpos($v, $magicFieldTag) === 0):?>
                <?php $fieldName = substr($v, strlen($magicFieldTag)); ?>
                <?=$user[$fieldName]?> &nbsp;
                <?php if($this->canModify):?>
                    <a class="link" popup="/admin/user/updateFieldPopup?id=<?=$user->id?>&fieldName=<?=$fieldName?>">Edit</a>
                <?php endif?>
            <?php else:?>
                <?=$v?>
            <?php endif?>
        </span>
    </div>
    <?php endforeach?>

    <div class="item">
        <span class="leftItem">App Sign-In Tokens</span>
        <span class="rightItem label">
            <?php foreach($appTokens as $t):?>
                <?=$t->token?>: <i><?=$t->create_time?> to <?=$t->expire_time?></i><br>
            <?php endforeach?>
        </span>
    </div>

    <div class="item">
        <span class="leftItem">App Device Tokens</span>
        <span class="rightItem label">
            <?php foreach($deviceTokens as $t):?>
                <?=$t->token?>:<br>
                <?=$t->param?><br>
                <i><?=$t->reg_time?> <?=$t->is_debug ? ('(Debug)') : ''?></i><br>
            <?php endforeach?>
        </span>
    </div>

    <div class="item">
        <span class="leftItem">Links</span>
        <span class="rightItem label">
            <a class="link" target="_blank" href="/user/<?=$user->id?>">個人主頁</a><br>
            <a class="link" href="/admin/system/logging/<?=$user->id?>">操作日誌</a><br>
            <a class="link" href="/admin/user/notification/<?=$user->id?>">通知管理</a><br>
        </span>
    </div>
</div>