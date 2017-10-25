<?php
    declare(strict_types=1);
    use cocina\production\app;

    require __DIR__.'/../vendor/autoload.php';

    $app = (new app())->bootstrap();
    $app->run();
