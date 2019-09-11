<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/16
 * Time: 10:26
 */

namespace app\customer\controller;
use app\common\model\Ticket as TicketModel;

class Ticket extends Base
{
    public function index()
    {
        $id = session('CustomerInfo', '', 'Customer');
        $result=(new TicketModel())->getDataByUserId($id);
        return $this->fetch('',[
            'result'=>$result
        ]);
    }
    public function add($user_id,$sn){
        $map=array(
          'user_id'=>$user_id,
          'sn'=>$sn,
        );
        $warranty=new \app\common\model\Warranty();
        $warranty=$warranty->where($map)->find();
        return $this->fetch('',[
            'warranty'=>$warranty
        ]);
    }
    public function addTicket(){

        return $this->fetch();
    }
    public function save(){
        if(request()->isAjax()){
            $data=input('post.');
            $data['create_time']=date('Y-m-d',time());
            $model=new TicketModel();
            if($model->where(['user_id'=>$data['user_id'],'product_id'=>$data['product_id']])->find()){
                return show(0, '', '', '', '', lang('同一个产品有一张保修申请单没有完成处理，请先完成上一次保修的项目再来提交新的保修'));
            }
            if((new TicketModel())->allowField(true)->save($data)){
                return show(1, '', '', '', '', lang('Success'));
            }else{
                return show(0, '', '', '', '', lang('Unknown Error'));
            }
        }
    }
}