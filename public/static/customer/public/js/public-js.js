/**
 * Created by admin on 18-4-12.
 */
$(function () {
    "use strict";

    // 重新加载页面回到顶部;
    setTimeout(function () {
        $(window).scrollTop(0);
    }, 50);

    // header 头部导航 划过显示效果;
    if($(document).outerWidth() >= 1152){
        $("ul.navbar-nav>li").hover(function(){
            $(this).children("ul").stop(true,false).slideDown(50)
        },function(){
            $(this).children("ul").stop(true,false).slideUp(50)
        });
    }

    // footer 固定在页面的最底部;
    $(".max").css("padding-bottom",($("footer").height())+"px");
    if($(document).outerWidth() >= 800){
        $("body").css("padding-top",($("nav.navbar-default").height())+"px");
    }

    //2018.2.26删除，删除原因：添加了窗口变化，执行刷新事件
    /*// 判断设备竖屏和横屏，并进行刷新;
     $(window).on("orientationchange",function(){
     if(window.orientation == 0 || window.orientation == 180){ // 竖屏;
     location.reload();
     }else{ // 横屏;
     location.reload();
     }
     });*/

    //2018.2.26添加,窗口变化，执行刷新
    //兼容火狐（火狐的刷新机制有个优先缓存的问题在里面，故而用location.href="网址"，然后网址里面加入random()随机数）
    //注释加入随机数代码，原因：出bug
    /*var parm = parseInt(Math.random() * 10);
     if (url.lastIndexOf('?') > -1) {
     url = url + parm;
     } else {
     url = url + "?" + parm;
     }*/
    if($(document).width() >= 768){
        var url = window.location.href;
        $(window).resize(function(){
            window.location.href = url;
        });
    }


    // 宽度768设备 搜索;
    if($(document).outerWidth() >= 768 || $(document).outerWidth() <= 991) {
        var p_icon_sear = $("div.search_box p"), sear_form = $("div.search_box form");
        p_icon_sear.click(function () {
            sear_form.fadeToggle(200);
            sear_form.css({
                position: "fixed",
                left: "190px",
                top: 0,
                width: "62%"
            });
            $("div.search_box div.form-group").css("margin-top", "2px")
        });
    }
});
//2018.2.6添加通用动画js
$(document).ready(function(){
    function isIE() { //ie?
        if (!!window.ActiveXObject || "ActiveXObject" in window){
            $("div#Detail_page_all>div.container-fluid>div.row>section>div>div.detail_page_text_cont>p").css("opacity","1");
            $("div#Detail_page_all>div.container-fluid>div.row>section>div>div.detail_page_text_cont>strong").css("opacity","1");
        }
    };
    isIE();
    var win_w=$(window).width();
    if (win_w>=1366){
        $("section:nth-of-type(1)>div>div.detail_page_text_cont>p").css("animation","animation_p 1s .5s forwards");
        $("section:nth-of-type(1)>div>div.detail_page_text_cont>strong").css("animation","animation_strong 1s forwards");
        $(window).bind("scroll",function(){
            var sec=$("div#Detail_page_all>div.container-fluid>div.row>section");
            var win_h=$(window).height();
            var document_sT=$(document).scrollTop();
            var section_length=sec.length;
            for (var i=1;i<section_length;i++){
                //判断是否存在detail_page_text_cont类的标签（错误修正，解决当不存在此类标签时，出现报错）
                if((sec).eq(i).find(".detail_page_text_cont").length!=0){
                    var tex_sT=(sec).eq(i).find(".detail_page_text_cont").offset().top;
                    var tex_animation=(tex_sT-document_sT)/win_h;
                    if (tex_animation>0&&tex_animation<1){
                        (sec).eq(i).find("p").css("animation","animation_p 1s .5s forwards");
                        (sec).eq(i).find("strong").css("animation","animation_p 1s forwards")
                    }
                }
            }
        });
    }
});
