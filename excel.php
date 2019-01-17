<?php
/**
 * Created by PhpStorm.
 * User: Katanugina
 * Date: 17.01.2019
 * Time: 15:10
 */
require 'config/config.php';
require 'php/BlackListController.php';

// контроллер
$controller = new BlackListController($config);
$vids = [];
$controller->excel($vids);