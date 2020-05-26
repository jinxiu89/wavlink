<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2018/1/2
 * Time: 14:55
 */

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Service\GuestBook as GuestBookModel;
use app\en_us\validate\Ticket;
use app\wavlink\service\service\driversCategory as service;
use think\App;


class GuestBook extends Base
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $data = (new service())->getDataByLanguageId($status = 1, $this->language_id);
        $level = Category::toLevel($data['data']->toArray()['data'], '&emsp;&emsp;');
        $this->assign('cate', $level);
    }

    public function index()
    {
        return $this->fetch();
    }

    public function add()
    {
        return $this->fetch($this->template . '/guest_book/add.html');
    }

    public function detail($sn)
    {
        /***
         *
         */
        return $sn;
    }

    public function save()
    {
        $data = input('post.', '', 'htmlentities');
        $validate = new Ticket();
        if ($validate->scene('add')->check($data)) {
            $ticketAdd = new GuestBookModel();
            try{
                if ($ticketAdd->addTicket($data, $this->code)) {
                    return show(1, '', '', '', '', lang('ticket success'));
                } else {
                    return show(0, '', '', '', '', lang('ticket error'));
                }
            }catch (\Exception $exception){
                return show(0, '', '', '', '', $exception->getMessage());
            }
        } else {
            return show(0, '', '', '', '', $validate->getError());
        }
    }
}