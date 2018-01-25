$(function(){
    $('#bodyLeft .l2').click(function(){
        window.location = $(this).attr('link');
    }).each(function(){
        var l = $(this).attr('link');
        if(uri.lastIndexOf('/') == uri.indexOf('/')) { uri += '/index'; }
        if(uri.indexOf(l) == 0)
        {
            $(this).addClass('sel');
            return false;
        }
    });

    $('#bodyLeft .l0').each(function(){
        var m = $(this).attr('relModule');
        if(uri.indexOf(m) == 1)
        {
            $(this).addClass('sel');
            $('#bodyLeft').scrollTop($(this).offset().top);
            return false;
        }
    });

    //Call window.onHtmlEditorChange(editor, editorValue) auto
    if($('foo').froalaEditor)
    {
        var toolbarIcons = ['bold','italic','underline','strikeThrough','fontSize','color','align','formatOL','formatUL','|',
            'insertLink','insertImage','insertVideo','insertTable','insertHR','|', 'undo','redo','fullscreen','html'];
        $('.htmlEditor').each(function () {
            $('.htmlEditor').froalaEditor({
                key: 'VxwdsG2ywiC-21==',
                editorClass: 'defaultFroalaEditorPresent',
                placeholderText: '',
                heightMax:700,
                linkInsertButtons: [],
                fontSize: ['10', '12', '14', '16', '18', '20', '22', '30'],
                imagePaste: false,
                imageUploadURL: '/htmlEditorUploader/byFroala',
                imageDefaultAlign: 'left',
                imageDefaultWidth: 0,
                imageMaxSize: 1024 * 1024 * 25,
                videoDefaultAlign: 'left',
                tableResizerOffset: 10,
                tableResizingLimit: 50,
                toolbarSticky: false,
                toolbarButtons: toolbarIcons,
                toolbarButtonsMD: toolbarIcons
            }).on('froalaEditor.contentChanged', function () {
                if(window.onHtmlEditorChange)
                {
                    var val = $(this).froalaEditor('html.get');
                    onHtmlEditorChange(this, val);
                }
            });
        });

    }
});

//Override the function
function scrollToTop()
{
    $('#bodyRight').animate({scrollTop:0});
}

function resetPassword(uid)
{
    var v = prompt("Please enter the new password", "");
    if(v)
    {
        $.get('/admin/user/resetPassword', {id: uid, password:v}, function(){
            location.reload();
        });
    }
}

function confirmThenReload(url, id, actionName)
{
    if(!actionName) actionName = 'Delete';
    if(confirm('Are you sure about the following action:\n' + actionName))
    {
        $.get(url, id ? {id: id} : {}, function(){
            location.reload();
        });
    }
}

function saveUserField(id, f)
{
    var v = vl('#userFieldInput');
    pend();
    $.get('/admin/user/updateField', {id:id, fieldName:f, value:v}, function(){
        location.reload();
    })
}

function saveUserNotif(id)
{
    var v = vl('#notifContentInput'), l = vl('#notifLinkInput');
    if(v) {
        pend();
        $.get('/admin/user/createNotif', {id: id, content: v, link: l}, function () {
            location.reload();
        });
    }
    else {  pop('Please fill in the notification content'); }
}

function submitCreateAdmin()
{
    var accessParamObject = [];
    if($('#regularAdminItem').is(':visible'))
    {
        $('#authCheckBox .item').each(function(){
            if($(this).find('.chk').is(':checked'))
            {
                authObject.push({'module': $(this).attr('relAuth')});
            }
        });
        if(accessParamObject.length === 0) { pop('Please select at least one item'); return;}
    }

    pend();
    $.post('/admin/system/adminCreate?nickname=<?=$user->nickname?>&adminType=' + vl('#adminTypeSelect'),
        {'access_param': JSON.stringify(authObject)}, function(){
            window.location = '/admin/system/admin';
        });
}
