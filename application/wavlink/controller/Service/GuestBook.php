<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/4
 * Time: 14:27
 * edit :qiujin
 * Time : 2018-11-12
 */

namespace app\wavlink\controller\Service;


use app\common\helper\Category;
use app\common\helper\Excel;
use app\common\model\Service\GuestBook as GuestBookModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\service\service\driversCategory as service;
use PHPMailer\PHPMailer\Exception;
use think\App;
use think\exception\PDOException;


/**
 * Class GuestBook
 * @package app\wavlink\controller
 *
 */
class GuestBook extends BaseAdmin
{
   public function __construct(App $app = null)
   {
       parent::__construct($app);
   }

    //获取未处理的固件信息,status = -1
    public function index()
    {
        $data = (new service())->getDataByLanguageId($status = 1, $this->currentLanguage['id']);
        $level = Category::toLevel($data['data']->toArray()['data'], '&emsp;&emsp;');
        $this->assign('cate', $level);
        $ticket = GuestBookModel::getDataByStatus(-1, $this->currentLanguage['id']);
        foreach ($ticket['data'] as $k => $v) {
            $v['desc'] = cut_str($v['description'], 20, 0);
        }
        return $this->fetch('', [
            'ticket' => $ticket['data'],
            'count' => $ticket['count'],
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    //获取已经处理的固件信息

    /**
     * @return mixed
     */
    public function index_off()
    {
        $ticket = GuestBookModel::getDataByStatus(1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'ticket' => $ticket['data'],
            'language_id' => $this->currentLanguage['id']
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
        if ($data['status'] == -1) {
            //如果没有选择哪种语言，就全部导出表格
            $field = 'id,first_name,last_name,email,model,sn,description';
            $data = (new GuestBookModel())->getDataByLanguage($data['status'], $this->currentLanguage['id'], $field);
            $label = ['id' => '序号', 'first_name' => '称呼', 'last_name' => '名字', 'email' => '客户邮箱', 'model' => '产品型号', 'sn' => '产品SN', 'description' => '问题描述'];
            try {
                $excel->setExcelName('下载未处理留言')->createSheet('未回复', $data, $label)->downloadExcel();
            } catch (\PHPExcel_Exception $e) {
                $this->error($e->getMessage());
            } catch (\think\Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
        if ($data['status'] == 1) {
            $field = 'id,first_name,last_name,email,model,sn,description,content';
            $label = ['id' => '序号', 'first_name' => '称呼', 'last_name' => '名字', 'email' => '客户邮箱', 'model' => '产品型号', 'sn' => '产品SN', 'description' => '问题描述', 'content' => '回复内容'];
            $data = (new GuestBookModel())->getDataByLanguage($data['status'], $this->currentLanguage['id'], $field);
            try {
                $excel->setExcelName('已处理')->createSheet('已恢复', $data, $label)->downloadExcel();
            } catch (\PHPExcel_Exception $exception) {
                $this->error($exception->getMessage());
            } catch (\think\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    //发送邮件

    /**
     * @return void
     * @throws PDOException
     * @throws \think\Exception
     */
    public function send()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $to = $data['email'];
            try {
                $res = sendMail($to, $data['last_name'], $data['subject'], $data['content']);
                if ($res) {
                    (new GuestBookModel())->where('id', $data['id'])->update(['status' => 1, 'subject' => $data['subject'], 'content' => $data['content']]);
                    return show(1, 'success', '', '', '', '发送成功');
                } else {
                    return show(0, 'error', '', '', '', $res);
                }
            } catch (Exception $exception) {
                return show(0, 'error', '', '', '', $exception->getMessage());
            }
        }
    }
}