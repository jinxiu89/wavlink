<?php


namespace app\wavlink\controller;

use app\common\helper\Search as elSearch;
use app\common\model\Product;
use think\App;
use Elasticsearch\Common\Exceptions as elException;

class Search extends BaseAdmin
{
    protected $elSearch;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->elSearch = new elSearch();
    }

    public function index()
    {
        return $this->fetch();
    }

    public function createIndex()
    {
        $properties = [
            'name' => ['type' => 'text', 'analyzer' => 'standard'],
            'url_title' => ['type' => 'text', 'analyzer' => 'standard'],
            'model' => ['type' => 'text', 'analyzer' => 'standard'],
            'seo_title' => ['type' => 'text', 'analyzer' => 'standard'],
            'keywords' => ['type' => 'text', 'analyzer' => 'standard'],
            'description' => ['type' => 'text', 'analyzer' => 'standard'],
            'features' => ['type' => 'text', 'analyzer' => 'standard'],
        ];
        $params['index'] = 'product_' . $this->currentLanguage['id'];
        $params['body']['mappings']['properties'] = $properties;
        try {
            $response = elSearch::createIndex($params);
            if ($response['acknowledged'] == 1) {
                $this->success('创建索引成功');
            }
            $this->error('已存在！或者服务器错误');
        } catch (elException $exception) {
            $this->success($exception->getMessage(), '', '', 10000);
        }
    }

    public function createProduct()
    {
        $items = Product::allData($this->currentLanguage['id']);
        $productItems = ['body' => []];
        foreach ($items as $item) {
            $productItems['body'][] = [
                'index' => [
                    '_index' => 'products_' . $this->currentLanguage['id'],
                    '_type' => '_doc',
                    '_id' => $item['id'],
                ],
            ];
            $productItems['body'][] = $item;
        }
        try {
            elSearch::Client()->bulk($productItems);
        } catch (elException $exception) {
            $this->error($exception->getMessage(), '', '', 5);
        }
        $this->success('创建完成！');
    }
    public function searchProduct(){
        $params=['index'=>'products_'.$this->currentLanguage['id'],'type'=>'_doc','body'=>[
            'query'=>['bool'=>['filter'=>[['term'=>['name'=>'6'],],],],],
        ],];
        $data=elSearch::Client()->search($params);
        print_r($data);
    }
}