<?php

namespace App;

use App\Controller\initPage\InitChromeDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
use Dotenv\Dotenv;

class LoginOgame {

    public static function init($chromeDriver) {

        $webHome = $chromeDriver->getPage($_ENV['DOMAIN'].'fr_FR/hub', true);

        // recuperer le bouton puis clique dessus
        $waitBtn = new WebDriverWait($webHome,5);

        try {
            $btn = $waitBtn->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector("button.button-default.button-md")));
            $btn->click();
            echo "connexion ok !!!";
        }catch (\Exception $e){

            echo "Connexion échouée : " . $e->getMessage();

            // click accepter les coockie
            $btnsBannier = $webHome->findElements(WebDriverBy::cssSelector('button.cookiebanner5'));
            if($btnsBannier){
                $btnsBannier[1]->click();
            }


            // Utilise le formulaire connection
            $formLogin = new \App\Controller\element\LoginPage($webHome);
            sleep(1);
            // click sur element "se connecter"
            $webHome->findElement(webDriverBy::cssSelector("ul.tabsList>li"))->click();
            sleep(1);
            // entre les information de connection
            $formLogin->addInput('email', $_ENV['LOGIN_USERNAME'])
                ->addInput('password', $_ENV['LOGIN_PASSWORD']);
            $formLogin->AppliqueValue();

            // Cliquer sur le bouton de connexion
            $btn = $formLogin->getButton('button[type="submit"]');
            $btn[0]->click();

            // Attendre que la page d'accueil apparaisse
            //$pageCon->wait(10, 500)->until(
            //WebDriverExpectedCondition::titleContains('administration')
            //);
            // Attendre que le document soit complètement chargé
            try{
                $wait = new WebDriverWait($chromeDriver->getChromeDriver(), 10); // 10 secondes max
                $wait->until(function($Driver) {
                    return $Driver->executeScript("return document.readyState") === "complete";
                });
            }catch(WebDriverException $e){
                echo "hors delay rechargement home !!!".PHP_EOL;
            }

            $chromeDriver->saveCookie();
        }
        return $webHome;
            //$pageCon->close();
            //$btn= $pageCon->findElement(WebDriverBy::cssSelector(".logout button[type='submit']"));
    }
}

