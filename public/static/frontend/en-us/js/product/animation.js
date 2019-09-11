/**
 * Created by admin on 17-12-23.
 */
$(function(){
   $(window).bind("scroll",function(){
        //封装法调用函数
       var b=$(document).scrollTop();
       var c=$(window).height();
       function top(p){
           var m=(p.offset().top-b)/c;
           if(m>=0&&m<=1){
               p.css("animation","animation4 2s forwards")
           }
       }
       top($(".p_animation_one"));
       /*top($(".detail_page_twelve>.detail_page_text_cont>p"));
       top($(".detail_page_eleven>.detail_page_text_cont>p"));
       top($(".detail_page_ten>.detail_page_text_cont>p"));
       top($(".detail_page_nine>.detail_page_text_cont>p"));
       top($(".detail_page_eight>.detail_page_text_cont>p"));
       top($(".detail_page_seven>.detail_page_text_cont>p"));
       top($(".detail_page_six>.detail_page_text_cont>p"));
       top($(".detail_page_five>.detail_page_text_cont>p"));
       top($(".detail_page_four>.detail_page_text_cont>p"));
       top($(".detail_page_three>.detail_page_text_cont>p"));
       top($(".detail_page_two>.detail_page_text_cont>p"));
       top($(".detail_page_one>.detail_page_text_cont>p"));*/
       function topp(s){
           var m=(s.offset().top-b)/c;
           if(m>=0&&m<=1){
               s.css("animation","animation4_2 3s forwards")
           }
       }
       topp($(".strong_animation_one"));
       /*topp($(".detail_page_twelve>.detail_page_text_cont>strong"));
       topp($(".detail_page_eleven>.detail_page_text_cont>strong"));
       topp($(".detail_page_ten>.detail_page_text_cont>strong"));
       topp($(".detail_page_nine>.detail_page_text_cont>strong"));
       topp($(".detail_page_eight>.detail_page_text_cont>strong"));
       topp($(".detail_page_seven>.detail_page_text_cont>strong"));
       topp($(".detail_page_six>.detail_page_text_cont>strong"));
       topp($(".detail_page_five>.detail_page_text_cont>strong"));
       topp($(".detail_page_four>.detail_page_text_cont>strong"));
       topp($(".detail_page_three>.detail_page_text_cont>strong"));
       topp($(".detail_page_two>.detail_page_text_cont>strong"));
       topp($(".detail_page_one>.detail_page_text_cont>strong"));*/
       // topp($(".container-fluid>.row>.detail_page_one>.detail_page_text_cont>strong"))
   })
});

