<?php
function arr_unique($arr2d)
{
    foreach ($arr2d as $k => $v) {
        $v = join(',', $v);
        $temp[] = $v;
    }
    if ($temp) {
        $temp = array_unique($temp);
        foreach ($temp as $k => $v) {
            $temp[$k] = explode(',', $v);
        }
        return $temp;
    }
}

/**
 * @param $path
 */
function getPath($path)
{
    $strArr = explode('-', $path);
    $pth = "/\S+/i";
    $items = preg_grep($pth, $strArr);
    $html = '';
    foreach ($items as $item) {
        $cat = app\common\model\Content\Category::field('name,url_title')->get($item);
        if ($item == end($items)) {
            $html .= '<li class=\'active\'>' . $cat->name . '</li>';
        } else {
            $html .= '<li>' . '<a href=' . $cat->url_title . '>' . $cat->name . '</a>' . '</li>';
        }
    }
    return $html;
}


