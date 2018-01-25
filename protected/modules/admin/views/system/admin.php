<div id="tableTop">
    <div class="blackButton ilb" onclick="addSystemAdmin()">新增管理員</div>
    <select onchange="reloadUriWithParam('type',vl(this))" style="margin:6px 0 0 9px" >
        <option value="0">全部種類</option>
        <?php foreach(UserConfig::$adminRoles as $k=>$v):?>
            <?php if($k <= 2):?>
                <option value="<?=$k?>" <?=$k==$type?'selected':''?>><?=$v?></option>
            <?php endif;?>
        <?php endforeach;?>
    </select>
    <input maxlength="100" placeholder="輸入 Nickname/ID/Email 搜尋" type="text" id="tableSearch"
           value="<?=(isset($_GET['keyword']) ? $_GET['keyword'] : '')?>">
</div>
<table class="table">
    <tr>
        <th>ID</th>
        <th style="width:95px">Nickname</th>
        <th>Email</th>
        <th style="width:250px">類型</th>
        <th style="width:188px">操作</th>
    </tr>
    <?php foreach($list as $l): ?>
        <tr>
            <td><?=$l->id?></td>
            <td><?=$l->nickname?></td>
            <td><a class="link" href="mailto:<?=$l->email?>"><?=$l->email?></a></td>
            <td><?=UserConfig::$adminRoles[$l->admin_status]?></td>
            <td>
                <a class="link" href="/admin/user/detail/<?=$l->id?>">詳情</a>
                <?php if($l->id != $this->user->id):?>
                    <a class="link" href="/admin/system/adminCreate?nickname=<?=$l->nickname?>">修改</a>
                    <a class="link" href="javascript:confirmThenReload('/admin/system/removeAdmin', <?=$l->id?>, '移除管理員')">移除</a>
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
        if(v) { window.location = '?keyword=' + v; }
    }
});

function addSystemAdmin()
{
    var t = prompt('請輸入新管理員 nickname', '');
    if(t)
    {
        pend();
        window.location = '/admin/system/adminCreate?nickname=' + t;
    }
}
</script>