<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;


Class Admin extends BaseAdmin
{
    /**
     * 清空缓存
     */
    public function clean()
    {
        echo "<span style='color: red;'>缓存清理中……</span> <br/><br/>";
        $path1 = RUNTIME_PATH . "cache/";
        echo delCache($path1);
        $path2 = RUNTIME_PATH . "temp/";
        echo delCache($path2);
        echo "<br/><span style='color: red;'>缓存清理完毕。</span>";
    }
}