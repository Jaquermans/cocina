<?php
    declare(strict_types=1);
    namespace cocina;//General namespace
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    abstract class abstractApp
    {
        protected $app;

        public function __construct()
        {
            $settings = array(
                'displayErrorDetails' => TRUE,
                'addContentLengthHeader' => FALSE,
            );
            $this->app = new \Slim\App(['settings' => $settings]);//Starting Slim App
            $container = $this->app->getContainer();
            $container['logger'] = $this->setLogger($container);

            $this->app->map(['GET','POST'],'/{endpoint}[/{params:.*}]', function (Request $request, Response $response) {
                $endpoint = $request->getAttribute('endpoint');

                $newResponse = $response->withHeader('Content-type', 'application/json');
                $newResponse = $response->withJson($endpoint);
                return $newResponse;
            });
        }

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
