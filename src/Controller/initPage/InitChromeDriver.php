<?php

namespace App\Controller\initPage;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;

class InitChromeDriver
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;
    public function __construct()
    {
        $host = 'http://localhost:4444/wd/hub';

        // to control a Chrome instance
        $capabilities = DesiredCapabilities::chrome();

        // chemin chrome extention
        $pathExtentionOgameHack = dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'extension_chrome'.DIRECTORY_SEPARATOR.'OgameHack.crx';

        // id extention "
        $idExtention = "ljckpgpjoikgafpnpjglpjnefelkfpkh";

        // define the browser options
        $chromeOptions = new ChromeOptions();
        // to run Chrome in headless mode [pas de fenetre chrome]

        // ajoute extention ogameHack
        $chromeOptions->addExtensions([$pathExtentionOgameHack]);

        $chromeOptions->addArguments(['--no-sandbox', '--disable-dev-shm-usage','--remote-debugging-port=9222']); // <- comment out for testing

        // register the Chrome options
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        // initialize a driver to control a Chrome instance
        $driver = RemoteWebDriver::create($host, $capabilities , 5000);

        // maximize the window to avoid responsive rendering
        $driver->manage()->window()->maximize();
        $this->driver = $driver;
    }

    /**
     * @description : open the target page in a new tab
     */
    public function getPage(string $url, bool $loadCookie = false):RemoteWebDriver
    {
        $driver = $this->driver->get($url);
        if($loadCookie)
        {
            $this->addCookiesToSession();
            // Actualiser la page pour utiliser les cookies
            $this->driver->navigate()->refresh();
        }
        try{
            $wait = new WebDriverWait($this->driver, 10); // 10 secondes max
            $wait->until(function($driver) {
                return $driver->executeScript("return document.readyState") === "complete";
            });
        }catch(\Exception $e){
            echo"page non recharge". $e->getMessage();
        }

        return $driver;
    }

    /**
     * @description : Récupérer et enregistrer les cookies
     * @return void
     */
    public function saveCookie()
    {
        $cookies = $this->driver->manage()->getCookies();
        // Transformez les objets Cookie en tableaux associatifs
        $cookiesArray = array_map(function ($cookie) {
            return $cookie->toArray();
        }, $cookies);

        // Encodez les tableaux en JSON
        $jsonCookies = json_encode($cookiesArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents('cookie.json', $jsonCookies);
        echo "Cookies sauvegardés.\n";
    }
    public function addCookiesToSession()
    {
        if (file_exists('cookie.json')) {
            $cookies = json_decode(file_get_contents('cookie.json'), true);
            echo "Cookies en chargement !!! \n";

            foreach ($cookies as $cookie) {
                $this->driver->manage()->addCookie($cookie);
            }
            echo "Cookies chargés.\n";
        }
    }
    public function getChromeDriver():RemoteWebDriver
    {
        return $this->driver;
    }
}