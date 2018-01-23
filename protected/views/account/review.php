<?php foreach($toBeDone as $p):?>
    <div class="accountNotifyBox">
        <?=$this->t('account.2')?>
        <a href="/product/<?=$p->id?>" target="_blank"><?=DbTools::getDbContentInLanguage($p, 'name')?></a>
        <br>
        <a href="/product/writeReview/<?=$p->id?>"><?=$this->t('account.3')?> ››</a>
    </div>
<?php endforeach?>

<div class="blackButton" style="width:120px;margin-bottom:20px" onclick="window.location='/product/writeIndependentReview'"><?=$this->t('account.4')?></div>
<?php if(count($list) > 0):?>
    <div id="accountReviewBox">
        <?php foreach($list as $l):?>
            <div class="item">
                <h3><?=$l->title?></h3>
                <p><?=$l->create_time?></p>
                <p><?=DbTools::getModelStatus($l)?></p>
                <?php if($l->status == 2):?>
                    <div class="blackButton" onclick="window.open('/review/<?=$l->id?>')"><?=$this->t('view')?></div>
                <?php
                    else:
                        $linkUrl = ($l->product_id ?
                            '/product/writeReview/'.$l->product_id :
                            '/product/writeIndependentReview/'.$l->id);
                ?>
                    <div class="blackButton" onclick="location.href='<?=$linkUrl?>'"><?=$this->t('modify')?></div>
                <?php endif?>
            </div>
        <?php endforeach?>
    </div>
<?php else:?>
    <div id="noProductBox"><?=$this->t('account.n4')?></div>
<?php endif?>