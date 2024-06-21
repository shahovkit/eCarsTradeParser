<?php
declare(strict_types=1);

namespace Controllers;

class InitializationController {

    public function initialize()
    {
        view('loader');
    }
}