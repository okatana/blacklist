<?php

//require_once '/vendor/autoload.php';
require_once 'vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\LogglyHandler;
use Monolog\Formatter\LogglyFormatter;

class MyLogger
{
  private $logger;
  function __construct($logger_name){
    $this->logger = new \Monolog\Logger($logger_name);
    $browserInfoHanlder = new \Monolog\Handler\BrowserConsoleHandler(\Monolog\Logger::INFO);
    $browserErrorHanlder = new \Monolog\Handler\BrowserConsoleHandler(\Monolog\Logger::ERROR);
    $this->logger->pushHandler($browserInfoHanlder);
    $this->logger->pushHandler($browserErrorHanlder);
  }
  function info($message){
    $this->logger->info($message);
  }
  function error($message){
    $this->logger->error($message);
  }
}





