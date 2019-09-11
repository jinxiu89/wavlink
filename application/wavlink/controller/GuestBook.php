<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/4
 * Time: 14:27
 * edit :qiujin
 * Time : 2018-11-12
 */

namespace app\wavlink\controller;

use app\common\helper\Excel;
use app\common\model\GuestBook as GuestBookModel;

class GuestBook extends BaseAdmin
{
    //获取未处理的固件信息,status = -1
    public function index()
    {
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));
        $ticket = GuestBookModel::getDataByStatus(-1, $language_id);
        foreach ($ticket['data'] as $k => $v) {
            $v['desc'] = cut_str($v['description'], 20, 0);
        }
        return $this->fetch('', [
            'ticket' => $ticket['data'],
            'count' => $ticket['count'],
            'language_id' => $language_id
        ]);
    }

    //获取已经处理的固件信息
    public function index_off()
    {
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));
        $ticket = GuestBookModel::getDataByStatus(1, $language_id);
        return $this->fetch('', [
            'ticket' => $ticket['data'],
            'language_id' => $language_id
        ]);
    }

    //查看客户提交的详细信息
    public function look($id)
    {
        $id = $this->MustBePositiveInteger($id);
        $ticket = GuestBookModel::get($id);
        return $this->fetch('', [
            'ticket' => $ticket
        ]);
    }

    //回复内容
    public function reply($id)
    {
        $id = $this->MustBePositiveInteger($id);
        $ticket = GuestBookModel::get($id);
        if ($ticket['status'] !== -1) {
            $this->error('已经回复了');
        } else {
            return $this->fetch('', [
                'ticket' => $ticket,
            ]);
        }

    }

    public function reply_look($id)
    {
        $id = $this->MustBePositiveInteger($id);
        $ticket = GuestBookModel::get($id);
        return $this->fetch('', [
            'ticket' => $ticket,
        ]);
    }

    //导出表格
    public function export()
    {
        $data = input('get.');
        $excel = new Excel();
        $table_name = "tb_guest_book";
        if ($data['status'] == -1) {
            //如果没有选择哪种语言，就全部导出表格
            if (empty($data['language_id'])) {
                $map = ['status' => -1];
                $language_name = '全部';
            } else {
                //如果选择了哪种语言，就把该语言的表格给导出来
                $language_name = getLanguage($data['language_id']);
                $map = [
                    'status' => -1,
                    'language_id' => $data['language_id']
                ];
            }
            $field = ['id' => '序号', 'first_name' => '称呼', 'email' => '客户邮箱', 'model' => '产品型号', 'sn' => '产品SN', 'description' => '问题描述'];
            $excel->setExcelName('下载未处理留言')->createSheetByModel('', $model_name = "GuestBook", $field, $map)->downloadExcel();

        }
        if ($data['status'] == 1) {
            $map = ['status' => 1, 'language_id' => $data['language_id']];
            $field2 = ['id' => '序号', 'first_name' => '称呼', 'email' => '客户邮箱', 'model' => '产品型号', 'sn' => '产品SN', 'description' => '问题描述', 'content' => '回复内容'];
            $excel->setExcelName('下载已处理留言')->createSheetByModel('', $model_name = "GuestBook", $field2, $map)->downloadExcel();
        }
    }

    //发送邮件

    /***
     *
     */
    public function send()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $to = $data['email'];
            try {
                $res = sendMail($to, $data['last_name'], $data['subject'], $data['content']);
            } catch (\phpmailerException $e) {
                $this->error($e->getMessage());
            }
            if ($res) {
                model("GuestBook")->where('id', $data['id'])->update(['status' => 1, 'subject' => $data['subject'], 'content' => $data['content']]);
                return show(1, 'success', '', '', '', '发送成功');
            } else {
                return show(0, 'error', '', '', '', '发送失败');
            }
        }
    }
}