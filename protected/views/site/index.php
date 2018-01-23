<div class="slider" id="indexBigSlider">
    <ul class="container">
        <?php foreach(EditableConfig::$indexBigProducts as $k=>$v):?>
            <li onclick="window.location='/product/<?=$k?>'" style="background-image:url('<?=$v[0]?>')">
                <div class="box">
                    <h2><?=$this->t($v[1])?></h2>
                    <?=$this->t($v[2])?>
                    <div class="blackButton" loginUrl="/product/<?=$k?>"><?=$this->t('index.5')?></div>
                </div>
            </li>
        <?php endforeach?>
    </ul>
    <div class="pager"></div>
</div><br>
<div class="center bigTitleBar"><?=$this->t('index.3')?></div>
<div id="indexPlaceIntroBox" class="center">
    <?php foreach(EditableConfig::$indexFeaturePlaces as $k=>$v):?><div class="ilb"
        onclick="window.location='/product/list?location=<?=LocationTools::getLocationName($k)?>'">
            <img src="<?=$v?>"><p><?=LocationTools::getLocationInShort($k)?></p>
        </div><?php endforeach?>
</div>
<div class="indexDarkWideBox">
    <h1><?=$this->t('index.4')?></h1>
    <div id="indexFeatProductBox">
        <div class="lastCall">
            <div class="call"><?=$this->t('index.31')?></div>
            <?php if($showCountdown):?>
                <div id="indexCountDownBox" countdownSec="<?=$countdownSec?>">
                    <span>-</span><span>-</span>:<span>-</span><span>-</span>:<span>-</span><span>-</span>
                </div>
            <?php endif?>
            <a href="/product/<?=$lastCallProduct->id?>"><img src="<?=$lastCallProduct->cover_url?>"></a>
            <div class="label">
                <h3><?=DbTools::getDbContentInLanguage($lastCallProduct, 'name')?></h3>
                <p><?=Tools::beautifyDate($lastCallProduct->from_date).' - '.Tools::beautifyDate($lastCallProduct->end_date)?></p>
            </div>
            <div class="desc"><?=strip_tags(DbTools::getDbContentInLanguage($lastCallProduct, 'description'))?></div>
            <a class="blackButton" href="/product/<?=$lastCallProduct->id?>"><?=$this->t('index.7')?></a>
        </div>
        <?php
            foreach(EditableConfig::$indexFeatureProductIds as $featId):
                if(isset($caches[$featId])): $p = $caches[$featId]; ?><div class="featPrdBox ilb">
            <a href="/product/<?=$p->id?>"><img src="<?=$p->cover_url?>"></a>
            <div class="label">
                <h3><?=DbTools::getDbContentInLanguage($p, 'name')?></h3>
                <p><?=Tools::beautifyDate($p->from_date).' - '.Tools::beautifyDate($p->end_date)?></p>
            </div>
            <a class="blackButton" href="/product/<?=$p->id?>"><?=$this->t('index.7')?></a>
            </div><?php endif; endforeach?>
    </div>
    <div class="center bigTitleBar righterBar white" onclick="window.location='/product/list'"><?=$this->t('index.8')?> ››</div><br>
</div>

<div class="center bigTitleBar"><?=$this->t('index.32')?></div>
<div id="indexReviewBox" class="center">
    <?php foreach($reviews as $r):?><div onclick="window.location='/review/<?=$r->id?>';return false"
        src="<?=$r->thumb_url?>" class="item ilb">
        <a class="dark" href="/review/<?=$r->id?>"><?=DbTools::getDbContentInLanguage($r, 'title')?></a>
    </div><?php endforeach?>
</div>
<div class="center bigTitleBar righterBar" onclick="window.location='/review'"><?=$this->t('index.8')?> ››</div><br>
<img src="/assets/v1/indexGlobalMap2.jpg" style="display: block;width:100%;margin-bottom:-41px">