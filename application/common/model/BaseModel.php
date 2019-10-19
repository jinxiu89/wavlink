<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 * 推荐位管理
 */

namespace app\common\model;

use app\common\helper\Search as SearchHelp;
use app\common\model\Language as LanguageModel;
use app\en_us\controller\Search;
use think\facade\Cookie;
use think\db\exception\ModelNotFoundException;
use think\Model;

Class BaseModel extends Model
{
    //php think optimize:schema  在命令行里输入这个，生成数据库缓存字段
    protected $autoWriteTimestamp = true; //把时间设置成当前时间

    public function add($data)
    {
        $data['status'] = 1;
        $data['listorder']=1;
        $this->save($data);
        //获取自增id
        $id = $this->id;
        //同时更新listorder字段，把id的值加100赋给它
        return $this->allowField('listorder')
            ->isUpdate(true)
            ->save(
                ['listorder' => $id + 100],
                ['id' => $id]
            );
    }


    /**
     * 根据状态值获取数据，
     * 后台首页正常数据，status=1
     * 回收站数据，status=-1
     * 下架数据,status=0
     * 传入一个排序数组
     * @param null|string $status
     * @param $order
     * @param $code
     * @return Search|mixed
     * 对于有语言的数据查询
     * @throws ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getDataByStatusLanguage($status, $order, $code)
    {
        $model = request()->controller();
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => $status,
            'language_id' => $language_id['language_id'],
        ];
        return Search($model, $map, $order);
    }

    /**
     * 根据状态值，，默认排序
     * 语言查找 语言可以为空
     * 可以设置默认值 status = 1
     * $result['data']
     * $result['count']
     * @param $status
     * @param string $language_id
     * @return array|string
     * @internal param int $page
     * @internal param array $map
     * @internal param $status
     */
    public static function getDataByStatus($status, $language_id = '')
    {
        if (empty($language_id)) {
            $map = [
                'status' => $status,
            ];
            $order = [
                'language_id' => 'desc',
                'id' => 'desc'
            ];
        } else {
            $map = [
                'status' => $status,
                'language_id' => $language_id
            ];
            $order = [
                'listorder' => 'desc',
                'id' => 'desc'
            ];
        }
        $model = request()->controller();
        if (Cookie::has('systemPage')) {
            $page = Cookie::get('systemPage');
        } else {
            $system = config('system.system');
            $page = $system['page'];
        }
        return SearchHelp::search($model, $map, $order, $page);
    }

    /**
     * 无状态status值数据查询
     * @param array $data
     * @param array $order
     * @return Search
     */
    public static function getDataByOrder($data = [], $order = [
        'status' => 'desc',
        'id' => 'desc',
    ])
    {
        $con = request()->controller();
        return Search($con, $data, $order);
    }

    /**
     * 构造一个通过url_title 获取一条记录
     * @url_title
     * 根据uri_title 获取一条记录
     * @param $urlTitle
     * @param $code
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getDetailsByUrlTitle($urlTitle, $code)
    {
        $model = request()->controller();
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
            'language_id' => $language_id,
            'url_title' => $urlTitle
        ];
        return model($model)->where($map)->find();
    }

    /**
     * 置顶,上移，下移，置底功能
     * @param $data
     * @param $model
     * @$map 基本条件，
     * @param string $map2 其他条件，
     * @return int * 上移下移
     * 上移下移
     * 上移 把大于它（要上移的数据的排序序号）的排序序号的最小值和它的排序序号更换
     * @internal param array $_map
     * @internal param $id
     * type == 1 时 置底
     * type == 4 时 置顶
     * type == 3 时 上移
     * type == 2 时 下移
     * @language_id = $data['language_id']
     * @id = $data['id']
     * @internal param $ = $data['type']
     */
    public static function listorder($data, $model, $map2 = '')
    {
        $list = model($model)->get($data['id']);
        $_map = array(
            'map1' => [
                'listorder' => ['lt', $list['listorder']],
                'status' => 1,
                'language_id' => $data['language_id']
            ],
            'map2' => [
                'listorder' => ['gt', $list['listorder']],
                'status' => 1,
                'language_id' => $data['language_id']],
        );
        switch ($data['type']) {
            case 4://置顶  找出最大的排序值再加1    (查找出大于要置顶的排序的最大值)
                $map = $_map['map2'];
                $order = ['listorder' => 'desc'];
                $_listorder = limit($model, $map, $order, 1, 'listorder', $map2);
                $listData = [
                    ['id' => $data['id'], 'listorder' => $_listorder[0]['listorder'] + 1]
                ];
                break;
            case 1: //置底 找出最小的排序值 -1
                $map = $_map['map1'];
                $order = ['listorder' => 'asc'];
                $_listorder = limit($model, $map, $order, 1, 'listorder', $map2);
                $listData = [
                    ['id' => $data['id'], 'listorder' => $_listorder[0]['listorder'] - 1]
                ];
                break;
            case 3: //上移时 查找大于要移动的排序序号的最小值,并将两个排序序号互相更换
                $map = $_map['map2'];
                $order = ['listorder' => 'asc'];
                $_listorder = limit($model, $map, $order, 1, 'listorder,id', $map2);
                $listData = [
                    ['id' => $data['id'], 'listorder' => $_listorder[0]['listorder']],
                    ['id' => $_listorder[0]['id'], 'listorder' => $list['listorder']]
                ];
                break;
            case 2: //下移 查找小于要移动的排序序号的最大值,并将两个排序序号互相更换
                $map = $_map['map1'];
                $order = ['listorder' => 'desc'];
                $_listorder = limit($model, $map, $order, 1, 'listorder,id', $map2);
                $listData = [
                    ['id' => $data['id'], 'listorder' => $_listorder[0]['listorder']],
                    ['id' => $_listorder[0]['id'], 'listorder' => $list['listorder']]
                ];
                break;
        }

        if (!empty($_listorder)) {
            $res = model($model)->isUpdate(true)->allowField(true)->saveAll($listData);
            if ($res) {
                return 1; //操作成功
            } else {
                return 0; //出现异常
            }
        } else {
            return -1; //最高 或者最低的不能操作
        }
    }

    /***
     * 传递一个待处理二位数组，去掉他的value为空的item，返回没有空value的item
     * @param $data
     * @return array
     */
    public static function array_empty($data)
    {
        $result = array();
        foreach ($data as $k => $v) {
            if (!empty($v)) {
                $result[$k] = $v;
            }
        }
        return $result;
    }
}

