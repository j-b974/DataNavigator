<?php

require_once('../vendor/autoload.php');

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

$host = 'http://localhost:4444/';

$pathExtentionVPN = dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'extension_chrome'.DIRECTORY_SEPARATOR.'3.1.7_0.crx';

$idExtention = "majdfhpaihoncoakbjgbdhglocklcgno";
$ud = "lmemhnhlhjipenikeogplkcojdpabego";
// to control a Chrome instance
$capabilities = DesiredCapabilities::chrome();

// define the browser options
$chromeOptions = new ChromeOptions();
// to run Chrome in headless mode [pas de fenetre chrome]
//$chromeOptions->addArguments(['--headless']); // <- comment out for testing
// add option extension
$chromeOptions->addExtensions([$pathExtentionVPN]);
// register the Chrome options
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

// initialize a driver to control a Chrome instance
$driver = RemoteWebDriver::create($host, $capabilities);

// Sauvegardez ces valeurs pour reconnecter à cette session
$sessionId = $driver->getSessionID();
echo "session ID => $sessionId".PHP_EOL;
$executorUrl = $driver->getCommandExecutor()->getAddressOfRemoteServer();
echo "url => $executorUrl".PHP_EOL;