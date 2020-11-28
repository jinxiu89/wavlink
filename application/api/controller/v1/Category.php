<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/11/28 15:59
 * @User: kevin
 * @Current File : Category.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\api\controller\v1;

use app\api\controller\Base;

/**
 * Class Category
 * @package app\api\controller\v1
 */
class Category extends Base
{
    /**
     *
     */
    public function index()
    {
        return json(['status' => 200, 'message' => 'ok', 'data' => ['id' => 1, 'name' => 'kevin.qiu', 'age' => 36]]);
    }

    /**
     * @param $id
     */
    public function read($id)
    {

    }

    /**
     * @param $id
     */
    public function edit($id)
    {

    }
}