<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default_local';

$query_builder = TRUE;

$db["default_local"] = array("dsn"  => "",
"hostname" => "dxb_vcproduction_db", 
"username" => "dxb_vcproduction" ,
"password" => "dxb_vcproduction",
"database" => "dxb_vcproduction",
"dbdriver" => "mysqli",
"pconnect" => FALSE,
"db_debug" => (ENVIRONMENT !== "production"),
"cache_on" => FALSE,
"cachedir" => "",
"char_set" => "utf8",
"dbcollat" => "utf8_general_ci",
"swap_pre" => "",
"encrypt" => FALSE,
"compress" => FALSE,
"stricton" => FALSE,
"failover" => array(),
"save_queries" => TRUE);

$db["default_testing"] = array("dsn"  => "",
"hostname" => "valucart.cvcf6fj8p13n.eu-central-1.rds.amazonaws.com", 
"port" => "3306",
"username" => "testing" ,
"password" => "(Testing#115)",
"database" => "valucart_testing",
"dbdriver" => "mysqli",
"pconnect" => FALSE,
"db_debug" => (ENVIRONMENT !== "production"),
"cache_on" => FALSE,
"cachedir" => "",
"char_set" => "utf8",
"dbcollat" => "utf8_general_ci",
"swap_pre" => "",
"encrypt" => FALSE,
"compress" => FALSE,
"stricton" => FALSE,
"failover" => array(),
"save_queries" => TRUE);


$db["production"] = array("dsn"  => "",
"hostname" => "valucart.cvcf6fj8p13n.eu-central-1.rds.amazonaws.com", 
"port" => "3306",
"username" => "production" ,
"password" => "(Production#115)",
"database" => "valucart",
"dbdriver" => "mysqli",
"pconnect" => FALSE,
"db_debug" => (ENVIRONMENT !== "production"),
"cache_on" => FALSE,
"cachedir" => "",
"char_set" => "utf8",
"dbcollat" => "utf8_general_ci",
"swap_pre" => "",
"encrypt" => FALSE,
"compress" => FALSE,
"stricton" => FALSE,
"failover" => array(),
"save_queries" => TRUE);




