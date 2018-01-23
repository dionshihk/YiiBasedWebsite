<?php if(count($list) > 0):?>
    <div id="accountBookingList">
        <?php
            foreach($list as $l):
                $p = Product::model()->findByPk($l->product_id);
        ?>
            <div class="item <?=DbTools::isOldProduct($p) ? 'old' : ''?>"
                style="background-image: url(<?=$p->cover_url?>);">
                <h2><?=DbTools::getDbContentInLanguage($p, 'name')?></h2>
                <p><?=Tools::beautifyDate($p->from_date).' - '.Tools::beautifyDate($p->end_date)?></p>
                <a class="link" href="/product/<?=$l->product_id?>"><?=$this->t('view')?></a>
                <div class="grey">
                    <?=$this->t('account.1').': '.$l->join_time?><br>
                    <?=DbTools::getModelStatus($l)?>
                </div>
            </div>
        <?php endforeach?>
    </div>
<?php else:?>
    <div id="noProductBox"><?=$this->t('account.n2')?></div>
<?php endif?>