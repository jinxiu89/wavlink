<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/11/28 18:14
 * @User: kevin
 * @Current File : Jwt.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\helper;
use Firebase\JWT\JWK;

class Jwt
{
    private $key='';//key
    private $payload=[
        'iss'=>'https://www.wavlink.com',
        'aud'=>'https://www.wavlink.com',
        'iat'=>'',
        'nbf'=>'',
    ];

}