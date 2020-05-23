<?php

use app\common\model\Content\Featured;
use app\common\model\Language;
use app\common\model\Service\Manual;
use app\common\model\Content\Product;
use app\common\model\Service\ServiceCategory;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;
use app\common\model\Service\DriversCategory;

//分类所属分类
function getCategory($id)
{
    $model = request()->controller();
    $id = intval($id);
    if (empty($id)) {
        return '<span style="color: blue">这是一级分类</span>';
    }
    $category = model($model)->find($id);
    return '<span style="color: blue">' . $category['name'] . '</span>';
}

//产品管理类获取所属分类
function getChild($id)
{
    $id = intval($id);
    $data = Product::getCategoryWithProduct($id);
    $name = [];
    foreach ($data as $v) {
        $name[] = $v['name'];
    }
    $nameStr = implode(">", $name);
    return $nameStr;
}

/**
 * GetNameByParentId 根据产品分类的ID来获得他的分类名称
 * @param $id
 * @return mixed
 */
function GetNameByParentId($id){
    $data=\app\common\model\Content\Category::get($id);
    return $data->name;
}

//首页图片管理获取分类
function featured($id)
{
    $ids = intval($id);
    $data = Featured::get($ids);
    $name = $data['name'];
    if ($ids == 1) {
        $str = "<span style='color:red'>$name</span>";
    } elseif ($ids == 2) {
        $str = "<span  style='color:blue'>$name</span>";
    } elseif ($ids == 3) {
        $str = "<span  style='color: orange'>$name</span>";
    } elseif ($ids == 4) {
        $str = "<span style='color: pink'>$name</span>";
    } else {
        $str = "<span style='color: green'>$name</span>";
    }
    return $str;
}

//得到语言id获取语言名称，并改变其字体颜色
function getLanguage($id)
{
    $map = intval($id);
    $data = Language::get($map);
    $name = $data['name'];
    if ($map == 1) {
        $str = "<span style='color:blue;'>$name</span>";
    } elseif ($map == 2) {
        $str = "<span style='color:red;'>$name</span>";
    } else {
        $str = "<span style='color:orange;'>$name</span>";
    }
    return $str;
}

//根据语言id，单纯的获取语言名称，
function getLanguageOne($id)
{
    $map = intval($id);
    $data = Language::get($map);
    return $data['name'];
}

//首页图片管理获取类别
function imagesType($id)
{
    $ids = intval($id);
    $type = Config('images.images_type');
    foreach ($type as $k => $v) {
        if ($ids == $k) {
            return $v;
        }
    }
}

//固件处理状态信息
function ticket_status($status)
{
    if ($status == 1) {
        $str = "<span class='label label-success radius'>已处理</span>";
    } elseif ($status == 0) {
        $str = "<span class='label label-error radius'>垃圾信息</span>";
    } else {
        $str = "<span class='label label-danger radius'>未处理</span>";
    }
    return $str;
}

function GetStatus($status)
{
    if ($status == 1) {
        $str = '<span class="label label-success radius">正常</span>';
    } else {
        $str = '<span class="label label-danger radius">禁用</span>';
    }
    return $str;
}


/**
 * 记录数查询
 * 用于排序操作
 * @param $table
 * @param $map
 * @param $order
 * @param $limit
 * @param $field
 * @param $map2
 * @return array|false|PDOStatement|string|Model
 * @throws DataNotFoundException
 * @throws ModelNotFoundException
 * @throws DbException
 */
function limit($table, $map, $order, $limit, $field, $map2)
{
    $_listorder = model($table)->where($map)
        ->where($map2)
        ->field($field)
        ->order($order)
        ->limit($limit)
        ->select();
    $listorder = Collection::make($_listorder);

    if (!empty($listorder)) {
        return $listorder;
    } else {
//        return show(0,'已经是置顶或者置底了，移动它的位置请上移或者下移，或者直接修改排序','');
        return false;
    }
}

/**
 * 获取某个目录下的php文件名的函数
 */
function getControllers($dir)
{
    $pathList = glob($dir . '/*.php');
    $res = [];
    foreach ($pathList as $key => $value) {
        $res[] = basename($value, '.php');
    }
    return $res;
}

/**
 * 获取某个控制器的方法名的函数
 * 过滤父级Base控制器的方法，只保留自己的
 */
function getActions($className, $base = '/app/wavlink/controller/BaseAdmin')
{
    $methods = get_class_methods(new $className());
    $baseMethods = get_class_methods(new $base());
    $res = array_diff($methods, $baseMethods);
    return $res;
}

/**
 * 删除缓存文件
 * @param $path
 * @param bool $diedir
 * @return string
 */
function delcache($path, $diedir = false)
{
    $message = "";
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$path/$item")) {
                    $msg = delcache("$path/$item", $diedir);
                    if ($msg) {
                        $message .= $msg;
                    }
                } else {
                    $message .= "删除文件" . $item;
                    if (unlink("$path/$item")) {
                        $message .= "成功<br />";
                    } else {
                        $message .= "失败<br />";
                    }
                }
            }
        }
        closedir($handle);
        if ($diedir) {
            if (rmdir($path)) {
                $message .= "删除目录" . dirname($path) . "<br />";
            } else {
                $message .= "删除目录" . dirname($path) . "失败<br />";
            }
        }
    } else {
        if (file_exists($path)) {
            if (unlink($path)) {
                $message .= "删除文件" . basename($path) . "<br />";
            } else {
                $message .= "删除文件" . basename($path) . "失败<br />";
            }
        } else {
            $message .= "文件" . basename($path) . "不存在<br />";
        }
    }
    return $message;
}

/***
 * 根据说明书的ID 获取他的title
 */
function getManualName($id)
{
    return Manual::get(intval($id))['title'];
}

/**
 * @param $category_id
 * @return mixed
 */
function getUrlTitleByCategoryId($category_id)
{
    $data = ServiceCategory::get($category_id);
    return $data['url_title'];
}

/***
 * 添加时间：2019-03-05
 * 添加人：kevin qiu
 * 邮箱：jinxiu89@163.com
 * 功能：利用Phpexcel 导出 2007版本的excel文件
 * 参数说明：
 * $data：需要导出的数据数组
 * $file_name：文件名
 * $sheet_name:工作簿的名字
 *
 *
 */
function exportExcel($data = array(), $file_name = '', $sheet_name = 'sheet1')
{

}

/**
 * @param $parent_id
 */
function getDriverCate($parent_id){
    if($parent_id == 0) return "根分类";
    try{
        $data=(new DriversCategory())->field('name')->get($parent_id);
        return  $data->toArray()['name'];
    }catch (Exception $exception){
        return  "有错误";
    }

}

/**
 * @param $path
 * @return string
 * 根据路径来重新组装分类层级
 * 使用功能有 各种分类的层级操作
 */
function getPath($path){
    $categorys=array_filter(explode('-',$path));
    $str='';
    foreach ($categorys as $v){
       $str=$str.$v.'-';
    }
    return $str;
}

