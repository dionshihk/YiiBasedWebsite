<form id="loginForm" method="post">
    <p><?=$this->t('email')?> ››</p>
    <input name="LoginForm[name]" class="sys" id="sysLoginName" type="text">
    <div style="position:relative">
        <p><?=$this->t('password')?> ››</p>
        <input name="LoginForm[password]" onkeyup="if(event.keyCode===13){systemLogin()}" id="sysLoginPass" type="password" class="sys">
        <a href="/resetPassword/forgot" class="link forgotPassword"><?=$this->t('account.1')?></a>
    </div>
    <label class="tickLabel"><input type="checkbox" name="LoginForm[remember]"><?=$this->t('account.3')?></label>
    <div class="blackButton" onclick="systemLogin()"><?=$this->t('signin')?></div>
</form>
<script type="text/javascript">window.signinFailText = "<?=$this->t('account.4')?>";</script>