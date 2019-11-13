<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller;
/**
 * Class Admin
 * @package app\wavlink\controller
 */
Class Admin extends BaseAdmin
{
    /**
     * 清空缓存
     */
    public function clean()
    {
        echo "<span style='color: red;'>缓存清理中……</span> <br/><br/>";
        $path = RUNTIME_PATH;
        echo delcache($path);
        echo "<br/><span style='color: red;'>缓存清理完毕。</span>";
    }
}