<?php
    declare(strict_types=1);
    namespace cocina\production;//General namespace
    use cocina\abstractApp;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    final class app extends abstractApp
    {
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
    }
