$(function () {
    "use strict";

    // 重新加载页面回到顶部;
    setTimeout(function () {
        $(window).scrollTop(0);
    }, 50);

    // header 头部导航 划过显示效果;
    if($(document).outerWidth() >= 1152){
        $("ul.navbar-nav>li").hover(function(){
            $(this).children("ul").stop(true,false).slideDown(50);
        },function(){
            $(this).children("ul").stop(true,false).slideUp(50);
        });
    }

    // footer 固定在页面的最底部;
    $(".max").css("padding-bottom",($("footer").height())+"px");
    if($(document).outerWidth() >= 800){
        $("body").css("padding-top",($("nav.navbar-default").height())+"px");
    }

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
            $(".navbar-nav").fadeToggle(200);
            sear_form.fadeToggle(200);
            sear_form.css({
                position: "fixed",
                left: 26+"%",
                top: 12+"px"
            });
        });
    }
});
//2018.2.6添加通用动画js
$(document).ready(function(){
    function isIE() { //ie?
        if (!!window.ActiveXObject || "ActiveXObject" in window){
            $("div#Detail_page_all>div.container-fluid>div.row>section p").css("opacity","1");
            $("div#Detail_page_all>div.container-fluid>div.row>section strong").css("opacity","1");
        }
        else {
            var win_w=$(document).width();
            if (win_w>=1199){
                $("div#Detail_page_all>div.container-fluid>div.row>section p").css("opacity","0");
                $("div#Detail_page_all>div.container-fluid>div.row>section strong").css("opacity","0");
                $("div#Detail_page_all>div.container-fluid>div.row>section:nth-of-type(1) p").css("animation","animation_p 1s .5s forwards");
                $("div#Detail_page_all>div.container-fluid>div.row>section:nth-of-type(1) strong").css("animation","animation_strong 1s forwards");
                $(window).bind("scroll",function(){
                    var sec=$("div#Detail_page_all>div.container-fluid>div.row>section");
                    var win_h=$(window).height();
                    var document_sT=$(document).scrollTop();
                    var section_length=sec.length;
                    for (var i=1;i<section_length;i++){
                        //判断是否存在detail_page_text_cont类的标签（错误修正，解决当不存在此类标签时，出现报错）
                        if((sec).eq(i).find(".detail_page_text_cont").add(".detail_box_text ").length!=0){
                            var tex_sT=(sec).eq(i).find(".detail_page_text_cont").add(".detail_box_text ").offset().top;
                            var tex_animation=(tex_sT-document_sT)/win_h;
                            if (tex_animation>0&&tex_animation<1){
                                (sec).eq(i).find("p").css("animation","animation_p 1s .5s forwards");
                                (sec).eq(i).find("strong").css("animation","animation_p 1s forwards")
                            }
                        }
                    }
                });
            }
        }
    }
    isIE();
});
