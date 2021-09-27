<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年09月15日 18:02:34 星期三
 * @User: admin
 * @Current File : World2Html.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\helper;

use Exception;
use Unoconv\Unoconv;

class World2Html
{
    /**
     * unconv 转还pdf
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-27
     * @param string $input
     * @param string $output
     * @return void
     */
    public function doc2pdf(string $input, string $output)
    {
        try {
            $unoconv = Unoconv::create([
                'timeout' => 42,
                'unoconv.binaries' => PUBLIC_PATH . '/../unoconv',
            ]);
            // return $unoconv->transcode($input, 'pdf', $output); //成功是返回的一个对象体
            if ($unoconv->transcode($input, 'pdf', $output)) return ['status' => 1, 'message' => '转换成功'];
        } catch (Exception $exception) {
            return ['status' => 0, 'message' => $exception->getMessage()];
        }
    }
}