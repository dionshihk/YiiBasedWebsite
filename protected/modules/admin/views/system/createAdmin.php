<?php
    //Format: [ {module} ... ]

    $adminAccessParamObject = json_decode($user->admin_access_param, true);
    if(!$adminAccessParamObject) $adminAccessParamObject = [];
?>

<div class="infoBox">
    <div class="item">
        <span class="leftItem">管理員類別</span>
        <span class="rightItem">
            <select id="adminTypeSelect">
                <?php foreach(UserConfig::$adminRoles as $k=>$v):?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach;?>
            </select>
        </span>
    </div>
    <div class="item" id="regularAdminItem">
        <span class="leftItem">可操作权限</span>
        <span class="rightItem" id="authCheckBox">
             <?php foreach($this->moduleList as $k=>$v):?>
                 <?php
                 if($k == 'Basic' || $k == 'System') continue;
                 ?>
                 <div class="item" relAuth="<?=$k?>">
                     <label class="ilb"><input class="chk" type="checkbox" onchange="updateModuleOption()"><?=$v[0]?></label>
                     <div class="ilb option">

                     </div>
                 </div>
             <?php endforeach?>
                <br>
        </span>
    </div>
    <div class="item" style="margin-top:19px">
        <span class="leftItem"></span>
        <span class="rightItem"><div class="blackButton" onclick="submitCreateAdmin()">保存</div></span>
    </div>
</div>

<script type="text/javascript">
<?php foreach($adminAccessParamObject as $item):?>
    $('[relAuth="<?=$item['module']?>"] .chk').attr('checked', 'checked');
<?php endforeach?>

function updateModuleOption()
{
    $('#authCheckBox .chk').each(function(){
        if($(this).is(":checked")) { $(this).parent().siblings('.option').show(); }
        else { $(this).parent().siblings('.option').hide(); }
    })
}

$(function(){
    $('#adminTypeSelect').change(function(){
        if(vl(this) == '2') { $('#regularAdminItem').show(); updateModuleOption(); }
        else { $('#regularAdminItem').hide(); }
    }).val('<?=$user->admin_status?$user->admin_status:1?>').change();
});
</script>