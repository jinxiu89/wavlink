<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/14
 * Time: 11:19
 */

namespace app\customer\controller;

use app\common\model\Content\About as AboutModel;
use app\lib\utils\email;
use app\lib\utils\sms;
use app\lib\utils\tools;
use Exception;
use think\App;
use think\Controller;
use think\facade\Cache;
use think\facade\Cookie;
use think\facade\Lang;
use app\lib\utils\cloud\ali;

class Base extends Controller
{
    protected $service;
    protected $validate;
    protected $uid;
    protected $username;
    protected $code;

    public $beforeActionList = ['about'];

    /**
     * 当 initialize 和_construct 同时存在时 先运行的是initialize
     * 这与_initialize 不同，如果用带下划线的initialize 需要 在_construct里加$this->_initialize来执行官优先顺序
     *
     */
    public function initialize()
    {
        $default = parseDefaultLanguage($this->request->header('accept-language'));
        if (strpos($default, 'zh') !== false) {
            Cookie::set('lang_var', 'zh_cn');
        }
        if (strpos($default, 'en') === false) {
            Cookie::set('lar_var', 'en_us');
        }
    }

    /**
     * 构造函数，用来注册全局变量，供整个系统使用（这里指customer这个模块）,每个控制器都继承到base控制器
     * @param App|null $app
     * 处理问题一般是一件非常高惹人痛苦的事情，我们每天都在一个问题和另一个问题之间来回窜动。
     * 发现问题：出了什么问题？谁发现的？是个什么背景下出现的什么问题？
     * 分析问题：这是个什么问题？为什么只有这个独特的场景下有这个问题？有什么办法来解决这个问题呢？列出一二三来！
     * 解决问题：根据分析出来的问题，一步一步的处理并反馈问题是否还会出现！再次走同样的步骤，直到解决问题为止。
     * 为什么程序员30岁就是去竞争力了呢？
     * 因为自己平时只懂得写代码，不足以自己其他方面的只是的积累，身体是去优势，加不了班，脾气不好，和人处不来等都是问题
     * 分析问题：只懂写代码是不行的，需要从产品，运营，商业几个角度来辅助自己的成长，学会架构知识，懂得应用产品运营商业几个角度获得的启发来做决策。
     * 当然写代码是硬技能，如果不够硬，就需要把他变硬，虚心求教。身体不行了，怎么办？锻炼，养生，休息，读书都是办法。
     * 解决问题：我们分析得到，我们专业不够硬，身体不够好，知识不够广，我们应该从哪一个切入点着手呢？第一，就是要坐得住，沉得住气。沉得住气的话就需要有一个好身体，一般养生和锻炼能够实现他。
     * 我们不应该上班时玩手机，玩手机的时候想着工作，睡觉的时候刷视频看笑话。这说明我们应该保持一定的作息时间，不要想过多的事情，让大脑充分释放休息。能够让我们能 在某一个领域坚持久一点。
     * 这样周而复始的解决问题，就会使我们越来越厉害，身体不垮，一切都能拥有更多的变数。
     * 我的第一不是养生，然后再锻炼，三是不要操心过多的事情，专心做好当下手头的这一件事情。我在程序里隐藏了很多养生秘方，等待伙计们发掘。也留了很多我学到的新知识在各个代码注释里，我立求把代码写的够精炼和注释清楚的同时，
     * 把我新的理解和所悟写到注释的下面
     *
     *
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $lang = Cookie::get('customer_lang') ? Cookie::get('customer_lang') : 'en_us';
        Lang::load(APP_PATH . 'customer/lang/' . $lang . '.php'); //加载该语言下的模块语言包
        $this->code = $lang;
        $user = session('CustomerInfo', '', 'Customer');
        if (isset($user) and !empty($user)) {
            $this->uid = $user['id'];
            $this->username = $user['username'];
            $this->assign('id', $this->uid);
            $this->assign('username', $this->username);
        }
        $this->assign('lang', $lang);
    }


    /**
     */
    public function about()
    {
        try {
            $about = (new AboutModel())->getAbouts($this->code);
            if ($about) $this->assign("about", $about['data']);
        } catch (Exception $exception) {
            $this->assign('about', []);
        }
    }


    /**
     * @return bool
     */
    public function isLogin()
    {
        //获取session
        $customer = session('CustomerInfo', '', 'Customer');
        if ($customer) {
            return true;
        }
        return false;
    }

    /**
     * sendVerification 发送验证码
     * 传递参数说明：1.phone: 手机注册 2.email 注册；如果email和phone 都传递过来，则走email注册
     */
    public function sendVerification()
    {
        $str = tools::GetIntStr(6);
        if (!empty($email = input('email'))) { /*邮件验证分支*/
            try {
                Cache::store('redis')->set($email, $str, 300);
            } catch (Exception $exception) {
                Cache::store('default')->set($email, $str, 300);
            }
            $dear = lang('Dear');
            $welcome = lang('Welcome to wavlink');
            $noreplay = lang('noreplay');
            $support = lang('Wavlink Support');
            $message = lang('Your verification code is') . ' ' . $str;
            $content = "<div>
                    <p><strong>$dear</strong></p>
                    <p>$welcome</p>
                    <p>$message</p>
                    </div>
                    <div style=\"border-top:1px solid #ccc;padding:10px 0;font-size:12px;margin:20px 0;color:#A0A0A0;\" >
                    <p>$noreplay</p>
                    <b>$support</b>
                    <p></p><img src=\"https://s3.amazonaws.com/files.wavlink.com/images/logo.png\" alt=\"https://www.wavlink.com\",height=\"49px\" width=\"138x\"/>
                    </div>";
            $subject = lang('verification your register');
            $result = email::sendMail($email, '', $subject, $content);
            if ($result) {// 根据结果让按钮阶段性的失去点击能力
                return show(1, lang('Send Mail is Success'), '', '', '', lang('Message Send Successfully!'));
            } else {
                return show(0, lang('Send Error'), '', '', '', lang('Message Send Failed!'));
            }
        } elseif (!empty($phone = input('phone'))) {
            try {
                Cache::store('redis')->set($phone, $str, 300);
            } catch (Exception $exception) {
                Cache::store('default')->set($phone, $str, 300);
            }
            $result = sms::ali($phone, $str);
            if ($result) { // 根据结果让按钮阶段性的失去点击能力
                return show(1, lang('Send Mail is Success'), '', '', '', lang('Message Send Successfully!'));
            } else {
                return show(0, lang('Send Error'), '', '', '', lang('Message Send Failed!'));
            }
        }
    }

    public function sendCode(){

    }

    public function listObj()
    {
        $prefix = !empty(input('get.prefix', '', 'htmlspecialchars,trim')) ? input('get.prefix', '', 'htmlspecialchars,trim') . '/' : 'videos/';
        $items = ali::listObj('wavlink', $prefix);
        $key = array_search($prefix, $items);
        array_splice($items, $key, 1);
        print_r($items);
    }
}