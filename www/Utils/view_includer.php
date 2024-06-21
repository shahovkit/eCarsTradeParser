<?php
declare(strict_types=1);

function view($file, $params = []): void
{
    extract($params, EXTR_SKIP);
    include 'Views/' . $file . '.php';
}