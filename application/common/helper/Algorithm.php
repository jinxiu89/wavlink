<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/10
 * Time: 17:57
 * php 常用的算法
 */
namespace app\common\helper;


class Algorithm
{
    /**
     * 二分法查询
     * 根据数组中的某个键值 key查找
     * $key 键，$array 传入的查找数组 $item 查找的值，
     * @param $array
     * @param $item
     * @param $key
     * @return bool|float
     */
    static public function binarySearch($array,$item,$key) {
        $low =0;
        $high = count($array)-1;
        while ($low <=$high ){
            $mid = floor(($low+$high)/2);
            if ($array[$mid][$key] == $item){
                return $mid;
            }elseif ($array[$mid][$key] < $item){
                //item 大于中间值 ,说明item在中间值和high之间，此时 low = mid+1,high还是等于high
                $low = $mid +1;
            }else{
                //item小于中间值，说明item在 low 和 中间值之间，此时low还是等于0,high=mid-1
                $high = $mid-1;
            }
        }
        return false; //没有找到这个数
    }

    /**
     * 这是一个选择排序算法，对于非常多条的数据建议不采用这种算法。
     * PHP一维数组的排序可以用sort()，asort(),arsort()等函数，但是PHP二维数组的排序需要自定义。
     *以下函数是对一个给定的二维数组按照指定的键值进行排序，先看函数定义：
     * $arr 是一个二维数组，$keys是指定的键 ,$type 默认升序排序
     * @param $arr
     * @param $keys
     * @param string $type
     * @return array
     */
    static public function array_sort($arr, $keys, $type = 'asc') {
        $keysValue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            //升序排序
            asort($keysValue);
        } else {
            //降序排序
            arsort($keysValue);
        }
        reset($keysValue);
        foreach ($keysValue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return array_values($new_array);
    }

    /**
     * @param $arr
     * @param $key
     * @return array
     * 快速排序算法
     * $arr 是一个二维数组，$key是指定的键 ,进行升序排序
     */
    static public function quickSort($arr,$key){
        if (count($arr) < 2){
            return $arr;
        }else{
            $num=count($arr);
            $l=$r=0;
            $left=$right=[];
            for ($i=1;$i<$num;$i++){
                if ($arr[$i][$key] < $arr[0][$key]){
                    $left[] = $arr[$i];
                    $l++;
                }else{
                    $right[]=$arr[$i];
                    $r++;
                }
            }
            $new_arr = $left;
            $new_arr[]=$arr[0];
            if ($l > 1){
                $right=self::quickSort($right,$key);
            }
            for ($i=0;$i<$r;$i++){
                $new_arr[]=$right[$i];
            }
            return $new_arr;
//            $less=$greater=array();
//            $pivot_arr = [$arr[0]];
//            $pivot = $arr[0][$key];
//            unset($arr[0]);
//            foreach ($arr as $k => $v){
//                if ($v[$key] <= $pivot){
//                    $less[] = $v;
//                }
//            }
//            foreach ($arr as $k => $v){
//                if ($v[$key] > $pivot){
//                    $greater[] = $v;
//                }
//            }
//            return array_merge(self::quickSort($less,$key),$pivot_arr,self::quickSort($greater,$key));
        }
    }


}