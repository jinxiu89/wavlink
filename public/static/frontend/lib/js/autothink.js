/**
 * Created by guo on 2018/5/2.
 * #search-text input标签的id
 * #pro_list input联想框 ul 的 父元素div的id
 * #pro_list_ul input联想框ul的id
 *
 */
$(function () {
    if ($(window).width() >= 1024){
        autoThink();
    }

});
function autoThink() {
    ularray = [];
    //给ul传入数据
    var ul = $("#pro_list_ul");
    $('#search-text').keyup(function () {
        var key = $('#search-text').val();
        var url = SCOPE.search_product_op;
        $.get(url, {'key': key}, function (data) {
            ularray.splice(0, ularray.length); //清空数组
            for(var i = 0; i<data.length;i++){
                if (data[i].name.toLowerCase().match(key.toLowerCase()) ){
                    ularray.push(data[i].name);
                }
            }
            for(var k = 0; k<data.length;k++){
                if (data[k].model.toLowerCase().match(key.toLowerCase()) ){
                    ularray.push(data[k].model);
                }
            }
            ularray.sort();
            var innerhtml = "";
            innerhtml += "<ul>";
            for(var j=0; j < ularray.length;j++){
                var search_url = SCOPE.search_product + '?key=' + ularray[j];
                innerhtml += "<li style='padding:5px 5px 0 5px'><a style='color: #0b0b0b' href='"+ search_url +"'>" + ularray[j] + "</a></li>";

            }
            innerhtml +="</ul>";
            $("#pro_list").html(innerhtml);
            $("#pro_list").css("display","block");
            if (key == ''){
                $("#pro_list").css("display","none");
            }
        });
    });
    $(document).click(function () {
        $("#pro_list").css("display","none");
    });
}
