<?php if(count($list) > 0):?>
    <div id="notifBox">
        <?php foreach($list as $l):?>
            <div class="item" onclick="<?=$l->rel_link?'window.open(\''.$l->rel_link.'\')':''?>">
                <?=NotificationTools::renderNotification($l->content)?>
                <i><?=$l->create_time?></i>
            </div>
        <?php endforeach?>
        <?php UITools::echoProductPaging($page, $total, $pageSize); ?>
    </div>
<?php else:?>
    <div id="noProductBox"><?=$this->t('account.n3')?></div>
<?php endif?>