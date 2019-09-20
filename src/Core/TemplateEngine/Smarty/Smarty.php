<?php
namespace Core\TemplateEngine\Smarty;

use Core\TemplateEngine\TemplateEngineInterface;

/**
 * @name Smarty
 * @author IDea Factory
 * @package Core\TemplateEngine\Smarty
 * @version 1.0.0
 *        
 */

require_once(__DIR__ . "/../../../../vendor/smarty/smarty/libs/Smarty.class.php");

final class Smarty extends \Smarty implements TemplateEngineInterface
{

    /**
     */
    public function __construct(){
        $this->_setup();
    }
    
    public function render(string $templateName, array $modele) {
        $this->assign($modele);
        $this->display($templateName);
    }
    
    private function _setup() {
        // Sets folders of Smarty Template Engine
        $this->setTemplateDir(__DIR__ . "/../../Templates");
        $this->setCompileDir(__DIR__ . "/../../../var/templates_c");
        $this->setCacheDir(__DIR__ . "../../../var/cache");
        $this->setConfigDir(__DIR__ . "../../../var/configs");
    }
}

