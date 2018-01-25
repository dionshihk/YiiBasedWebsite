function inArray(e, t) {
    for (var o = 0; o < e.length; o++) if (t == e[o]) return !0;
    return !1
}

function isInt(e, t, o) {
    return e = parseInt($.trim(e)), isNaN(e) ? !1 : "undefined" != typeof t ? e >= t && o >= e ? !0 : !1 : !0
}

function htmlEncode(e) {
    return $("<div/>").text(e).html()
}

function isNumber(e, t, o) {
    return e = parseFloat($.trim(e)), isNaN(e) ? !1 : "undefined" != typeof t ? e >= t && o >= e ? !0 : !1 : !0
}

function isHidden($elem) {
    return $elem.is(":hidden") || $elem.css("visibility") == "hidden" || $elem.css("opacity") == 0;
}

function vl(e) {
    return $.trim($(e).val())
}

function jsRepeat(e, t, o) {
    for (var n = "", i = 0; t > i; i++) n += e;
    return o && (n += o), n
}

function jsSplit(e) {
    for (var t = e.split(" "), o = [], n = 0; n < t.length; n++) t[n] && o.push(t[n]);
    return o
}

function reloadUriWithParam(e, t) {
    pend();
    if (e !== 'page' && uriParams['page']) {
        delete uriParams['page'];
    }
    uriParams[e] = t;
    window.location = "?" + $.param(uriParams, 1);
}

function hasExt(e, t) {
    e = e.toLowerCase();
    for (var o = 0; o < t.length; o++) {
        var n = "." + t[o].toLowerCase();
        if (e.indexOf(n) > 0) return 1
    }
    return 0
}

function pend(isHide) {
    if (!window.disablePending) {
        if(isHide) $("#jmpOverlay,#jmpBox").hide();
        else $("#jmpOverlay,#jmpBox").show();
    }
}

function pop(e, hideOkButton) {
    pend(1);
    if($("#facebox").length)
    {
        e = e.replace("<br>", "\n");
        alert(e);
    }
    else
    {
        $.facebox({
            div: '<div style="font-size:14px">' + e + '</div><div class="blackButton" style="' + (hideOkButton ? 'display:none' : 'margin-top:20px') + '" onclick="$.facebox.close()">OK</div>',
            title: "Mask Club"
        });
    }
}

