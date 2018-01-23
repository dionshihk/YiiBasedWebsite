<div id="creditDetailBox">
    <?php foreach($list as $l):?>
        <div class="item">
            <?=CreditTools::renderCreditChange($l->content)?>
            <p class="time"><?=$l->change_time?></p>
            <div class="score"><?=$l->change_value>0?'+'.$l->change_value:$l->change_value?></div>
        </div>
    <?php endforeach?>
</div>