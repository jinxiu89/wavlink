<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年09月09日 13:56:35 星期四
 * @User: admin
 * @Current File : SocialResume.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service\contents;

use app\wavlink\service\Base;
use app\common\model\Jobs\SocialResume as model;
use Exception;
use think\facade\Env;

class SocialResume extends Base
{
    protected $model;
    /**
     * Undocumented function
     * @Author: kevin qiu
     * @DateTime: 2021-09-09
     */
    public function __construct()
    {
        $this->model =  new model();
    }
    /**
     * 获取列表数据，分页，并按id 倒叙排列
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-08
     * @return void
     */
    public function getData()
    {
        try {
            $response = $this->model->order('id desc');
            $result['count'] = $response->count();
            $result['data'] = $response->paginate(25);
            return $result;
        } catch (\Exception $exception) {
            //todo:日志 错误
            return [$exception->getMessage()];
        }
    }
    /**
     * 预览简历后更新状态为已读抓昂天
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-08
     * @param integer $id
     * @param integer $status
     * @return void
     */
    public function readed(int $id, int $status)
    {
        //todo::
        try {
            $this->model->save(['status' => $status], ['id' => $id]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
    /**
     * Undocumented function
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-08
     * @param array $data
     * @return void
     */
    public function add_tag(array $data)
    {
        try {
            return $this->model->save(['tags_id' => $data['tags_id']], ['id' => $data['id']]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}