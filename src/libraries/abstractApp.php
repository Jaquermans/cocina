<?php
    declare(strict_types=1);
    namespace cocina;//General namespace
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use cocina\endpoints\message;
    use cocina\endpoints\webhook;

    abstract class abstractApp
    {
        protected $app;

        protected $container;

        public function __construct()
        {
            $this->app = new \Slim\App(['settings' => $this->setSettings()]);//Starting Slim App
            $this->container = $this->app->getContainer();
            $this->container['logger'] = $this->setLogger($this->container);
            $this->setEndPoints();
            $this->setRoutes();
        }

        private function setSettings()
        {
            return array(
                'displayErrorDetails' => TRUE,
                'addContentLengthHeader' => FALSE,
            );
        }

        public function bootstrap()
        {
            return $this->app;
        }

        private function setLogger($c)
        {
            $logger = new \Monolog\Logger('cocina_logger');
            $file_handler = new \Monolog\Handler\StreamHandler(__DIR__.'/../logs/app.log');
            $logger->pushHandler($file_handler);
            return $logger;
        }

        private function setEndPoints()
        {
            $this->container['message'] = function($c){
                return new message();
            };
            $this->container['webhook'] = function($c){
                return new webhook();
            };
        }

        private function setRoutes()
        {
            $this->app->map(['GET','POST'],'/{endpoint}[/{params:.*}]', function (Request $request, Response $response) {
                $endpoint = $request->getAttribute('endpoint');
                if($this->has($endpoint)) {
                    list($status,$data) = $this->$endpoint->processReq($request);
                }
                $newResponse = $response->withHeader('Content-type', 'application/json');
                $newResponse = $response->withJson($data,$status);
                return $newResponse;
            });
        }
    }
