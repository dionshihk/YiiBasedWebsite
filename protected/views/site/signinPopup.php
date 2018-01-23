<form id="loginForm" method="post">
    <p><?=$this->t('index.9')?> ››</p>
    <input placeholder="<?=$this->t('index.10')?>" name="LoginForm[name]" autocomplete="off" class="sys" id="sysLoginName" type="text">
    <p><?=$this->t('account.i22')?> ››</p>
    <input name="LoginForm[password]" placeholder="<?=$this->t('index.11')?>" autocomplete="off" id="sysLoginPass" type="password" class="sys">
    <label id="rememberLabel"><input type="checkbox" name="LoginForm[remember]"><?=$this->t('index.12')?></label>
    <div class="blackButton" onclick="sysLogin()"><?=$this->t('nav.7')?></div>
    <p><?=$this->t('index.13')?> <a class="link" href="/page/membership"><?=$this->t('index.14')?></a></p>
</form>
<script type="text/javascript">
function sysLogin()
{
	var $n=$('#sysLoginName'),$p=$('#sysLoginPass');
	var n=vl($n),p=vl($p);
	if(!$.trim(n)) {$n.focus();}
	else if(!p) {$p.focus();}
	else {
		$.get('/site/ajaxLogin?name='+n+'&pass='+p,function(d){
			if(d!='0') { $('#loginForm').attr('action','/site/index?returnUrl=' + uri).submit(); }
			else { pop('<?=$this->t('index.15')?>'); }
		});
	}
}
$('#sysLoginPass').keyup(function(e){if(e.keyCode==13)sysLogin()});
</script>