//导航js
$(document).ready(function () {
    //导航交互
    (function ($) {
        var $nav = $('#main-nav');
        var $toggle = $('.toggle');
        var defaultData = {
            maxWidth: false,
            customToggle: $toggle,
            navTitle: 'All CATEGORIES',
            levelTitles: true
        };

        // we'll store our temp stuff here
        var $clone = null;
        var data = {};

        // calling like this only for demo purposes

        const initNav = function (conf) {
            if ($clone) {
                // clear previous instance
                $clone.remove();
            }

            // remove old toggle click event
            $toggle.off('click');

            // make new copy
            $clone = $nav.clone();

            // remember data
            $.extend(data, conf)

            // call the plugin
            $clone.hcMobileNav($.extend({}, defaultData, data));
        }

        // run first demo
        initNav({});

        /*$('.actions').find('a').on('click', function(e) {
            e.preventDefault();

            var $this = $(this).addClass('active');
            var $siblings = $this.parent().siblings().children('a').removeClass('active');

            initNav(eval('(' + $this.data('demo') + ')'));
        });*/
    })(jQuery);

    //搜索交互
    var searchBar = $(".search-bar"),
        searchContent = $(".search-container");
    searchBar.click(function (e) {
        searchContent.stop(false, true).slideToggle(300);
        e.stopPropagation();
    });
    $(".search-container input").click(function (e) {
        e.stopPropagation();
    });
    $(document).add(".menu-bar>.toggle").click(function () {
        searchContent.slideUp(300);
    });

    //footer交互效果
    $(".about-item h3").click(function () {
        $(this).siblings("ul").stop(true, false).slideToggle();
        $(this).children("span").toggleClass("iconclose").toggleClass("iconincrease");
        $(this).parent("li").siblings("li").children("ul").stop(true, false).slideUp();
        $(this).parent("li").siblings("li").find("span").addClass("iconincrease").removeClass("iconclose");
    })
});