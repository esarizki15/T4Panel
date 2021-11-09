<?php

use App\Http\Model\Order;

function num2word($n=0) {
    $n = intval($n);
   
    if($n>1000000000000) return round(($n/1000000000000),1).'T';
    else if($n>1000000000) return round(($n/1000000000),1).'B';
    else if($n>1000000) return round(($n/1000000),1).'M';
    else if($n>1000) return round(($n/1000),1).'K';
    else return $n;
}

function slugify($text="") {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);

    if (empty($text)) {
    return 'n-a';
    }

    return $text;
}

function getOrderNumber($id){
    if($id == '' || $id == null) return '';
    $rs = 'xxx-'.$id;
    $order = Order::find($id);
    if($order != null){
        $rs = $order->no;
    }

    return $rs;
}

function setViewCaption($str)
{
    $str = str_replace("_", " ", $str);
    $str = strtoupper($str);

    return $str;
}