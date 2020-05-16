<?php
/**
 * Created by PhpStorm.
 * User: web
 * Date: 2018/6/1
 * Time: 14:44
 */
namespace app\wavlink\controller;
use app\common\helper\Search;
use app\common\helper\Excel;
class Big extends BaseAdmin
{
//    后台列表
    public function index()
    {

        $data = Search::search('BigData','','');
        return $this->fetch('', [
            'result' => $data['data'],
            'count' => $data['count']
        ]);
    }

    public function export()
    {

        $excel = new Excel();
        $table_name = "winstars";
        $field = ['id' => '序号', 'first_name' => '名','last_name'=>'姓','email' => '客户邮箱', 'country' => '所在国家','interested'=>"感兴趣的产品类别" ];
        $excel->setExcelName('展会数据')
            ->createSheet('展会数据', $table_name, $field)
            ->downloadExcel();
    }
}