<div class="rightItem">
    <p class="tip">通知內容</p>
    <input id="notifContentInput" type="text" maxlength="150" placeholder="不超過 150 字符">
    <p class="tip" style="margin-top:5px">相關鏈接（Optional）</p>
    <input id="notifLinkInput" type="text">
    <p class="tip" style="margin-top:5px">若用戶已裝 app 並啟用通知，該通知亦會被同步推送至 app 端</p>
    <div class="blackButton" style="margin-top: 14px" onclick="saveUserNotif(<?=$user->id?>)">保 存</div>
</div>