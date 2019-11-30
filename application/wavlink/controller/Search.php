<?php


namespace app\wavlink\controller;

use app\common\helper\Search as elSearch;
use app\common\model\Product;
use app\common\model\Drivers;
use think\App;
use Elasticsearch\Common\Exceptions as elException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Search extends BaseAdmin
{
    protected $elSearch;

    /***
     * Search constructor.
     * @param App|null $app
     *
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->elSearch = new elSearch();
    }

    /***
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    public function createIndex()
    {
        $properties = [
            'name' => ['type' => 'text'],
            'url_title' => ['type' => 'text'],
            'model' => ['type' => 'text'],
            'seo_title' => ['type' => 'text'],
            'keywords' => ['type' => 'text'],
            'description' => ['type' => 'text'],
            'features' => ['type' => 'text'],
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
        if($this->elSearch->Client()->indices()->exists(['index'=>'products_' . $this->currentLanguage['id']])){
            $this->elSearch->Client()->indices()->delete(['index'=>'products_' . $this->currentLanguage['id']]);
        }
        try {
            $this->elSearch->Client()->bulk($productItems);
            return show(1, 'success', '', '', '', '更新成功');
        } catch (elException $exception) {
            return show(0, 'failed', '', '', '', $exception->getMessage());
        }
    }

    public function createDriver()
    {
        try {
            $items = Drivers::allData($this->currentLanguage['id']);
            $driverItems = ['body' => [],];
            foreach ($items as $item) {
                $driverItems['body'][] = [
                    'index' => [
                        '_index' => 'drivers_' . $this->currentLanguage['id'],
                        '_type' => '_doc',
                        '_id' => $item['id'],
                    ]
                ];
                $driverItems['body'][] = $item;
            }
            if($this->elSearch->Client()->indices()->exists(['index'=>'drivers_' . $this->currentLanguage['id']])){
                $this->elSearch->Client()->indices()->delete(['index'=>'drivers_' . $this->currentLanguage['id']]);
            }
            try {
                $this->elSearch->Client()->bulk($driverItems);
            } catch (elException $exception) {
                return show(0, 'failed', '', '', '', $exception->getMessage());
            }
            return show(1, 'success', '', '', '', '更新成功');
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }

    public function searchProduct()
    {
        $params = ['index' => 'products_' . $this->currentLanguage['id'], 'type' => '_doc', 'body' => [
            'query' => ['bool' => ['filter' => [['term' => ['name' => '6'],],],],],
        ],];
        $data = elSearch::Client()->search($params);
        print_r($data);
    }
}