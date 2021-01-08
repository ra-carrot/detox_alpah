<?php

require_once 'common_detox.php';

try {
    $dao = new DetoxDao();
//    var_dump($dao);

}catch (Exception $e){
    echo $e->getMessage();
}