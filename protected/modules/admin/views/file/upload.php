<form method="post" id="dataForm" enctype="multipart/form-data">
    <div class="infoBox">
        <div class="item">
            <span class="leftItem">選擇文件</span>
            <span class="rightItem">
                <input id="customFileUploader" name="files[]" multiple="multiple" type="file">
                <p class="tip">一次性可選擇最多 10 個文件</p>
                <p class="tip">單次上載總大小限制為 <?=ini_get("upload_max_filesize")?></p>
                <p class="tip">如欲上載超出該大小的文件，請聯絡管理員處理</p>
            </span>
        </div>
        <div class="item">
            <span class="leftItem"></span>
            <span class="rightItem">
                <div class="blackButton" onclick="submitForm()">開始上載</div>
            </span>
        </div>
    </div>
    <input type="hidden" name="isUpload" value="1">
</form>
<script type="text/javascript">
function submitForm()
{
    var fileList = $('#customFileUploader')[0].files;
    if(!fileList.length) { pop('請上載至少一個文件'); }
    else if(fileList.length > 10) { pop('請最多上傳 10 個文件'); }
    else {
        pend();
        $('#dataForm').submit();
    }
}
</script>