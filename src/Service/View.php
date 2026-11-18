<?php

declare(strict_types=1);

namespace App\Service;

use Smarty\Smarty;

class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);

        $this->smarty = new Smarty();

        $this->smarty->setTemplateDir(
            $root . '/templates'
        );

        $this->smarty->setCompileDir(
            $root . '/storage/templates_c'
        );

        $this->smarty->setCacheDir(
            $root . '/storage/cache'
        );
    }

    public function render(
        string $template,
        array $data = []
    ): void {
        $this->smarty->assign($data);

        $this->smarty->display($template);
    }
}