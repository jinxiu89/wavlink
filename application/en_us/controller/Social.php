<?php

namespace app\en_us\controller;


class Social extends Base
{
    public function index()
    {
        print_r("hello man<br />");
        print_r("社招首页<br />");
    }
    public function list()
    {
        # code...
        print_r("社招职位列表页");
    }
    function details()
    {
        # code...
        print_r("详情页");
    }
    function gain()
    {
        print_r("申请");
    }
}