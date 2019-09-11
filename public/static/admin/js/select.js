/***
 * 20180914
 * 作者:黄志辉
 * 作用：用于选择框左右列表切换
 * 依赖：jquery,需要在此方法前就引入jquery
 */
function Add(option) {
    this._init(option)
}

$.extend(Add.prototype, {
    _init: function (option) {
        this.selectClass = option.selectClass || "check-box";
        this.containLeft = option.containLeft || "containLeft";
        this.containRight = option.containRight || "containRight";
        this.goLeft = option.goLeft || "goLeft";
        this.goRight = option.goRight || "goRight";
        this.btnSubmit = option.btnSubmit || "btnSubmit";
        this.btnReset = option.btnReset || "btnReset"
    },
    init: function () {
        this.bindEvents();
        return this.btnClick()
    },
    bindEvents: function () {
        var that = this;
        var goLight = $("." + this.goLeft);
        var goRight = $("." + this.goRight);
        goLight.click(function () {
            that.handLeBtnClick(that.containRight, that.containLeft);
        });
        goRight.click(function () {
            that.handLeBtnClick(that.containLeft, that.containRight);
        });
    },
    handLeBtnClick: function (containSelected, containAdd) {
        var arrSelected = $("." + containSelected + " input:checkbox:checked").parent();
        /*$(arrSelected).clone(true).appendTo("."+containAdd);  //复制指定的input到指定的容器（不会删除原先的节点）*/
        $("." + containAdd).append(arrSelected);  // 移动选中的input到指定的容器
        $("." + containAdd + " input:checkbox").each(function () {
            this.checked = false;
        });
    },
    btnClick: function () {
        var a = $(".containRight>div>label");
        var arr = [];
        for (var i = 0; i < a.length; i++) {
            arr.push(a[i])
        }
        return arr
    }
});