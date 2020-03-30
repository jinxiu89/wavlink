<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
//分类
use app\common\model\Manual;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as emailException;
use think\Collection;
use think\facade\Config;

function pagination($obj)
{
    if (!$obj) {
        return '';
    }
    $params = request()->param();
    return $obj->appends($params)->render();
}


//status输出
function status($status)
{
    if ($status == 1) {
        $str = "<span class='label label-success radius'>正常</span>";
    } elseif ($status == 0) {
        $str = "<span class='label label-error radius'>禁用</span>";
    } else {
        $str = "<span class='label label-danger radius'>禁用</span>";
    }
    return $str;
}

//用户登录密码 key 校验
function GetPassword($password)
{
    return md5(sha1($password) . $key = 'ad;lkfjSDAF@@#$@#Q%4>>><KJJH11111111111111########sdfasdf!!!bbbsdf');
}

function Search($table, $map = [], $order, $field = '')
{
    //公共查询函数
    $query = model($table)->where($map);
    $data = $query->order($order)->field($field)->paginate('', true);
    $counts = $query->count();
    if ($data) {
        $result = [
            'data' => $data,
            'count' => $counts,
        ];
        if (!empty($result)) {
            return $result;
        }
    } else {
        return '';
    }
}

/***
 * 将数据库的查询对象转成数组
 * @param $obj
 * @return Collection
 */
function TurnArray($obj)
{
    return Collection::make($obj);
}

// 字符串进行数组处理，以逗号分割组合
function ModelsArr($data, $key, $newKey)
{
    foreach ($data as $k => $vo) {
        $models = explode(",", $vo[$key]);
        $vo[$newKey] = $models;
    }
    return $data;
}

/**
 * 校验传递的参数是否是正整数
 * @param $value
 * @return bool
 */
function isPositiveInteger($value)
{
    if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
        return true;
    } else {
        return false;
    }
}

/***
 * 根据分类ID获取分类名称
 * @param $id
 * @return string
 * @throws \think\exception\DbException
 */
function getServiceCategory($id)
{
    $map = intval($id);
    if ($map == 0) {
        return "<span style='color: blue'>这是一级分类</span>";
    }
    $data = \app\common\model\ServiceCategory::get($map);
    return $data['name'];
}

/***
 * 返回错误信息
 * @param $status
 * @param $message
 * @param $title
 * @param $btn
 * @param string $url 返回跳转url
 * @param array $data 数据
 */
function show($status, $message = '', $title = '', $btn = '', $url = '', $data = array())
{
    $res = [
        'status' => $status,
        'message' => $message,
        'jump_url' => $url,
        'data' => $data,
        'title' => $title,
        'btn' => $btn
    ];
    exit(json_encode($res));
}

/***
 * @param $arr
 * 生成json数组
 */
function toJson($arr)
{
    exit(json_encode($arr));
}

/**参数：
 * $str_cut    需要截断的字符串
 * $length     允许字符串显示的最大长度
 * 程序功能：截取全角和半角（汉字和英文）混合的字符串以避免乱码
 * $sublen    截取的长度
 * ------------------------------------------------------
 * @param $string
 * @param $sublen
 * @param int $start
 * @param string $code
 * @return string
 */
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if (count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129) $i++;
        }
        if (strlen($tmpstr) < $strlen) $tmpstr .= "...";
        return $tmpstr;
    }
}

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验 为 false，部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

function getNameById($id)
{
    $user = (new \app\common\model\Customer())->getNameById($id);
    return $user['first_name'];
}

/***
 * @param $model_id
 * @return mixed
 * 根据model_id 找出model
 */
function getModelByModel_id($model_id)
{
    $model = (new \app\common\model\Model())->getDataByModel_id($model_id);
    return $model['model'];
}

function getPrdCodeByModel_id($model_id)
{
    $model = (new \app\common\model\Model())->getDataByModel_id($model_id);
    return $model['cate'] . $model['code'];
}

function numberStr($count)
{
    return sprintf('%05s', $count);
}


/***
 * 判断是否是移动端
 */
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
        return false;
    }
    if (strrpos($_SERVER['HTTP_USER_AGENT'], 'Windows NT 10.0')) {
        return false;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }

    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile_old'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/***
 * 后台修改获得更新的url
 * @param $id
 * @return string
 */
function getDownload($id)
{
    $data = Manual::get($id);
    $list = $data->downloads;
    $arr = array();
    foreach ($list as $v) {
        $arr[] = "<a title=\"编辑\" href=\"javascript:;\" onclick=\"add('更新','/wavlink/manual/edit_download?id=" . $v['id'] . "&manual_id=" . $v['manual_id'] . "',800,480)\" class=\"ml-5 btn btn-primary\" style=\"text-decoration:none;margin-bottom: 5px;position: relative\">" . $v['language'] . "</a>";
    }
    return implode('', $arr);
}

/***
 * 前端根据说明书的ID查询到所有的可下载的说明书并给出直接下载的连接
 * @param $id
 * @return string
 */
function getDownloadUrl($id)
{
    $data = Manual::get($id);
    $list = $data->downloads;
    $arr = array();
    foreach ($list as $v) {
        $arr[] = "<a href=" . $v['url'] . " class='download_url btn btn-default'>" . $v['language'] . "<i class=\"iconfont\">&#xe64f;</i></a>";
    }
    return implode('', $arr);
}

