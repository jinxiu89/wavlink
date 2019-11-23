<?php


namespace app\common\helper;

use think\exception\DbException;

/**
 * Class Search
 * @package app\common\helper
 * jinxiu89
 * 20191122
 * 全文搜索工具库
 */
class Search extends Elasticsearch
{
    /**
     * @param $model
     * @param $map
     * @param $order
     * @param int $page
     * @return array|string
     * @throws DbException
     */
    public static function search($model, $map, $order, $page = 12)
    {
        //公共查询函数
        $data = model($model)->where($map)->order($order)->paginate($page);
        $counts = model($model)->where($map)->count();
        if ($data) {
            $result = ['data' => $data, 'count' => $counts,];
            return $result;
        } else {
            return '';
        }
    }

    /**
     * @param $size
     * @param $page
     * @return Elasticsearch
     * 添加分页查询
     */
    public function paginate( $page,$size)
    {
        $this->params['body']['from'] = ($page - 1) * $size;
        $this->params['body']['size'] = $size;
        return $this;
    }

    /**
     * @param $status
     * @return $this
     * 传递要查询的状态码
     */
    public function status($status)
    {
        $this->params['body']['query']['bool']['filter'][] = ['term' => ['status' => $status]];
        return $this;
    }
    /**
     * @param $keywords
     * @param  $fields
     * @return $this
     * 添加搜索词
     * 传过来一个数组或者单个关键词
     * 字段必须是一个数组，便于创建搜索器，加权示例：title^3 description^2 (数字越大权重越高)
     */
    public function keywords($keywords, $fields)
    {
        $keywords = is_array($keywords) ? $keywords : [$keywords];
        foreach ($keywords as $keyword) {
            $this->params['body']['query']['bool']['must'][] = [
                'multi_match' => [
                    'query' => $keyword,
                    'fields' => $fields,
                ],
            ];
        }
        return $this;
    }

    /**
     * @param $field
     * @param $direction
     * @return $this
     */
    public function orderBy($field, $direction)
    {
        if (!isset($this->params['body']['sort'])) {
            $this->params['body']['sort'] = [];
        }
        $this->params['body']['sort'][] = [$field => $direction];

        return $this;
    }

    /**
     * // 返回构造好的查询参数
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}