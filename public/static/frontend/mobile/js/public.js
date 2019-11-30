function gFoot () {
    //footer交互效果
    $(".about-item h3").click(function () {
        $(this).siblings("ul").stop(true, false).slideToggle();
        $(this).children("span").toggleClass("iconclose").toggleClass("iconincrease");
        $(this).parent("li").siblings("li").children("ul").stop(true, false).slideUp();
        $(this).parent("li").siblings("li").children("h3").find("span").addClass("iconincrease").removeClass("iconclose");
    })
}
function gSearch () {
    //搜索交互
    var searchBar = $(".search-bar"),
        searchForm = $(".search-container form"),
        searchMark = $(".search-container .search-mask-layer");
    searchBar.click(function (e) {
        searchForm.stop(false, true).fadeToggle(300);
        searchMark.stop(false, true).fadeToggle(300);
        e.stopPropagation();
    });
    $(".search-container input").click(function (e) {
        e.stopPropagation();
    });
    $(document).add(".menu-bar>.toggle").click(function () {
        searchForm.fadeOut(300);
        searchMark.fadeOut(300);
    });
}
function goTop () {
    var go_top = $("#go-top"),                // 侧边导航;
        go_topP = $("#go-top p");// 侧边导航 回到顶部按钮;
    function topBar () {
        var doc = $(document).scrollTop();
        if (doc >= 1000) {
            go_top.show(); // 侧边导航 回到顶部按钮;
        }
        if (doc < 1000) {
            go_top.hide(); // 侧边导航 回到顶部按钮;
        }
    }
    // 侧边导航的显示与隐藏;
    topBar();
    $(window).scroll(function () {
        topBar()
    });
    //go-top
    go_topP.click(function () {
        $("html,body").animate({"scrollTop": 0}, 300);
    });
}
function gPath () {
    var pathOl = $('.breadcrumb').width(),
        pathLi = $('.g-path li');
    var  sum = 0;
    for (var i = 0; i < pathLi.length - 1; i++) {
        sum += $(pathLi[i]).width()
    }
    var activeWidth = $(pathLi[pathLi.length - 1]).width();
    if (activeWidth + sum > pathOl) {
        $(pathLi[pathLi.length - 1]).css({
            width: pathOl - sum
        })
    }
}
$(document).ready(function () {
    gFoot();
    gSearch();
    goTop();
    gPath();
});