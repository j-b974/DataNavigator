<?php


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

$webHome = $chromeDrive->getPage($_ENV['DOMAIN'], true);

// recuperer le bouton puis clique dessus
$waitBtn = new WebDriverWait($webHome,5);

try {
    $btn = $waitBtn->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector("a#user_tag")));
    echo "connexion ok !!!";
}catch (\Exception $e){

    echo "Connexion échouée : " . $e->getMessage();
    $webSignin = $chromeDrive->getPage($_ENV['DOMAIN'].'login');
    // initiliser une connection !
    $formLogin = new \App\Controller\element\LoginPage($webSignin);
    $formLogin->addInput('username', $_ENV['LOGIN_USERNAME'])
        ->addInput('password', $_ENV['LOGIN_PASSWORD'])
        ->addInput('remember','true');
    $formLogin->AppliqueValue();
    // Cliquer sur le bouton de connexion
    $btn = $formLogin->getButton('button[type="submit"]');
    $btn[1]->click();

// Attendre que la page d'accueil apparaisse
//$pageCon->wait(10, 500)->until(
    //WebDriverExpectedCondition::titleContains('administration')
//);
    sleep(2);
    $chromeDrive->saveCookie();
}
//$pageCon->close();
//$btn= $pageCon->findElement(WebDriverBy::cssSelector(".logout button[type='submit']"));