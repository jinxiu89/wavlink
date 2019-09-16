<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller;

use app\wavlink\validate\UrlTitleMustBeOnly;
use think\Request;
use app\common\model\About as AboutModel;
use app\wavlink\validate\About as AboutValidate;

/***
 * Class About
 * @package app\wavlink\controller
 * 关于我们：有语言区分
 */
Class About extends BaseAdmin
{
    /***
     * @return mixed
     * 20190916
     * 修改语言获取方式为session方式
     */
    public function index()
    {
        $order = [
            'status' => 'desc',
            'listorder' => 'desc'
        ];
        $about = AboutModel::getDataByOrder(['language_id' => $this->currentLanguage['id']], $order);
        return $this->fetch('', [
            'about' => $about['data'],
            'count' => $about['count'],
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    public function add()
    {
        return $this->fetch('', [
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    public function save(Request $request)
    {
        if (request()->isAjax()) {
            (new AboutValidate())->goCheck();
            (new UrlTitleMustBeOnly())->goCheck();
            $data = $request::instance()->post();
            if (!empty($data['id'])) {
                return $this->update($data);
            }
            $res = (new AboutModel())->add($data);
            if ($res) {
                return show(1, '', '', '', '', '添加成功');
            } else {
                return show(1, '', '', '', '', '添加失败');
            }
        }
    }

    public function edit($id)
    {
        $id = $this->MustBePositiveInteger($id);
        $about = AboutModel::get($id);
        return $this->fetch('', [
            'about' => $about,
            'language_id' => $this->currentLanguage['id']
        ]);
    }
}