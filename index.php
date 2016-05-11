<?php 
require __DIR__.'/vendor/autoload.php';
date_default_timezone_set('Africa/Lagos');

use Superirale\Spider;
use Superirale\Spider\Console\Commands;
use Symfony\Component\Console\Application;
use RedBeanPHP\R;


$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


R::setup(getenv('database_connection_string'), getenv('database_user'), getenv('database_password'));

$application = new Application();
$application->add(new Commands\CrawlCommand());
$application->add(new Commands\GreetCommand());
$application->run();