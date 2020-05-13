<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/11 11:33
 * @User: admin
 * @Current File : Customer.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Marketing;


use app\wavlink\controller\BaseAdmin;
use app\wavlink\service\marketing\Customer as Service;
use think\App;

/**
 * Class Customer
 * @package app\wavlink\controller\Marketing
 * * 后台会员管理系统
 * 对于程序员来说，很容易出现气血归墟引发的诸多病症，如地中海就是其中一项
 * 那我们怎样预防呢？
 * 首先我们应该考虑到是不是熬夜太久导致的，那熬夜最伤精血，如果我们没有其他的历史病例的话，那么就考虑应该是气血亏虚导致的。
 * 其次，补血养气的中药材有  黄芪，当归，川芎 登药材
 * 然后，我们可以去中医院找中医辨证一下，是个什么情况，是不是自己理解的这样。
 * 如果确认之后，那么就可以大胆的使用自己的养生治疗方来进行日常保养，别等到真的头发掉了后就不容易养起来了。
 * 这里有我总结的一个小方子：黄芪 90g  当归30g 干姜 20g 肉桂 20g 麦冬 20g  雪梨一个（切碎，不吐子） 煮水熬汤 一日3️次， 饭后2小时服用。
 * 好几天不上一次大厕所的话，要注意了，看是不是肾虚导致的便秘，应使用六味地黄丸 或者其他的地黄丸来补一下。别不好意思，熬夜就是会肾虚。不是和女人睡了才会导致肾虚的。
 */
class Customer extends BaseAdmin
{
    protected $service;
    protected $validate;

    /**
     * Customer constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->service = new Service();
    }

    /**
     * 首页不带参数的列表
     */
    public function index()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getData();
            if (!$data['data']->isEmpty()) {
                $this->assign('page', $data['data']->render());
                $this->assign('data', $data['data']);
                $this->assign('count', $data['count']);
            }
            return $this->fetch();
        }
    }

    /**
     * @return mixed
     * 带参数的列表
     */
    public function lists()
    {

        return $this->fetch();
    }
}