<?php 
require __DIR__.'/vendor/autoload.php';
date_default_timezone_set('Africa/Lagos');

use Superirale\Spider;
use Superirale\Spider\Console\Commands;
use Symfony\Component\Console\Application;


$application = new Application();
$application->add(new Commands\CrawlCommand());
$application->add(new Commands\GreetCommand());
$application->run();