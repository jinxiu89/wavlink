<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *内容管理之 文章管理验证规则。
 */
namespace app\wavlink\validate;

class Images extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        ['id','number','id不合法'],
//        ['title','max:64','图片标题不要太长'],
//        ['subtitle','max:64','副标题太长了'],
        ['listorder','isPositiveInteger','排序必须是正整数'],
        ['product_title','max:100','标题太长了'],
        ['image_pc_url','max:500','PC端图外链地址太长了'],
        ['image_mobile_url','max:500','移动端图外链地址太长了'],
        ['product_type','max:64','产品型号太长了'],
        ['language_id','number','语言不能为空'],
        ['featured_id','require|featuredLimit','选择推荐位|该推荐位的产品太多了，请先下架再添加'],
        ['url','max:255','链接不能太长'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
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
    protected function featuredLimit($value, $rule = '', $data = '', $field = '') {
        if (!empty($data['id'])){
            return true;
        }else{
            $map = [
                'language_id' => $data['language_id'],
                'featured_id' => $value,
                'status'      => 1
            ];
            $con = request()->controller();
            $count = model($con)->where($map)->count();
            if($value == 1){
                return true;
            }elseif ($value == 2 && $count<1){
                return true;
            }elseif ($value == 3 && $count < 4 ){
                return true;
            }elseif ($value == 4 && $count > 1){
                return true;
            }elseif ($value == 5 && $count < 3 ){
                return true;
            }elseif ($value == 6 && $count < 2){
                return true;
            }else{
                return false;
            }
        }
    }
}
