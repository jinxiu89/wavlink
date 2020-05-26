function handleMenuBarHover () {
    // header 头部导航 划过显示效果;
    var menu = $(".wavlink-menu > .menu-item");
    menu.hover(function () {
        $(this).children("ul").stop(false, true).slideDown(50)
    }, function () {
        $(this).children("ul").stop(false, true).slideUp(50)
    });
}

function handleFootHover () {
    var footItem = $('.g-footer .foot-container dl dd a');
    footItem.hover(function () {
        if ($(this).siblings('img').length > 0) {
            $(this).parent('dd').siblings('dd').children('img').css({'display': 'none'});
            $(this).siblings('img').css({'display': 'block'})
        }
    })
}

function gPath () {
    var pathOl = $('.breadcrumb').width(),
        pathLi = $('.g-path li');
    var sum = 0;
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

function supportAside () {
    var supportList = $('.wavlinkAside .supportItem');
    supportList.find('h3').click(function () {
        $(this).find('span').toggleClass("iconsami-select").toggleClass("iconadd-select");
        $(this).parent('.supportItem').siblings('.supportItem').find('h3').find('span').addClass("iconadd-select").removeClass("iconsami-select");
        $(this).parent('.supportItem').siblings('.supportItem').find('dl').slideUp();
        $(this).siblings('dl').stop(false, true).slideToggle();
    });
}

function wavlinkAds() {
    var ads = $('.wavlink-ads');
    var nav = $('.g-nav');

    if (ads.length) {
        var adsH = ads.height();
        $('body').css({
            paddingTop: adsH + 50
        });
        ads.slideDown(300);
        nav.css({
            top: adsH
        });
        ads.find('.ads-close').click(function () {
            ads.slideUp(300);
            $('body').css({
                paddingTop: 50
            });
            nav.css({
                top: 0
            });
        })
    }
}

function handleSearchBarClick() {
    var bar = $('#search-bar'),
        searchBox = $('#search-form'),
        bgPander = $('.wavlink-bgPander'),
        menu = $('#menu');
    bar.click(function () {
        $(this).stop(false, true).toggleClass('iconsearch').toggleClass('iconclose');
        bgPander.stop(false, true).fadeToggle(300);
        menu.stop(false, true).fadeToggle(200);
        searchBox.stop(false, true).fadeToggle(300);
        searchBox.find('.wavlink-input').focus();
    });
    bgPander.click(function () {
        bar.stop(false, true).removeClass('iconclose').addClass('iconsearch');
        bgPander.stop(false, true).fadeOut(300);
        menu.stop(false, true).fadeIn(200);
        searchBox.stop(false, true).fadeOut(300)
    })
}

function Account() {
    var Dom = $('#account');
    Dom.hover(function () {
        $(this).find('#user-menu-wrapper').stop(false, true).slideToggle(50)
    })
}

$(function () {
    handleFootHover();

    gPath();

    supportAside();

    wavlinkAds();

    handleSearchBarClick();

    Account();

    if ($(document).outerWidth() >= 768) {
        handleMenuBarHover()
    }
});