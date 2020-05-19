<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/20
 * Time: 16:39
 */

namespace app\en_us\controller;

use app\common\model\Service\Faq as FaqModel;
use app\common\model\Service\ServiceCategory;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Request;

class Faq extends Base
{
    /**
     * Faq constructor.
     * @param APP|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        try {
            $cate = ServiceCategory::getTree($this->code,'faq');
            $this->assign('cate', $cate);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }

    /**
     * @param string $order
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index($order = 'desc')
    {
        //获取一级faq分类
        $parent = ServiceCategory::getTopCategory($this->code, 'faq');
        //获取所有的faq列表
        $faq = (new FaqModel())->getFaqByCategoryID('', $this->code, $order);
        return $this->fetch($this->template . '/faq/index.html', [
            'parent' => $parent,
            'name' => '',
            'order' => $order,
            'count' => $faq['count'],
            'data' => $faq['data']
        ]);
    }

    public function category($url_title = '', $order = 'desc')
    {
        if (empty($url_title) || !isset($url_title)) {
            abort(404);
        }
        if($url_title == 'all'){
            return redirect(url('/'.$this->code.'/faq'),[],200);
        }
        //获取选择的faq子分类信息
        $parent = ServiceCategory::getCategoryIdByName($this->code, $url_title);
        if (empty($parent)) {
            abort(404);
        } else {
            $faq = (new FaqModel())->getFaqByCategoryID($parent['id'], $this->code, $order);
            return view($this->template . '/faq/index.html', [
                'data' => $faq['data'],
                'count' => $faq['count'],
                'parent' => $parent,
                'name' => $parent['name'],
                'order' => $order,

            ]);
        }
    }

    public function details($url_title = '')
    {
        if (empty($url_title) || !isset($url_title)) {
            abort(404);
        }
        //该问题详情页
        $result = FaqModel::getDataByTitle($url_title);
        //该问题的分类
        $faqCate = ServiceCategory::get(['id' => $result['category_id']]);
        if (!empty($result)) {
            return $this->fetch($this->template . '/faq/details.html', [
                'faqName' => cut_str($result['name'], 15),
                'result' => $result,
                'faqCate' => $faqCate
            ]);
        } else {
            abort(404);
        }
    }
}
