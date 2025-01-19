<?php


use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
use Dotenv\Dotenv;

require_once('vendor/autoload.php');

// Spécifiez le chemin du fichier .env

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

// todo : appeller la page ogame .
//  => verifisicoiton sion connection


$chromeDrive = new \App\Controller\initPage\InitChromeDriver();

$webHome = \App\LoginOgame::init($chromeDrive);

echo "page homme ".PHP_EOL;

// accee a l'univers !
$waitBtnUnivers = new WebDriverWait($webHome,10);
try{
    $btnUnivers =  $waitBtnUnivers->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector("div.accountNavButton")));
    echo "univers ok !".PHP_EOL;
}catch(WebDriverException $e){
    echo 'pas bouton Univers: '.$e->getMessage();
}