<div id="tableTop">
    <input maxlength="20" placeholder="輸入關鍵字搜尋" type="text" id="tableSearch"
        value="<?=(isset($_GET['keyword']) ? $_GET['keyword'] : '')?>">
</div>
<table class="table">
    <tr>
        <th style="width:120px">Operator</th>
        <th style="width:148px">Time</th>
        <th>Logging</th>
    </tr>
    <?php $lastItem = null; foreach($list as $l) {?>
        <tr class="admLog">
            <?php if($lastItem && $l->user_id && $lastItem->user_id == $l->user_id && $lastItem->gets == $l->gets):?>
                <td></td>
            <?php elseif($l->user_id):?>
                <td tipText="<?=$l->ip?> <?=$l->ua?>"><a class="link" href="/admin/user/detail/<?=$l->user_id?>"><?=$l->name?></a></td>
            <?php else:?>
                <td style="color:#bbb" tipText="<?=$l->ua?>"><?=$l->ip?></td>
            <?php endif?>
            <td><?=$l->time?></td>
            <td tipText="<?=str_replace('"', '', $l->gets)?>"><?=($l->log)?></td>
        </tr>
    <?php $lastItem = $l; }?>
</table>
<?php UITools::echoPaging($page, $total, $pageSize); ?>
<script type="text/javascript">
    $('#tableSearch').keyup(function(e){
        if(e.keyCode == 13) {
            var v = vl(this);
            if(v) { window.location = '?id=<?=$userId?>&keyword=' + v; }
        }
    });
</script>