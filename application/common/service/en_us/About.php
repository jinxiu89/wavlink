<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/9 14:46
 * @User: admin
 * @Current File : About.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;


use app\common\model\About as model;

class About extends BaseService
{
    public function __construct()
    {
        $this->model=new model();
    }

    /**
     * @param $url_title
     * @param $code
     * @return string
     */
    public function getArticle($url_title,$code){

        try{
            return $this->model->getArticleByUrlTitle($url_title,$code);
        }catch (\Exception $exception){
            abort(404);
        }
    }
}