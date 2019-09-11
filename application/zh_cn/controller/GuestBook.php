<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/7
 * Time: 17:03
 */

namespace app\zh_cn\controller;

use app\common\model\GuestBook as GuestBookModel;
use app\common\validate\OtherInformation as OtherInformationValidate;
use app\common\validate\ProductInformation as ProductInformationValidate;
use app\common\validate\UserInformation as UserInformationValidate;

class GuestBook extends Base
{
    public function index()
    {
        return $this->fetch($this->template.'/guest_book/index.html');
    }

    public function add()
    {
        if (counter('cn_', 600)) {
            return $this->fetch($this->template.'/guest_book/add.html');
        } else {
            return $this->fetch($this->template.'/guest_book/error.html');
        }
    }

    public function detail($sn)
    {
        return $sn;
    }

    public function save()
    {
        (new UserInformationValidate())->goCheck();
        (new ProductInformationValidate())->goCheck();
        (new OtherInformationValidate())->goCheck();
        $ticketAdd = new GuestBookModel();
        $data = input('post.', '', 'htmlentities');
        if ($ticketAdd->addTicket($data, $this->code)) {
            return show(1, '', '', '', '', lang('ticket_success'));
        } else {
            return show(0, '', '', '', '', lang('ticket_error'));
        }
    }
}