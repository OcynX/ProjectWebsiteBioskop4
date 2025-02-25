<?php 

$gender = "pria";
$sapaan = null;

if($gender == "pria"){
    $sapaan = "kamu adalah pria";
    echo $sapaan;
}elseif($gender == "wanita"){
    $sapaan = "halo kak";
    echo $sapaan;
}else{
    echo "kamu tak bergender";
}