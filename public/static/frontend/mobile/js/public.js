function gAds () {
    var close = $('#g-ads .ads-close');
    close.click(function () {
        $(this).parents('#g-ads').slideUp()
    })
}

function navFixedTop () {
    var nav = $('.g-nav'),
        scroll = $(document).scrollTop(),
        ads = $('.g-ads'),
        adsH = ads.css('display') === 'none' ? 0 : ads.outerHeight() || 0;
    if (scroll > adsH) {
        nav.addClass('g-hd-fixed');
        $('.g-section').css('padding-top', nav.outerHeight() || 0)
    } else {
        nav.removeClass('g-hd-fixed');
        $('.g-section').css('padding-top', 0)
    }
}

function gFoot() {
    $(".about-item h3").click(function () {
        $(this).siblings("ul").stop(true, false).slideToggle();
        $(this).children("span").toggleClass("iconclose").toggleClass("iconincrease");
        $(this).parent("li").siblings("li").children("ul").stop(true, false).slideUp();
        $(this).parent("li").siblings("li").children("h3").find("span").addClass("iconincrease").removeClass("iconclose")
    })
}

function gSearch() {
    var searchBar = $(".search-bar"), searchForm = $(".search-container form"),
        searchMark = $(".search-container .search-mask-layer");
    searchBar.click(function (e) {
        searchForm.stop(false, true).fadeToggle(300);
        searchMark.stop(false, true).fadeToggle(300);
        e.stopPropagation()
    });
    $(".search-container input").click(function (e) {
        e.stopPropagation()
    });
    $(document).click(function () {
        searchForm.fadeOut(300);
        searchMark.fadeOut(300)
    })
}

function goTop() {
    var go_top = $("#go-top"), go_topP = $("#go-top p");

    function topBar() {
        var doc = $(document).scrollTop();
        if (doc >= 1000) {
            go_top.show()
        }
        if (doc < 1000) {
            go_top.hide()
        }
    }

    topBar();
    $(window).scroll(function () {
        topBar()
    });
    go_topP.click(function () {
        $("html,body").animate({"scrollTop": 0}, 300)
    })
}

function gPath() {
    var pathOl = $(".breadcrumb").width(), pathLi = $(".g-path li");
    var sum = 0;
    for (var i = 0; i < pathLi.length - 1; i++) {
        sum += $(pathLi[i]).width()
    }
    var activeWidth = $(pathLi[pathLi.length - 1]).width();
    if (activeWidth + sum > pathOl) {
        $(pathLi[pathLi.length - 1]).css({width: pathOl - sum})
    }
}

$(document).ready(function () {
    gAds();
    navFixedTop();
    $(window).bind('scroll', navFixedTop);
    gFoot();
    gSearch();
    goTop();
    gPath();
});
