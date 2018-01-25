<div id="tableTop">
    <input maxlength="100" placeholder="輸入 Nickname/Name/ID/Email 搜尋" type="text" id="tableSearch"
        value="<?=(isset($_GET['keyword']) ? $_GET['keyword'] : '')?>">
</div>
<table class="table">
    <tr>
        <th class="hiddenForMobile">ID</th>
        <th>Nickname</th>
        <th>Email</th>

        <th class="hiddenForMobile" style="width:166px">註冊時間</th>
        <th style="width:200px">操作</th>
    </tr>
    <?php foreach($list as $l): ?>
        <?php
            $enableAction = ($l->account_status == 1 ? '封禁' : ($l->account_status == 2 ? '解封' : null));
        ?>
        <tr>
            <td class="hiddenForMobile"><?=$l->id?></td>
            <td><?=$l->nickname?></td>
            <td><a class="link" href="mailto:<?=$l->email?>"><?=$l->email?></a></td>

            <td class="t hiddenForMobile"><?=$l->join_time?></td>
            <td>
                <a class="link" href="/admin/user/detail/<?=$l->id?>">詳細資料</a>
                <a class="link" href="javascript:resetPassword(<?=$l->id?>)">重設密碼</a>

                <?php if($enableAction):?>
                    <a class="link" href="javascript:confirmThenReload('/admin/user/toggleEnable',<?=$l->id?>,'<?=$enableAction?>')"><?=$enableAction?></a>
                <?php endif?>
            </td>
        </tr>
    <?php endforeach?>
</table>
<?php UITools::echoPaging($page, $total, $pageSize); ?>
<script type="text/javascript">
    $('#tableSearch').keyup(function(e){
        if(e.keyCode == 13) {
            var v = vl(this);
            if(v) { window.location = '/admin/user/search?keyword=' + v; }
        }
    });
</script>