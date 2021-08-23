<?php

namespace app\en_us\controller;


class Social extends Base
{
    public function index()
    {
        return $this->fetch($this->template . '/social/index.html');
    }
    public function list()
    {
        # code...
        return $this->fetch($this->template . '/social/list.html');
    }
    function details()
    {
        # code...
        return $this->fetch($this->template . '/social/details.html');
    }
    function gain()
    {
        return $this->fetch($this->template . '/social/gain.html');
    }
}