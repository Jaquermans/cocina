<?php
    declare(strict_types=1);
    use PHPUnit\Framework\TestCase;
    use cocina\test\testApp;
    use Slim\Http\Environment;
    use Slim\Http\Request;
    use Slim\Http\Uri;
    use Slim\Http\Headers;
    use Slim\Http\RequestBody;
    use Slim\Http\UploadedFile;
    use Slim\Http\Response;

    final class messageTest extends TestCase
    {
        private $app;

        public function setUp()
        {
            $this->app = (new testApp())->bootstrap();
        }

        public function testInitialGreeting()
        {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/message',
            ]);
            $body = new RequestBody();
            $req = Request::createFromEnvironment($env)->withBody($body);
            $this->app->getContainer()['request'] = $req;//Include the Request in the App
            $response = $this->app->run();//TRUE is to hide body

            //Test 1
            $this->assertSame($response->getStatusCode(), 200);

            //Test 2
            $result = json_decode($response->getBody()->__toString(), true);
            $this->assertEquals('Que tranza compa?',$result);
        }
    }
