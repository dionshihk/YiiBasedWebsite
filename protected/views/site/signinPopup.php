<form id="loginForm" method="post">
    <p>Nickname/Email ››</p>
    <input name="LoginForm[name]" class="sys" id="sysLoginName" type="text">
    <p style="position:relative">Password ››
        <a href="/resetPassword/forgot" class="link forgotPassword">Forgot Password ?</a>
    </p>
    <input name="LoginForm[password]" onkeyup="if(event.keyCode===13){systemLogin()}" id="sysLoginPass" type="password" class="sys">
    <label class="tickLabel"><input type="checkbox" name="LoginForm[remember]">Remember My Account On This Device</label>
    <div class="blackButton" onclick="systemLogin()">Sign In</div>
</form>