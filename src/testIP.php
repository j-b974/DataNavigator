<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;

require_once('../vendor/autoload.php');

// the URL to the local Selenium Server
$host = 'http://localhost:4444/';
$sessionID = '2d8b6b4ab6d75a7f0cd695bf2455c71e';

// to control a Chrome instance
$capabilities = DesiredCapabilities::chrome();

// define the browser options
$chromeOptions = new ChromeOptions();

// to run Chrome in headless mode [pas de fenetre chrome]
//$chromeOptions->addArguments(['--headless']); // <- comment out for testing

// register the Chrome options
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

try {
    // initialize a driver to control a Chrome instance
    $driver = RemoteWebDriver::createBySessionID($sessionID, $host);

    // maximize the window to avoid responsive rendering
    //$driver->manage()->window()->maximize();

    // Ouvrir une nouvelle fenêtre à l’extension (nécessite l’ID de l’extension)
    $driver->get("https://www.mon-ip.com/");
    sleep(4);

    // Récupérer l'élément contenant l'IP et afficher son texte
    $ipElement = $driver->findElement(WebDriverBy::cssSelector('#ip4'));
    echo "Votre IP : " . $ipElement->getText();

    // Close the browser
    $driver->quit();
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
