<div id="tableTop">
    <div class="blackButton" popup="/admin/user/createNotifPopup/<?=$user->id?>">新增通知</div>
</div>
<table class="table">
    <tr>
        <th style="width:167px">通知時間</th>
        <th style="width:60px">已讀</th>
        <th>通知內容</th>
    </tr>
    <?php foreach($list as $l): ?>
        <tr>
            <td class="t"><?=$l->create_time?></td>
            <td class="t"><?=($l->has_read?'<span class="accepted">✓</span>':'')?></td>
            <td><?=$l->content?>
                <?php if($l->rel_link):?>
                    <br><a class="link" target="_blank" href="<?=$l->rel_link?>"><?=$l->rel_link?></a>
                <?php endif?>
            </td>
        </tr>
    <?php endforeach?>
</table>