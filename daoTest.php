<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
require_once 'dbcontroll.php';
echo var_dump($dao->check_marketing_level_overlap());
