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

Class About extends BaseAdmin
{
    public function index() {
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));
        $order = [
            'status' => 'desc',
            'listorder' => 'desc'
        ];
        $about = AboutModel::getDataByOrder(['language_id' => $language_id], $order);
        return $this->fetch('', [
            'about' => $about['data'],
            'count' => $about['count'],
            'language_id' => $language_id
        ]);
    }

    public function add() {
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));
        return $this->fetch('', [
            'language_id' => $language_id
        ]);
    }

    public function save(Request $request) {
        if (request()->isAjax()) {
            (new AboutValidate())->goCheck();
            (new UrlTitleMustBeOnly())->goCheck();
            $data = $request::instance()->post();

            if (!empty($data['id'])) {
                return $this->update($data);
            }
            $res = (new AboutModel())->add($data);
            if ($res) {
                return show(1,'','','','', '添加成功');
            } else {
                return show(1,'','','','', '添加失败');
            }
        }
    }

    public function edit($id) {
        $id = $this->MustBePositiveInteger($id);
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));

        $about = AboutModel::get($id);
        return $this->fetch('', [
            'about' => $about,
            'language_id' => $language_id,
        ]);
    }
}