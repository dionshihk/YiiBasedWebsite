<?php if(count($list) > 0):?>
    <div id="accountReviewBox">
        <?php foreach($list as $l):?>
            <div class="item">
                <h3><?=DbTools::getDbContentInLanguage($l, 'title')?></h3>
                <p><?=$l->create_time?></p>
                <p><?=DbTools::getModelStatus($l)?></p>
                <?php if($l->status == 2):?>
                    <div class="blackButton" onclick="window.open('/competition/<?=$l->id?>')"><?=$this->t('view')?></div>
                <?php else:?>
                    <div class="blackButton" onclick="location.href='/competition/edit/<?=$l->id?>'"><?=$this->t('modify')?></div>
                <?php endif?>
            </div>
        <?php endforeach?>
    </div>
<?php else:?>
    <div id="noProductBox"><?=$this->t('account.n1')?></div>
<?php endif?>