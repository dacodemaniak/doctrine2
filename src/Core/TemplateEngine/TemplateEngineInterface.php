<?php
namespace Core\TemplateEngine;

interface TemplateEngineInterface {
    public function render(string $templateName, array $model);
}

