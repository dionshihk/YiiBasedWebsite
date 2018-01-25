<br><br>
<table class="table large">
    <tr>
        <th style="width:200px">後台信息</th>
        <th></th>
    </tr>
    <tr>
        <td>當前登入用戶</td>
        <td><?php $this->echoUserLink($this->user)?></td>
    </tr>
    <tr>
        <td>當前登入角色</td>
        <td> <?=UserConfig::$adminRoles[$this->user->admin_status]?></td>
    </tr>
    <tr>
        <td>Server 時間</td>
        <td> <?=Tools::now()?></td>
    </tr>
</table>