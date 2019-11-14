$(document).ready(function () {
    handleFootHover();

    if ($(document).outerWidth() >= 768) {
        handleMenuBarHover()
    }

    if ($(document).outerWidth() <= 1199) {
        handleSearchBarClick()
    }

    function handleSearchBarClick() {
        //768-1199px搜索交互
        var bar = "#search-bar",
            box = "#search-box";
        $(bar).click(function (e) {
            $(this).siblings(box).slideToggle();
            $(this).siblings(box).find(".text").focus();
            e.stopPropagation();
        });
        $(box).click(function (e) {
            e.stopPropagation();
        });
        $(document).click(function () {
            $(box).slideUp(300);
        });
    }

    function handleMenuBarHover () {
        // header 头部导航 划过显示效果;
        var menu = $("#menu > .menu-item");
        menu.hover(function () {
            $(this).children("ul").stop(true, false).slideDown(50)
        }, function () {
            $(this).children("ul").stop(true, false).slideUp(50)
        });
    }

    function handleFootHover () {
        var footItem = $('.g-footer .foot-container dl dd a')
        footItem.hover(function () {
            if ($(this).siblings('img').length > 0) {
                $(this).parent('dd').siblings('dd').children('img').css({'display': 'none'})
                $(this).siblings('img').css({'display': 'block'})
            }
        })
    }
})