(function (e) {
    function t(t) {
        var o = e.facebox.settings.imageTypes.join("|");
        e.facebox.settings.imageTypesRegexp = new RegExp(".(" + o + ")$", "i"), t && e.extend(e.facebox.settings, t), e("body").append(e.facebox.settings.faceboxHtml)
    }

    function o(t, o) {
        var n = new Image;
        n.onload = function () {
            e.facebox.reveal('<div class="image"><img style=\'max-height:550px\' src="' + n.src + '" /></div>', o)
        }, n.src = t
    }

    function n(t, o) {
        e.get(encodeURI(t), function (t) {
            e.facebox.reveal(t, o)
        })
    }

    function i() {
        return 0 == e("#facebox_overlay").length && e("body").append('<div id="facebox_overlay"></div>'), e("#facebox_overlay").hide().addClass("facebox_overlayBG").css("opacity", .7).fadeIn(200), !1
    }

    function a() {
        return e("#facebox_overlay").remove(), !1
    }

    var c = !0;
    e.facebox = function (t, i, a) {
        c = !i, e.facebox.loading(), t.ajax ? n(t.ajax, a) : t.image ? o(t.image, a) : t.div && e.facebox.reveal(t.div, a), t.title && e("#facebox .header").html(t.title), t.noHeader && e("#facebox .header").remove()
    }, e.extend(e.facebox, {
        settings: {
            imageTypes: ["png", "jpg", "jpeg", "gif"],
            faceboxHtml: "<div id='facebox' style='display:none'><div class='popup'><div class='header'>　</div><div class='content'></div><i class='fa fa-lg fa-close'></i></div>"
        }, loading: function () {
            function o(t) {
                var o = e(window).event || t, i = parseInt(n.css("top")) + o.clientY - r.y,
                    c = parseInt(n.css("left")) + o.clientX - r.x, s = e(window).height() - a.height(),
                    l = e(window).width() - a.width();
                return 0 > i ? i = 0 : i > s && (i = s), 0 > c ? c = 0 : c > l && (c = l), r.y = o.clientY, r.x = o.clientX, n.css({
                    top: i,
                    left: c
                }), !1
            }

            t(), i();
            var n = e("#fbDrag"), a = e("#facebox"), r = {x: 0, y: 0};
            c ? e("#facebox i.fa-close").show().click(e.facebox.close) : e("#facebox i.fa-close").hide(), a.find(".content").empty(), a.find(".header").mousedown(function (t) {
                var i = e(window).event || t;
                return r.x = i.clientX, r.y = i.clientY, n.length || (n = e('<div class="facebox_bdr"/>'), e("body").append(n)), n.show().css({
                    width: a.width(),
                    height: a.height(),
                    top: parseInt(a.css("top")),
                    left: parseInt(a.css("left"))
                }), e(document).bind("mousemove", o), !1
            }), e("#facebox .body").children().hide().end(), c && e(document).bind("keydown.facebox", function (t) {
                return 27 == t.keyCode && e.facebox.close(), !0
            }), e(document).mouseup(function (t) {
                return a.css({top: n.css("top"), left: n.css("left")}), e(document).unbind("mousemove", o), n.hide(), !1
            })
        }, reveal: function (t, o) {
            o && e("#facebox .content").addClass(o), e("#facebox .content").append(t), e("#facebox .body").children().show(), setTimeout(function () {
                e("#facebox").show(), "function" == typeof initBox && initBox(), e("#facebox .header").css("width", e("#facebox .popup").width()), e("#facebox .content").css("max-height", e(window).height() - 100 + "px"), e("#facebox").css("left", e(window).width() / 2 - e("#facebox .popup").width() / 2).css("top", Math.max(12, e(window).height() / 2 - e("#facebox .popup").height() / 2 - 99))
            }, 400)
        }, close: function () {
            return e(document).trigger("close.facebox"), !1
        }
    }), e.fn.facebox = function (o) {
        function n() {
            c = !0, e.facebox.loading(!0);
            var t = this.rel.match(/facebox\[?\.(\w+)\]?/);
            return t && (t = t[1]), fillFaceboxFromHref(this.href, t), !1
        }

        if (0 != e(this).length) return t(o), this.bind("click.facebox", n)
    }, e(document).bind("close.facebox", function () {
        e(document).unbind("keydown.facebox"), window.initBox = null, e("#facebox").empty().remove(), e(".facebox_bdr").remove(), e("#globalMap").trigger("closeMap"), a()
    })
})(jQuery);

/*!
 * hoverIntent v1.9.0 // 2017.09.01 // jQuery v1.7.0+
 * http://briancherne.github.io/jquery-hoverIntent/
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007-2017 Brian Cherne
 */
