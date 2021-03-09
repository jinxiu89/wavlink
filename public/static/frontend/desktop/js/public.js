function gAds() {
    var close = $("#g-ads .ads-close");
    close.click(function () {
        $(this).parents("#g-ads").slideUp();
    })
}

function navFixedTop() {
    var nav = $(".g-nav"), scroll = $(document).scrollTop(), ads = $(".g-ads"),
        adsH = ads.css("display") === "none" ? 0 : ads.outerHeight() || 0;
    if (scroll > adsH) {
        nav.addClass("g-hd-fixed");
        $(".g-section").css("padding-top", nav.outerHeight() || 0)
    } else {
        nav.removeClass("g-hd-fixed");
        $(".g-section").css("padding-top", 0);
    }
}

function navHover() {
    var firstItem = $("#nav .first-item"), secondItem = $("#nav .first-item .second-item"),
        thirdItem = $("#nav .first-item .second-item .third-item > a"),
        fourthMenu = $("#nav .first-item .second-item .third-item .fourth-menu");
    firstItem.mouseenter(function () {
        $(this).addClass("active");
        $(this).siblings(".first-item").removeClass("active");
        $("#nav").find(".third-item").removeClass("active");
        $(this).siblings(".first-item").find(".second-menu").css("display", "none");
        $(this).find(".second-menu").stop(true, false).css("display", "block")
    });
    secondItem.mouseenter(function () {
        $("#nav").find(".third-item").removeClass("active");
        $(this).siblings(".second-item").find(".third-menu").css("display", "none");
        $(this).find(".third-menu").stop(true, false).css("display", "block")
    });
    thirdItem.mouseenter(function () {
        $(this).parents(".third-item").addClass("active");
        $(this).parents(".third-item").siblings(".third-item").removeClass("active");
        $(this).parents(".third-item").siblings(".third-item").find(".fourth-menu").css("display", "none");
        $(this).siblings(".fourth-menu").stop(true, false).css("display", "block")
    });
    $("#nav").mouseleave(function () {
        $(this).find(".first-item").removeClass("active");
        $(this).find(".third-item").removeClass("active");
        $(this).find(".second-menu").stop(true, false).css("display", "none");
        $(this).find(".third-menu").stop(true, false).css("display", "none");
        $(this).find(".fourth-menu").stop(true, false).css("display", "none");
        $(this).find(".item-product").stop(true, false).css("display", "none");
        $(this).find(".item-product").stop(true, false).css("display", "none")
    })
}

function handleFootHover() {
    var footItem = $(".g-footer .foot-container dl dd a");
    footItem.hover(function () {
        if ($(this).siblings("img").length > 0) {
            $(this).parent("dd").siblings("dd").children("img").css({"display": "none"});
            $(this).siblings("img").css({"display": "block"})
        }
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

function supportAside() {
    var supportList = $(".wavlinkAside .supportItem");
    supportList.find("h3").click(function () {
        $(this).find("span").toggleClass("iconsami-select").toggleClass("iconadd-select");
        $(this).parent(".supportItem").siblings(".supportItem").find("h3").find("span").addClass("iconadd-select").removeClass("iconsami-select");
        $(this).parent(".supportItem").siblings(".supportItem").find("dl").slideUp();
        $(this).siblings("dl").stop(false, true).slideToggle()
    })
}

function handleSearchBarClick() {
    var bar = $("#search-bar"), searchBox = $("#search-form"), bgPander = $(".wavlink-bgPander"), menu = $("#nav");
    bar.click(function () {
        $(this).stop(false, true).toggleClass("iconsearch").toggleClass("iconclose");
        bgPander.stop(false, true).fadeToggle(300);
        menu.stop(false, true).fadeToggle(200);
        searchBox.stop(false, true).fadeToggle(300);
        searchBox.find(".wavlink-input").focus()
    });
    bgPander.click(function () {
        bar.stop(false, true).removeClass("iconclose").addClass("iconsearch");
        bgPander.stop(false, true).fadeOut(300);
        menu.stop(false, true).fadeIn(200);
        searchBox.stop(false, true).fadeOut(300)
    })
}

function Account() {
    var Dom = $("#account");
    Dom.hover(function () {
        $(this).find("#user-menu-wrapper").stop(false, true).slideToggle(50)
    })
}

$(function () {
    gAds();
    navFixedTop();
    $(window).bind("scroll", navFixedTop);
    navHover();
    handleFootHover();
    gPath();
    supportAside();
    handleSearchBarClick();
    Account()
});
