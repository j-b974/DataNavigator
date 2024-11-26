<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

require_once('../vendor/autoload.php');

// the URL to the local Selenium Server
$host = 'http://localhost:4444/wd/hub';

// to control a Chrome instance
$capabilities = DesiredCapabilities::chrome();

// define the browser options
$chromeOptions = new ChromeOptions();
// to run Chrome in headless mode [pas de fenetre chrome]
$chromeOptions->addArguments([ '--no-sandbox', '--disable-dev-shm-usage','--remote-debugging-port=9222']); // <- comment out for testing

// register the Chrome options
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

// initialize a driver to control a Chrome instance
$driver = RemoteWebDriver::create($host, $capabilities , 120000);

// maximize the window to avoid responsive rendering
$driver->manage()->window()->maximize();

// open the target page in a new tab
$driver->get('http://195.35.2.151:8888/connection');

$valueNameInputLogin = "username";
$inputLogin = $driver->findElement(WebDriverBy::name($valueNameInputLogin));
$inputLogin->sendKeys('admin@admin.fr');

$valueNameInputMdp = "password";
$inputMdp = $driver->findElement(WebDriverBy::name($valueNameInputMdp));
$inputMdp->sendKeys('admin');

// Cliquer sur le bouton de connexion
$loginButton = $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'));
$loginButton->click();

// Attendre que la page d'accueil apparaisse
$driver->wait(10, 500)->until(
    WebDriverExpectedCondition::titleContains('administration')
);

// extract the HTML page source and print it
$html = $driver->getPageSource();
echo $html;

// close the driver and release its resources
//$driver->close();
//$driver->quit();