
var baseData = {
    starYear: 2018,
    starMonth: 8,
    starDay: 8,
    days: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
    numbers: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
    alphabet: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
};

function TimeRight(option) {
    this._init(option);
}

TimeRight.prototype = {
    _init: function (option) {
        this.starYear = option.starYear;
        this.starMonth = option.starMonth;
        this.starDay = option.starDay;
        this.days = option.days;

        this.yearVal = option.yearVal;
        this.monthVal = option.monthVal;
        this.dayVal = option.dayVal;
        this.year = parseInt(this.yearVal);
        this.month = parseInt(this.monthVal);
        this.day = parseInt(this.dayVal);
    },
    init: function () {
        return this.right();
    },
    right: function () {
        var nowDate = new Date(),
            nowYear = nowDate.getFullYear(),
            nowMonth = nowDate.getMonth() + 1,
            nowDay = nowDate.getDate();
        if (this.year < this.starYear ||
            this.year === this.starYear && this.month < this.starMonth ||
            this.year === this.starYear && this.month === this.starMonth && this.day < this.starDay ||
            this.yearVal.length > 4) {
            layer.msg('新版本SN起始日期为：' + this.starYear + '-' + this.starMonth + '-' + this.starDay, {
                icon: 5,
                time: 2000
            });
        }
        else if (this.yearVal === '') {
            layer.msg('您还没有输入年份呢！', {icon: 5, time: 2000})
        }
        else {
            if (this.month > 12 || this.month === 0 || this.monthVal.length > 2) {
                layer.msg('请输入正确月份', {icon: 5, time: 2000})
            }
            else if (this.monthVal === '') {
                layer.msg('您还没有输入月份呢！', {icon: 5, time: 2000})
            }
            else {
                if (this.day > this.days[this.month - 1] || this.day === 0 || this.dayVal.length > 2) {
                    layer.msg('请输入正确日期\n' + '今年' + this.month + '月' + '只有' + this.days[this.month - 1] + '天', {
                        icon: 5,
                        time: 2000
                    })
                }
                else if (this.dayVal === '') {
                    layer.msg('您还没有输入日期呢！', {icon: 5, time: 2000})
                }
                else {    //日期格式判断正确，将返回true，若输入的时间日期超过了今天的日期，将给出警告，但仍然会返回true
                    if (this.year > nowYear ||
                        this.year === nowYear && this.month > nowMonth ||
                        this.year === nowYear && this.month === nowMonth && this.day > nowDay) {
                        layer.msg('请注意：当前时间为：' + nowYear + '-' + nowMonth + '-' + nowDay + '，请检查您输入的时间是否正确，如果您确定要生成这天的SN Code，请忽略本条警告', {
                            icon: 7,
                            time: 3000
                        })
                    }
                    return true
                }
            }
        }
    }
};

function GetWeek(option) {
    this._init(option)
}

GetWeek.prototype = {
    _init: function (option) {
        this.year = parseInt(option.yearVal);
        this.month = parseInt(option.monthVal);
        this.day = parseInt(option.dayVal);
        this.days = option.days
    },
    init: function () {
        return this.week()
    },
    week: function () {
        var firstDay = new Date(Date.UTC(this.year, this.month - 1, 1)).getDay();
        return Math.ceil((this.day + firstDay - 1) / 7);
    }
};

function GetCode(option) {
    this._init(option)
}

GetCode.prototype = {
    _init: function (option) {
        this.starYear = option.starYear;
        this.year = parseInt(option.yearVal);
        this.month = parseInt(option.monthVal);
        this.day = parseInt(option.day);
        this.numbers = option.numbers;
        this.alphabet = option.alphabet;
        this.starYear = option.starYear;
        this.week = option.week
    },
    init: function () {
        return this.code()
    },
    code: function () {
        var a = this.numbers.concat(this.alphabet);
        var sy = this.starYear.toString();
        var b = parseInt(sy.charAt(sy.length - 1));
        var y = a[((this.year-this.starYear)%a.length+b)%a.length];
        var m = this.alphabet[this.month - 1];
        var d = this.alphabet[this.week - 1];
        return y + m + d
    }
};

$(document).ready(function () {

    $('#btn').click(function () {
        var yearVal = $('#year').val(), monthVal = $('#month').val(), dayVal = $('#day').val();

        if ((parseInt(yearVal) % 4 === 0 && parseInt(yearVal) % 100 != 0) || parseInt(yearVal) % 400 === 0) {
            baseData.days[1] = 29;
        } else {
            baseData.days[1] = 28;
        }

        baseData.yearVal = yearVal;
        baseData.monthVal = monthVal;
        baseData.dayVal = dayVal;

        var timeRight = new TimeRight(baseData);
        if (timeRight.init()) {
            var week = new GetWeek(baseData);
            baseData.week = week.init();

            var getcode = new GetCode(baseData);
            var code = getcode.init();
            $('#code').val(code);       //最终获得的日期对应Code
        }
        else {
            $('#code').val('');         //日期输入错误，清空
        }
    });
});