!function(factory){"use strict";"function"==typeof define&&define.amd?define(["jquery"],factory):jQuery&&!jQuery.fn.hoverIntent&&factory(jQuery)}(function($){"use strict";var cX,cY,_cfg={interval:100,sensitivity:6,timeout:0},INSTANCE_COUNT=0,track=function(ev){cX=ev.pageX,cY=ev.pageY},compare=function(ev,$el,s,cfg){if(Math.sqrt((s.pX-cX)*(s.pX-cX)+(s.pY-cY)*(s.pY-cY))<cfg.sensitivity)return $el.off(s.event,track),delete s.timeoutId,s.isActive=!0,ev.pageX=cX,ev.pageY=cY,delete s.pX,delete s.pY,cfg.over.apply($el[0],[ev]);s.pX=cX,s.pY=cY,s.timeoutId=setTimeout(function(){compare(ev,$el,s,cfg)},cfg.interval)},delay=function(ev,$el,s,out){return delete $el.data("hoverIntent")[s.id],out.apply($el[0],[ev])};$.fn.hoverIntent=function(handlerIn,handlerOut,selector){var instanceId=INSTANCE_COUNT++,cfg=$.extend({},_cfg);$.isPlainObject(handlerIn)?(cfg=$.extend(cfg,handlerIn),$.isFunction(cfg.out)||(cfg.out=cfg.over)):cfg=$.isFunction(handlerOut)?$.extend(cfg,{over:handlerIn,out:handlerOut,selector:selector}):$.extend(cfg,{over:handlerIn,out:handlerIn,selector:handlerOut});var handleHover=function(e){var ev=$.extend({},e),$el=$(this),hoverIntentData=$el.data("hoverIntent");hoverIntentData||$el.data("hoverIntent",hoverIntentData={});var state=hoverIntentData[instanceId];state||(hoverIntentData[instanceId]=state={id:instanceId}),state.timeoutId&&(state.timeoutId=clearTimeout(state.timeoutId));var mousemove=state.event="mousemove.hoverIntent.hoverIntent"+instanceId;if("mouseenter"===e.type){if(state.isActive)return;state.pX=ev.pageX,state.pY=ev.pageY,$el.off(mousemove,track).on(mousemove,track),state.timeoutId=setTimeout(function(){compare(ev,$el,state,cfg)},cfg.interval)}else{if(!state.isActive)return;$el.off(mousemove,track),state.timeoutId=setTimeout(function(){delay(ev,$el,state,cfg.out)},cfg.timeout)}};return this.on({"mouseenter.hoverIntent":handleHover,"mouseleave.hoverIntent":handleHover},cfg.selector)}});


function fillToLen2(v) {
    v = v.toString();
    return v.length >= 2 ? v : ('0' + v);
}

function pop(t) {
    $('#topPopBox .core').html('<i class="fa fa-info-circle fa-lg"></i>' + t).show();
    setTimeout(function () {
        $('#topPopBox .core').fadeOut();
    }, 2400);
}


var uri = document.location.pathname + document.location.search, uriParams = {},
    mailRegex = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
    nickNameRegex = /^(\w){3,20}$/;

//Load earlier, so that the auto session tip works!

$('body').prepend('<div id="topPopBox"><div class="core"></div></div>');
var params = document.location.search, reg = /(?:^\?|&)(.*?)=(.*?)(?=&|$)/g, temp;
while ((temp = reg.exec(params)) != null) uriParams[temp[1]] = decodeURIComponent(temp[2]);
if (uriParams['keyword']) uriParams['keyword'] = uriParams['keyword'].replace(/\+/g, " ");

$(function ()
{
    $.ajaxSetup({cache: false});
    $('body').prepend('<div id="jmpOverlay"/>').prepend('<div id="jmpBox"><img src="/assets/image/loading.gif"/></div>');
    $('body').prepend('<div id="textTipBox"></div>');

    $('body').on('click', '[popup]', function () {
        var t = $(this).attr('popupTitle');
        if (!t) t = $(this).text();
        var p = $(this).attr('popup');
        var dt = {title: t};
        if (hasExt(p, ['jpg', 'jpeg', 'png', 'gif', 'bmp']) || $(this).attr('popupIsImage')) dt.image = p;
        else dt.ajax = p;
        $.facebox(dt);
        return false;
    });

    $('body').hoverIntent({
        over: function () { $(this).find('.hidden').toggleClass('shown'); },
        selector: '.dropDown',
        timeout: 130
    });

    $('[tipText]').hover(function () {
        var t = $(this).attr('tipText');
        if(t && !isHidden($(this)))
        {
            var showInBottom = ($(this).attr('tipTextBelow') === '1');
            var showAlignRight = ($(this).attr('tipTextRight') === '1');
            var loc = $(this).offset();
            var cssObj = {'top': loc.top - $(document).scrollTop() + (showInBottom ? $(this).outerHeight() + 15 : -30)};
            if(showAlignRight)
            {
                cssObj['right'] = $(document).width() - loc.left - $(this).outerWidth();
                cssObj['left'] = 'auto';
            }
            else
            {
                cssObj['right'] = 'auto';
                cssObj['left'] = loc.left - $(document).scrollLeft();
            }

            $('#textTipBox').html(t).css(cssObj).show();
        }
    }, function () {
        $('#textTipBox').hide();
    });


    var hash = location.hash.replace('#', '');
    var $hash = $('[name="' + hash + '"]');
    if($hash.length)
    {
        $('html,body').animate({scrollTop:$hash.offset().top - 50});
    }
});

