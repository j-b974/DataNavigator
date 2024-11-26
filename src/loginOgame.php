<?php

use App\Controller\initPage\InitChromeDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Dotenv\Dotenv;

require_once('../vendor/autoload.php');



// Spécifiez le chemin du fichier .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__,1));
$dotenv->load();

$driver = new InitChromeDriver();


$pageCon = $driver->getPage($_ENV['DOMAIN'], true);

$formLogin = new \App\Controller\element\LoginPage($pageCon);
$formLogin->addInput('username', $_ENV['LOGIN_USERNAME'])
    ->addInput('password', $_ENV['LOGIN_PASSWORD'])
    ->addInput('remember','true');
$formLogin->AppliqueValue();
//$formLogin->checkBoxCheck();

// Cliquer sur le bouton de connexion
$btn = $formLogin->getButton('button[type="submit"]');


$btn[1]->click();

// Attendre que la page d'accueil apparaisse
//$pageCon->wait(10, 500)->until(
    //WebDriverExpectedCondition::titleContains('administration')
//);
sleep(2);
$driver->saveCookie();
// extract the HTML page source and print it

$pageCon->quit();
