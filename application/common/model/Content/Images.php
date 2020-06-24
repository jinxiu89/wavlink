<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\Content;

use app\common\model\Language as LanguageModel;
use app\common\model\BaseModel;
use think\Exception;
use think\facade\Cache;
use think\facade\Log;

class Images extends BaseModel
{
    protected $table = 'images';

    // 根据状态和语言查找 所有首页产品
    public function getImages($status, $language_id)
    {
        $map = [
            'language_id' => $language_id,
            'status' => $status
        ];
        $order = [
            'featured_id' => 'desc',
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $query = self::where($map)->order($order);
        $result['data'] = $query->paginate();
        $result['count'] = $query->count();
        return $result;
    }

    //获取推荐位的产品图片

    /**
     * @param int $language_id
     * @param int $featured
     * @param string $field
     * @return array|mixed|void
     */
    public function getImagesByFeatured($language_id = 1, $featured = 4, $field = '')
    {
        $map = ['status' => 1, 'featured_id' => $featured, 'language_id' => $language_id];
        $order = ['featured_id' => 'desc', 'listorder' => 'desc', 'id' => 'desc',];
        try {
            if (false == $this->debug) {
                $data = Cache::get($featured . __FUNCTION__);
                if ($data) return $data;
                $query = $this->where($map);
                $obj = $query->order($order)->field($field)->paginate('', true);
                $count = $query->count();
                $result['data'] = $obj;
                $result['count'] = $count;
                Cache::set($featured . __FUNCTION__, $result);
                return $result;
            }
            $query = $this->where($map);
            $obj = $query->order($order)->field($field)->paginate('', true);
            $count = $query->count();
            $result['data'] = $obj;
            $result['count'] = $count;
            return $result;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return [];
        }
    }
}