<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *内容管理之 文章管理验证规则。
 */

namespace app\wavlink\validate;
/**
 * Class Images
 * @package app\wavlink\validate
 */
class Images extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'listorder' => 'number',
        'product_title' => 'max:100',
        'image_pc_url' => 'max:500',
        'image_mobile_url' => 'max:500',
        'product_type' => 'max:64',
        'language_id' => 'number',
        'featured_id' => 'require|featuredLimit',
        'url' => 'max:255',
        'status' => 'integer|in:-1,0,1',
    ];
    protected $message = [
        'id' => 'ID不合法',
        'listorder' => '排序值不合法',
        'product_title' => '产品标题不能超过100个字符',
        'image_pc_url' => '图片地址不能够超过500个字符',
        'image_mobile_url' => '移动端图片不能操作500个字符',
        'product_type' => '类型不能超过64个字符',
        'language_id' => '语言ID不合法',
        'featured_id.require' => '推荐位不能为空',
        'featured_id.featuredLimit' => '推荐位不能超过个数',
        'url' => 'url不能超过255个字符',
        'status' => '状态值不在合法范围内'
    ];
    protected $scene = [
        'add' => ['listorder', 'product_title', 'image_pc_url', 'image_mobile_url', 'product_type', 'featured_id', 'url', 'status'],
        'edit' => ['id', 'listorder', 'product_title', 'image_pc_url', 'image_mobile_url', 'product_type', 'featured_id', 'url', 'status'],
        'changeStatus'=>['id','status'],
        'listorder'=>['id','listorder'],
        'del'=>['id'],
    ];

    /**
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * 验证推荐位的正常产品数
     * 幻灯片不限产品
     * 第二屏热卖限一个产品
     * 第三屏主流限4个产品
     * 第四屏新品限一个产品
     * 推荐位限3个产品
     * 公告推荐位一个正常产品
     *
     * @return bool
     */
    protected function featuredLimit($value, $rule = '', $data = '', $field = '')
    {
        if (!empty($data['id'])) {
            return true;
        } else {
            $map = [
                'language_id' => $data['language_id'],
                'featured_id' => $value,
                'status' => 1
            ];
            $con = request()->controller();
            $count = model($con)->where($map)->count();
            if ($value == 1) {
                return true;
            } elseif ($value == 2 && $count < 1) {
                return true;
            } elseif ($value == 3 && $count < 4) {
                return true;
            } elseif ($value == 4 && $count > 1) {
                return true;
            } elseif ($value == 5 && $count < 3) {
                return true;
            } elseif ($value == 6 && $count < 2) {
                return true;
            } else {
                return false;
            }
        }
    }
}
