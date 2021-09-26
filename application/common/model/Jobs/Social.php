<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月24日 17:37:04 星期二
 * @User: admin
 * @Current File : Social.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\model\Jobs;

use app\common\model\BaseModel;
use think\Db;

/**
 * 社招职位 模型 class
 *
 * @Author: kevin qiu
 * @DateTime: 2021-08-24
 */
class Social extends BaseModel
{
    protected $table = 'tb_social_jobs';
    /**
     * 获取所有职位 状态位 急招货正常招聘的职位在查询之列
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-30
     * @param array $status
     * @return void
     */
    public function gedJobsByStatus(array $status)
    {
        return Db::table($this->table)
            ->where('status', 'in', $status)
            ->field('id,title,url_title,city,type,salary,seniority,education,status,sort,numbers,update_time')
            ->order(['sort' => 'desc', 'create_time' => 'desc', 'id' => 'asc'])->select();
    }
    public function getDataByWhere(array $where, array $status)
    {
        return Db::table($this->table)->whereIn('status', $status)
            ->where($where)->field('id,title,url_title,city,type,salary,seniority,education,status,sort,numbers,update_time')
            ->order(['sort' => 'desc', 'create_time' => 'desc', 'id' => 'asc'])->select();
    }
    /**
     * 获取职位详情
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-30
     * @param string|null $url_title
     * @return void
     */
    public function getDetails(string $url_title = null)
    {
        # code...
        return Db::table($this->table)->where(['url_title' => $url_title])->find();
    }

    public function Sort(int $id, int $sort)
    {
        return Db::table($this->table)->update(['id' => $id, 'sort' => $sort]);
    }

    public function getCity()
    {
        return Db::table($this->table)->column('city');
    }
}