function systemLogin()
{
    var $n = $('#sysLoginName'), $p = $('#sysLoginPass');
    var n = vl($n), p = vl($p);
    if(!$.trim(n)) { $n.focus(); }
    else if(!p) { $p.focus(); }
    else {
        $.get('/site/ajaxLogin?name=' + n + '&pass=' + p, function(d)
        {
            if(d != '0') { $('#loginForm').attr('action', '/site/index?returnUrl=' + uri).submit(); }
            else { pop('Your account name or password is incorrect.'); }
        });
    }
}

function magnifyImage($selector) {
    $selector.each(function () {
        if(!$(this).hasClass('magAdded'))
        {
            var src = $(this).attr('src'), className = $(this).attr('class');
            var $b = $('<div class="imgMagnifyContainer"><div class="zoom"><i class="fa fa-2x fa-search-plus"></i></div>' +
                '<img src="' + src + '" class="' + className + ' magAdded"></div>');
            $b.mouseover(function () {
                $(this).find('.zoom').show();
            }).mouseout(function () {
                $(this).find('.zoom').hide();
            }).find('.zoom').click(function () {
                window.open(src);
            });
            $(this).replaceWith($b);
        }

    });
}

function scrollToTop() {
    $('html,body').animate({scrollTop:0});
}

(function ($)
{
    "use strict";
    $.fn.ajaxPageLoader = function(url, options)
    {
        var mergedOptions = $.extend({}, $.fn.ajaxPageLoader.defaultOptions, options);
        var $container = $(this).addClass('ajaxPlContainer'), currentPage = 0, isLastPage = false;

        function loadNextPage()
        {
            if($load.hasClass('loading')) return;
            $load.addClass('loading').html(mergedOptions.loadingHtml);

            var combinedUrl, pageParameterPart = (mergedOptions.pageParameterName + '=' + currentPage);

            if(url.indexOf('?') > 0) { combinedUrl = url + '&' + pageParameterPart; }
            else { combinedUrl = url + '?' + pageParameterPart; }

            $.get(combinedUrl, function (d) {
                try {
                    d = JSON.parse(d);
                    if($.isArray(d))
                    {
                        var dataLength = d.length;
                        if(dataLength > 0)
                        {
                            for(var i = 0; i < dataLength; i++)
                            {
                                var $newItem = mergedOptions.builderCallback(d[i]);
                                if($newItem)
                                {
                                    $core.append($newItem);
                                }
                            }

                            $load.removeClass('loading').html(loadHtml);
                            currentPage++;

                            if(mergedOptions.pageSize > 0 && dataLength < mergedOptions.pageSize) isLastPage = true;
                        }
                        else { isLastPage = true; }

                        if(isLastPage)
                        {
                            //Reach the last page
                            $load.remove();
                            if(currentPage === 0)
                            {
                                $core.html(mergedOptions.nothingHtml);
                                mergedOptions.nothingCallback();
                            }
                        }
                    }
                }
                catch (ex) {}
            });
        }

        var loadHtml = '<div class="default">' + mergedOptions.loadMoreText + '<i class="fa fa-angle-double-down"></i></div>';
        var $core = $('<div class="core"></div>').appendTo($container);
        var $load = $('<div class="load">' + loadHtml + '</div>').click(loadNextPage).appendTo($container);
        loadNextPage();
    };

    $.fn.ajaxPageLoader.defaultOptions =
    {
        pageSize: 0,    //Use this can avoid unnecessary show "Load More"
        loadMoreText: 'Load More',
        loadingHtml: '<img src="/assets/ajaxLoading.gif">',
        nothingHtml: '',
        nothingCallback: $.noop,
        pageParameterName: 'page',
        builderCallback: $.noop
    };
})(jQuery);
