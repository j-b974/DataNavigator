<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

require_once('../vendor/autoload.php');

// Chemin vers votre serveur Selenium
$host = 'http://localhost:4444/wd/hub';

// id extension
$idExtension = "fcfhplploccackoneaefokcmbjfbken" ;

$pathExtentionVPN = dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'extension_chrome'.DIRECTORY_SEPARATOR.'2.0.10_0.crx';

// Créez les options pour Chrome
$chromeOptions = new ChromeOptions();

// Indiquez à Chrome de charger l’extension VPN
$chromeOptions->addExtensions([$pathExtentionVPN]);

// Désactivez le sandboxing pour éviter les erreurs d'initialisation dans certains environnements
$chromeOptions->addArguments(['--no-sandbox', '--disable-dev-shm-usage']);

// Créez les capacités pour utiliser Chrome avec les options définies
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

$proxy = null;
$port = null;
// Lancez le navigateur
$driver = RemoteWebDriver::create($host, $capabilities,null, null,$proxy,$port  );

try {

    // Ouvrez un site pour initialiser la session
    $driver->get('https://www.mon-ip.com/');
    sleep(3); // Pause pour charger l'extension

    // Récupérer l'élément contenant l'IP et afficher son texte
    $ipElement = $driver->findElement(WebDriverBy::cssSelector('#ip4'));
    echo "Votre IP : " . $ipElement->getText();

    // Ouvrez le popup de l’extension
    //$driver->get("chrome-extension://$idExtension/popup.html");
    //sleep(4); // Pause pour le chargement du popup

    //$html = $driver->getPageSource();
    //echo $html;

} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage();
} finally {
    // Fermez le navigateur
    $driver->quit();
}