/**
 * @param $id
 * @return mixed
 */
function getProductImage($id)
{
    $data = \app\common\model\Product::get($id);
    return $data['image_litpic_url'];
}

function getCategoryLevel($id)
{
    if ($id == '') {
        return intval(1);
    }
    $data = \app\common\model\ServiceCategory::get($id);
    return intval($data['level']) + 1;

}

function toLevel($cate, $delimiter = '--', $parent_id = 1)
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['parent_id'] == $parent_id) {
            $v['delimiter'] = str_repeat($delimiter, $v['level']);
            $arr[] = $v;
            $arr = array_merge($arr, toLevel($cate, $delimiter, $v['id']));
        }
    }
    return $arr;
}

/**
 * @param $toMail
 * @param $toName
 * @param $subject
 * @param $content
 * @param null $attachment
 * @return bool 阿里云的smtp邮件发送
 * 阿里云的smtp邮件发送
 * @throws emailException
 * @internal param $to
 */
function sendMail($toMail, $toName, $subject, $content, $attachment = null)
{
    $mail = new PHPMailer();
    $mail->CharSet = Config::get('email.charSet');
    $mail->IsSMTP();
    $mail->isHTML(true);
    $mail->SMTPDebug = Config::get('email.debug');
    $mail->Debugoutput = Config::get('mail.debug_output');
    $mail->Host = Config::get('email.host');                         // SMTP服务器地址
    $mail->Port = Config::get('email.port');                         // 端口号
    $mail->SMTPAuth = Config::get('email.auth');                // SMTP登录认证
    $mail->SMTPSecure = Config::get('email.secure');            // SMTP安全协议
    $mail->Username = Config::get('email.user');                 // SMTP登录邮箱
    $mail->Password = Config::get('email.password');                 // SMTP登录密码
    $mail->setFrom(Config::get('email.from'), Config::get('email.name'));            // 发件人邮箱和名称
    $mail->addReplyTo(Config::get('email.replay'), Config::get('email.replay_name')); // 回复邮箱和名称
    $mail->AddAddress($toMail, $toName);
    $mail->Subject = $subject;
    $mail->Body = $content;
    if ($attachment) { // 添加附件
        if (is_string($attachment)) {
            is_file($attachment) && $mail->AddAttachment($attachment);
        } else if (is_array($attachment)) {
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
    }
    $result = $mail->send();
    return $result ? true : $mail->ErrorInfo;
}

//传递一个父级分类ID返回所有子级分类
function getChilds($cate, $pid)
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['parent_id'] == $pid) {
            $v['delimiter'] = str_repeat('-', $v['level']);
            $arr[] = $v;
            $arr = array_merge($arr, getChilds($cate, $v['id']));
        }
    }
    return $arr;
}

/***
 * 给定一个子分类ID 返回他所有的父分类
 * @param $cate
 * @param $id
 * @return array
 */
function getParents($cate, $id)
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['id'] == $id) {
            $arr[] = $v;
            $arr = array_merge(getParents($cate, $v['parent_id']), $arr);
        }
    }
    return $arr;
}

/***
 * @param $category_id
 * @return mixed
 * @throws \think\exception\DbException
 */
function getTitleByCategoryID($category_id)
{
    $data = \app\common\model\ServiceCategory::get($category_id);
    return $data['name'];
}

/**
 * @param $id
 * 根据产品ID 把它所属的分类查出来返回到前端
 *
 */
function getCategoryByPid($id)
{
    $categoryIds = \app\common\model\Product::getProductCategory($id);
    return $categoryIds[0];
}

/**
 * @param $id
 * @return mixed
 *
 */
function getCategoryByID($id)
{
    $categoryIds = \app\common\model\Product::getProductCategory($id);
    return $categoryIds[1];
}

function getCNameByCid($id)
{
    $data = \app\common\model\Category::get($id);
    return $data['name'];
}

function getUrlTitleByCid($id)
{
    $data = \app\common\model\Category::get($id);
    return $data['url_title'];
}

function getUrlByCategoryID($category_id)
{
    $data = \app\common\model\ServiceCategory::get($category_id);
    return $data['url_title'];
}

function getDescriptionByCode($code)
{
    $data = app\common\model\Language::getIDStatusByCode($code);
    return $data['remark'];
}

/***
 * @param $header
 * @return string
 *
 */
function get_lang($header)
{
    //拿到浏览器的语言，初始化语言项
    if (empty($header['accept-language'])) {
        return 'en_us';
    } else {
        $lang_code = $header['accept-language'];
        $result = explode(',', $lang_code);
        $code = strtolower($result[0]);
        //在extra 里配置各国语言代码对应相应的模块
        return str_ireplace('-', '_', $code);
    }
}

/**
 * GetFourStr 生成一个任意长度的随机字符串组合
 * @param $len
 * @return string
 */
function GetFourStr($len)
{
    $chars_array = [
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z",
    ];
    $charsLen = count($chars_array) - 1;
    $outputstr = "";
    for ($i = 0; $i < $len; $i++) {
        $outputstr .= $chars_array[mt_rand(0, $charsLen)];
    }
    return $outputstr;
}