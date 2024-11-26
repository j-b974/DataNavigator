<?php

namespace App\Controller\element;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class LoginPage
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;
    private $element = [];

    private $btnClick ;

    public function __construct($driver){
        $this->driver = $driver;
    }

    /**
     * @param string $selector Attention tag name
     * @param string $value
     * @return self
     */
    public function addInput(string $selector, string $value): self {
        if(!in_array($selector, $this->element)){
            $this->element[$selector] = $value;
        }
        return $this;
    }
    public function AppliqueValue()
    {
        foreach($this->element as $selector => $value){
            $input = $this->driver->findElement(WebDriverBy::name($selector));
            $input->sendKeys($value);
        }
    }
    public function getButton(string $selector){
        return $this->driver->findElements(WebDriverBy::cssSelector($selector));
    }

    /**
     * @description check le check-box selectionner
     * @param string $selector
     * @return void
     *
     */
    public function checkBoxCheck(string $selector):void
    {
        $this->driver->findElement(WebDriverBy::cssSelector($selector))->click();
    }

}