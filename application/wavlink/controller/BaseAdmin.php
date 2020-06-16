<?php
/**公共控制器
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/10/31
 * Time: 10:22
 */

namespace app\wavlink\controller;

use app\common\model\BaseModel;
use app\common\model\Language as LanguageModel;
use Exception;
use think\App;
use think\captcha\Captcha;
use think\Controller;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\response\Redirect;
use app\wavlink\controller\System\Auth;

class BaseAdmin extends Controller
{
    protected $currentLanguage;
    protected $currentUser;
    protected $version;
    protected $beforeActionList = [
        'isLogin', 'Auth'
    ];

    /**
     * BaseAdmin constructor.
     * @
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->assign('currentLanguage',$this->currentLanguage);
        $this->assign('version',Config::get('app_version'));
    }

    /***
     */
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 清空缓存
     */
    public function clean()
    {
        echo "<span style='color: red;'>缓存清理中……</span> <br/><br/>";
        $path = RUNTIME_PATH;
        echo delcache($path);
        echo "<br/><span style='color: red;'>缓存清理完毕。</span>";
    }

    /***
     * //判定是否登录
     * 20190911
     *
     */
    public function isLogin()
    {
        if (Session::has('userName', 'admin')) {
            $userSession = session('userName', '', 'admin');
            $this->currentLanguage = session('current_language', '', 'admin');
            $mangerName = $userSession['name'];
            $username = $userSession['username'];
            $this->assign('mangerName', $mangerName);
            $this->assign('username', $username);
            $this->currentUser = $userSession;
            $this->assign('session', $userSession);
        } else {
            $next = Request::instance()->url(true);
            $this->redirect(url('admin_login', ["next" => $next]));
        }
    }

    public function Auth()
    {
        $userSession = session('userName', '', 'admin');
        $mangerName = $userSession['name'];
        $username = $userSession['username'];
        $this->assign('mangerName', $mangerName);
        $this->assign('username', $username);
        $auth = new Auth();
        $request = Request::instance();
        $con = $request->controller();
        $action = $request->action();
        $name = $con . '/' . $action;
        $uid = $userSession['id'];
        $notCheck = ['Index/index', 'Content/index', 'Service/index', 'System/index'];//对一些（控制器/方法）不需要验证
        //权限部分
        //获取全部语言
        $languages = LanguageModel::all(['status' => 1]);//待清理
        $rules = $auth->getAllAuth();
        $this->assign('access', $rules);
        /*if ($uid != 1) {
            $_access = $auth->getAuthById($uid);
            $this->assign('access', $_access);
            if (!in_array($name, $notCheck)) {
                if (!$auth->check($name, $uid)) {

                    return show(0, 'Sorry!没权限操作', '', '', $this->request->header('http-referer'), '');
//                    $this->error('Sorry,没有权限.', url('index/index'));
                }
            }
            $language_ids = $userSession->language_id;//待清理
            $languages = LanguageModel::getLanguageByIDs($language_ids);//待清理
        } else {
            //获取全部语言
            $languages = LanguageModel::all(['status' => 1]);//待清理
            $rules = $auth->getAllAuth();
            $this->assign('access', $rules);
        }*/
        $this->assign('uid', $uid);
        $this->assign('languages', $languages);//这个是后台切换语言时的数组
    }


    /**
     * 编辑后更新数据操作
     * @param $data
     * @return array
     */
    public function update($data)
    {

        //自动获取对应控制器
        $model = request()->controller();
        $res = model($model)->allowField(true)->save($data, ['id' => $data['id']]);
        if ($res) {
            return show(1, "success", '', '', '', '操作成功');
        } else {
            return show(0, 'error', '', '', '操作失败');
        }
    }

    //置顶，上移，下移，置顶操作。需要的数据 type操作类型，语言id，需要移动的数据id
    public static function order($data, $map2 = '')
    {
        $con = request()->controller();
        $res = BaseModel::listorder($data, $con, $map2);
        $url = $_SERVER['HTTP_REFERER'];
        if ($res == 1) {
            return show(1, '', '', '', $url, '操作成功');
        } elseif ($res == 0) {
            return show(0, 'error', '', '', '', '操作失败');
        } elseif ($res == -1) {
            return show(-1, 'error', '', '', '', '嗷呜~到极限了哇！,点别的。');
        } else {
            return show(0, 'error', 'error', '', '', '操作失败');
        }
    }

    //严格校验 传递的参数必须是正整数
    //防止在地址栏随意修改参数
    protected function MustBePositiveInteger($value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return $value;
        }
        $this->error('别乱改参数');
    }

    //批量更新listorder
    public function allRecycle(Request $request)
    {
        $ids = $request::instance()->post();
        if (!is_array($ids)) {
            return show(0, '', '', '', '', '数据错误，没有选择');
        }
        $con = request()->controller();
        try {
            foreach ($ids as $k => $v) {
                if (model($con)->get($k)) {
                    model($con)->where('id', $k)->update(['status' => -1]);
                } else {
                    return show('0', 'error', '', '', '', '操作失败');
                }
            }
            return show(1, 'success', '', '', '', '批量回收成功');
        } catch (Exception $e) {
            return show(0, 'error', '', '', '', $e->getMessage());
        }
    }

    /**
     * @param $code
     * @return Redirect
     * @throws \think\Exception
     */
    public function ChangeLanguage($code)
    {
        $result = (new LanguageModel())->getLanguageByLanguageId($code);
        $next = Request::header('referer');
        if(strpos($next,'/product/index') == true){
            /**特殊情况：但用户进产品筛选后，切换语言，返回产品结果集为空，原因是
            category_id 语言有别，在中文里有，在英文里就没有这个id
            **/
            $next='/wavlink/product/index.html';
        }
        session('current_language', $result[0], 'admin');
        return redirect($next);
    }

//    /**
//     * @return \think\Response
//     * 后台简化验证码
//     */
//    public function genCode(){
//        $captcha=new Captcha(Config::get('verify.config'));
//        return $captcha->entry();
//    }
}