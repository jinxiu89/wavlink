function layerInit() {
    var t = 2 * Math.sqrt(Math.pow($(window).height(), 2) + Math.pow($(window).width(), 2));

    // 2018-4-11 15:48 星期三 删除一堆不知道是什么的代码,解决了浏览器控制台显示的报错问题！
    //*
    // overlayNav.children("span").velocity({scaleX: 0, scaleY: 0, translateZ: 0}, 50).velocity({
    //     height: t + "px",
    //     width: t + "px",
    //     top: -(t / 2) + "px",
    //     left: -(t / 2) + "px"
    // }, 0),
    //     overlayContent.children("span").velocity({
    //     scaleX: 0,
    //     scaleY: 0,
    //     translateZ: 0
    // }, 50).velocity({height: t + "px", width: t + "px", top: -(t / 2) + "px", left: -(t / 2) + "px"}, 0)
}

var overlayNav = $(".overlay-nav"), overlayContent = $(".overlay-content"), navigation = $("#navigation>ul"),
    toggleNav = $(".navbut");
layerInit(), $(window).on("resize", function () {
    window.requestAnimationFrame(layerInit)
});
var str = /localhost/;
plug = str.test(location.href) ? "plug" : "/Public/Home/plug", function (a) {
    var e = {
        plug: function (e) {
            var n = new Date;
            chrimas = !1, 11 == n.getMonth() && 20 < n.getDate() && n.getDate() < 27 && (chrimas = !0);
            var s = {tips: !1, cmas: chrimas};
            e && a.extend(s, e), s.tips && (t = s.tips, document.write('<script src="' + plug + '/tips/tips.js"></script>')), "undefined" == typeof cmas_status && s.cmas && (document.write('<script src="' + plug + '/chrismas/chrismas.js"></script>'), cmas_status = !0)
        }
    };
    a.loadPlug = function (t) {
        return e[e] ? e[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? void a.error("Method " + t + " does not exist on loadPlug") : e.plug.apply(this, arguments)
    }
}(jQuery), jQuery.loadPlug(), toggleNav.on("click", function () {
    toggleNav.hasClass("close-nav") ? (toggleNav.removeClass("close-nav"), overlayContent.children("span").velocity({
        translateZ: 0,
        scaleX: 1,
        scaleY: 1
    }, 500, "easeInCubic", function () {
        navigation.removeClass("fade-in"), overlayNav.children("span").velocity({
            translateZ: 0,
            scaleX: 0,
            scaleY: 0
        }, 0), overlayContent.addClass("is-hidden").one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function () {
            overlayContent.children("span").velocity({translateZ: 0, scaleX: 0, scaleY: 0}, 0, function () {
                overlayContent.removeClass("is-hidden")
            })
        }), $("html").hasClass("no-csstransitions") && overlayContent.children("span").velocity({
            translateZ: 0,
            scaleX: 0,
            scaleY: 0
        }, 0, function () {
            overlayContent.removeClass("is-hidden")
        })
    })) : (toggleNav.addClass("close-nav"), overlayNav.children("span").velocity({
        translateZ: 0,
        scaleX: 1,
        scaleY: 1
    }, 500, "easeInCubic", function () {
        navigation.addClass("fade-in")
    }))
});