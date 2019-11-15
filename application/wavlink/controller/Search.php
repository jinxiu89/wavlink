<?php


namespace app\wavlink\controller;

use app\common\helper\Search as elSearch;
use app\common\model\Product;

class Search extends BaseAdmin
{
    public function index()
    {
        return $this->fetch();
    }

    public function createProduct()
    {
        $items = Product::allData($this->currentLanguage['id']);
        $productItems = ['body' => []];
        foreach ($items as $item) {
            $productItems['body'][] = [
                'index'=>[
                    '_index' => 'products_' . $this->currentLanguage['id'],
                    '_type' => '_doc',
                    '_id' => $item['id'],
                ],
            ];
            $productItems['body'][] = $item;
        }
        try {
            elSearch::createClient()->bulk($productItems);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage(),'','',5);
        }
        $this->success('创建完成！');
    }
}