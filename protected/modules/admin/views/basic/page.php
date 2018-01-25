<form method="post" id="dynamicPageForm">
    <div class="infoBox">
        <textarea class="htmlEditor" id="pageContentInput"><?=$model->content?></textarea>
        <br>
        <div class="blackButton ilb" onclick="submitForm(0)">Save</div>
        <div class="blackButton ilb" onclick="<?=$previewPageUrl?'submitForm(1)':'previewInPop()'?>">Preview</div>
    </div>
</form>
<script type="text/javascript">
function submitForm(isPreview) {
    if(isPreview)
    {
        $('#pageContentInput').attr('name', 'preview_<?=$key?>');
        $('#dynamicPageForm').attr('target', '_blank').attr('action', '<?=$previewPageUrl?>').submit();
    }
    else
    {
        pend();
        preventPageLeave(0);
        $('#pageContentInput').attr('name', 'content');
        $('#dynamicPageForm').removeAttr('target').removeAttr('action').submit();
    }
}

function previewInPop()
{
    var t = $("#pageContentInput").val();
    $.facebox({div:'<div style="width:880px" class="defaultFroalaEditorPresent">' + t + '</div>', title:'Preview'});
}

function onHtmlEditorChange() { preventPageLeave(1,1); }
</script>