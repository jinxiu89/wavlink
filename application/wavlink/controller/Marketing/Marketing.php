<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 * 营销管理单页面管理
 */

namespace app\wavlink\controller\Marketing;

use think\Facade\Request;
use app\common\model\Language as LanguageModel;
use app\common\model\Marketing as MarketingModel;
use app\wavlink\validate\Marketing as MarketingValidate;
use app\wavlink\controller\BaseAdmin;
Class Marketing extends BaseAdmin
{
    /**
     * @return mixed
     *  做程序的人，要注意身体，要笑到最后才是赢家：
     *  金匮肾气汤，熬夜加班，头晕脑涨，说不定就是肾虚了，不是睡女人才会肾虚的
     * 【材料】桂枝10克，制附子6克，熟地黄15克，山萸肉15克，山药15克，茯苓15克，丹皮10克，泽泻10克;
     * 【功效】温补肾阳，行气化水。用于肾虚水肿，腰膝酸软，小便不利，畏寒肢冷。治脾肾大虚，腰重脚重，小便不利，肚腹肿胀，四肢浮肿，喘急痰盛，已成蛊症，其效如神
     *
     * 柴胡10 黄芩10 陈皮10 赤白芍10 枳壳10 甘草6 生山茶6 焦山茶6  疏肝解郁
     */
    public function index()
    {
        $result = (new MarketingModel())->getDataByOrder('', '', $this->currentLanguage['id']);
        return $this->fetch('', [
            'marketing' => $result['data'],
            'count' => $result['count'],
        ]);
    }

    public function add()
    {
        $languages = LanguageModel::all([
            'status' => 1,
        ]);
        return $this->fetch('', [
            'languages' => $languages,
        ]);
    }

    public function save(Request $request)
    {
        if (!request()->isPost()) {
            return show(0, 'error', '提交不合法');
        }
        $data = $request::instance()->post();
        $validate = new MarketingValidate();
        if(isset($data['id']) and !empty($data['id'])){
            if($validate->scene('edit')->check($data)){
                try{
                    return $this->update($data);
                }catch (\Exception $exception){
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
        }else{
            try{
                if($validate->scene('add')->check($data)){
                    $res = model("Marketing")->add($data);
                    if ($res) {
                        return show(1, '', '', '', '', '添加成功');
                    } else {
                        return show(0, '', '', '', '', '添加失败');
                    }
                }
            }catch (\Exception $exception){
                return show(0, '', '', '', '', $exception->getMessage());
            }
        }
        return show(0, '', '', '', '', $validate->getError());
    }

    public function edit($id)
    {
        if (intval($id) < 0) {
            return show(0, 'error', 'Id不合法');
        }
        $languages = LanguageModel::all([
            'status' => 1
        ]);
        $marketing = MarketingModel::get($id);
        return $this->fetch('', [
            'languages' => $languages,
            'marketing' => $marketing,
        ]);
    }

    /**
     * 改变状态
     *
     */
    public function byStatus()
    {
        $data = input('get.');
        $check['status'] = number_format($data['status']);
        $validate =new MarketingValidate();
        if ($validate->scene('changeStatus')->check($data)) {
            try {
                if ((new MarketingModel())->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            } catch (\Exception $exception) {
                return show(0, "failed", '', '', '', $exception->getMessage());
            }
        }
        return show(0, "failed", '', '', '', $validate->getError());
    }

    /**
     * 失效的部分 删除 操作
     */
    public function del()
    {
        $id = input('get.id');
        $validate=new MarketingValidate();
        if (!$validate->scene('del')->check(['id' => $id])) {
            return show(0, 'error', $validate->getError());
        }
        //删除
        try {
            $res = MarketingModel::destroy($id);
            if ($res) {
                return show(1, 'success', '', '', '', '删除成功');
            } else {
                return show(0, 'failed', '', '', '', '删除失败,位置错误');
            }
        } catch (\Exception $e) {
            return show(0, 'failed', '', '', '', $e->getMessage());
        }
    }
}