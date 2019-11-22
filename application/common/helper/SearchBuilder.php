<?php


namespace app\common\helper;

/**
 * Class SearchBuilder
 * @package app\common\helper
 * 查询构造器
 */
class SearchBuilder
{
    protected $params = [];

    /**
     * @param $index
     * @return $this
     * 添加要查的哪个索引
     */
    public function getIndex($index)
    {
        $this->params['index'] = $index;
        return $this;
    }

    /**
     * @param $size
     * @param $page
     * @return SearchBuilder
     * 添加分页查询
     */
    public function paginate($size, $page)
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
     * 字段必须是一个数组，便于创建搜索器
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
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}