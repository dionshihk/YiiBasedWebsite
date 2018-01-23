<div class="simpleBox">
    <h1>請於左側列表選擇要進行的操作</h1>

    <h3>註冊會員总数：<a href="/adminUser/list" class="link"><?=User::model()->count('')?></a></h3>
    <h3>旅遊产品总数：<a href="/adminProduct/list" class="link"><?=Product::model()->count('')?></a></h3>
    <h3>網站文章总数：<a href="/adminProduct/review" class="link"><?=ProductReview::model()->count('')?></a>
        <?php $pendingReview = ProductReview::model()->count('status = 1'); ?>
        <?php if($pendingReview > 0):?>
            &nbsp; <b style="color: #f22"><?=$pendingReview?> 項待審查</b>
        <?php endif?>
    </h3>

    <h3>報名預訂总数：<a href="/adminProduct/join" class="link"><?=ProductJoin::model()->count('')?></a>
        <?php $pendingJoin = ProductJoin::model()->count('status = 1'); ?>
        <?php if($pendingJoin > 0):?>
        &nbsp; <b style="color: #f22"><?=$pendingJoin?> 項待處理</b>
        <?php endif?>
    </h3>

    <?php
        $currentCompetition = CompetitionTools::getLatest();
        if($currentCompetition):
            $currentCompetitionId = CompetitionTools::getLatest()->id;
    ?>
    <h3>當期參賽总数：<a href="/adminCompetition/workList" class="link"><?=CompetitionWork::model()->count('competition_id = '.$currentCompetitionId)?></a>
        <?php $pendingReview = CompetitionWork::model()->count('competition_id = '.$currentCompetitionId.' AND status = 1'); ?>
        <?php if($pendingReview > 0):?>
            &nbsp; <b style="color: #f22"><?=$pendingReview?> 項待審查</b>
        <?php endif?>
    </h3>
    <?php endif?>

    <h3 style="display: none">會員提款总数：<a href="/adminUser/withdraw" class="link"><?=UserWithdraw::model()->count('')?></a>
        <?php $toWithdrawCount = UserWithdraw::model()->count('status = 1'); ?>
        <?php if($toWithdrawCount > 0):?>
            &nbsp; <b style="color: #f22"><?=$toWithdrawCount?> 項待處理</b>
        <?php endif?>
    </h3>
    <br>
</div>