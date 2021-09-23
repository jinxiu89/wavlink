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

class World2Html
{
    /**
     * 利用 libreoffice 转PDF
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-23
     * @param [type] $source
     * @param [type] $outdir
     * @return void
     */
    public function Libreoffice($source, $outdir)
    {
        try {
            $retval = 1;
            $cmd = 'export HOME=/tmp/ && /usr/bin/soffice --headless --convert-to pdf ' . $source . ' --outdir ' . $outdir;
            if (function_exists('exec')) {
                @exec($cmd, $output, $retval);
            }
            if ($retval > 0) {
                return ['status' => 0, 'message' => '程序运行失败'];
            }
            return ['status' => 1, 'message' => '转换成功'];
        } catch (Exception $exception) {
            return ['status' => 0, 'message' => $exception->getMessage()];
        }
    }
}