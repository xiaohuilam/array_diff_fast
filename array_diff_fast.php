<?php

// 高效的数组diff算法...
// Weak: Memory spending... tested 200,0000 * 200,0000 will spending near 512mb memory...

function flip( $array ){
    // 将数组键值反转...
    // 之所以自主封装是为了保留重复值...
    $array_f = array();
    foreach( $array as $key => $value ){
        for( $i = 0; $i < 1000; $i ++ ){
            if( is_array( $value ) ) $value = join(',', $value);
            $k = $value . str_repeat(' ',$i);
            if( !isset( $array_f[ $k ]) ){
                $array_f[ $k ] = $key;
                break;
            }
        }
    }
    return $array_f;
}

function diff( $arr1, $arr2 ){
    $arr1 = flip( $arr1 );
    $arr2 = flip( $arr2 );
    foreach( $arr1 as $k => $v ){
        if( isset( $arr2[ $k ] ) ){ // It saved the time here.... Ya~
            unset( $arr1[ $k ], $arr2[ $k ] );
        }
    }
    $arr1_tmp = array();
    $arr2_tmp = array();
    $arr1_tmp = array_flip( $arr1 );
    $arr2_tmp = array_flip( $arr2 );
    return Array(
        $arr1_tmp,
        $arr2_tmp
    );
}

function getmillisecond() {
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

$test = array(
);
$test_2 = $test;
for($i = 0; $i < 20000; $i ++ ){
    $test[] = '('. rand(1,10) . ',' . rand(1,10) .',' . rand(1,10) .',' . rand(1,10) . ',' . rand(1,10) . ')';
    $test_2[] = '('. rand(1,10) . ',' . rand(1,10) . ',' . rand(1,10) .',' . rand(1,10) .',' . rand(1,10) . ')';
}
$start = getmillisecond();
$a = diff( $test, $test_2 );
$end = getmillisecond();
echo count($a[0]);
echo "\r\n";
echo count($a[1]);
echo "\r\n";
echo "total spend: {$start} ~ {$end} : " . ($end - $start);
