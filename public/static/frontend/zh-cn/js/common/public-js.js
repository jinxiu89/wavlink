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

    if($(document).width() >= 768){
        var url = window.location.href;
        $(window).resize(function(){
            window.location.href = url;
        });
    }
});
//2018.2.6添加通用动画js
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
        }
    }
    isIE();
});


//768屏首页搜索框处理
$(document).ready(function(){
    // 宽度768设备 搜索;
    if($(document).width() >= 768 && $(document).width() <= 991) {
        var p_icon_sear = $("div.search_box p"), sear_form = $("div.search_box form");
        p_icon_sear.click(function (e) {
            sear_form.fadeToggle(200);
            $("div.search_box input:nth-of-type(1)").focus();
            e.stopPropagation();
            sear_form.css({
                position: "fixed",
                right: "5px",
                top: "40px",
                width: "35%"
            });
        });
        $("body>div#mescroll>div.max").on("touchend", function() {
            function searchSlideUp(){
                sear_form.fadeOut()
            }
            searchSlideUp()
        });
    }
});
