<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/25
 * Time: 17:40
 */
namespace app\common\model;

use app\common\model\BaseModel;
use think\Cookie;
use think\Model;

Class System extends BaseModel
{
    public function saveSystem($data) {
        $configtxt = file_get_contents(EXTRA_PATH . '/system.php');
        $system = config('system.system');
//        $configtxt = str_replace("'page'=>'{$system['page']}'", "'page'=>'{$data['page']}'", $configtxt);
        $configtxt = str_replace("'cache'=>'{$system['cache']}'", "'cache'=>'{$data['cache']}'", $configtxt);
        Cookie::set('systemPage',$data['page'],3600);
        file_put_contents(EXTRA_PATH . '/system.php', $configtxt);
        return true;
    }
}


