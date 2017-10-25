<?php
    declare(strict_types=1);
    namespace cocina;//General namespace

    abstract class abstractApp
    {
        protected $app;

        public function bootstrap()
        {
            return $this->app;
        }

        public function setLogger($c)
        {
            $logger = new \Monolog\Logger('cocina_logger');
            $file_handler = new \Monolog\Handler\StreamHandler(__DIR__.'../logs/app.log');
            $logger->pushHandler($file_handler);
            return $logger;
        }
    }
