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

    final class webhookTest extends TestCase
    {
        private $app;

        public function setUp()
        {
            $this->app = (new testApp())->bootstrap();
        }

        public function testReadMessage()
        {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI'    => '/webhook',
                'CONTENT_TYPE'   => 'application/json',
            ]);
            $data = [
                'object'=>'page',
                'entry'=>[
                    [
                        'messaging'=>[
                            ['message'=>'TEST_MESSAGE']
                        ]
                    ]
                ],
            ];
            $body = new RequestBody();
            $body->write(json_encode($data));
            $req = Request::createFromEnvironment($env)->withBody($body);
            $this->app->getContainer()['request'] = $req;//Include the Request in the App
            $response = $this->app->run(TRUE);//TRUE is to hide body

            //Test 1
            $this->assertSame(200,$response->getStatusCode());

            //Test 2
            $result = json_decode($response->getBody()->__toString(), true);
            $this->assertEquals('TEST_MESSAGE & EVENT_RECEIVED',$result);
        }

        public function testMessageObjectNotPage()
        {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI'    => '/webhook',
                'CONTENT_TYPE'   => 'application/json',
            ]);
            $data = [
                'object'=>'something',
                'entry'=>[
                    [
                        'messaging'=>[
                            ['message'=>'TEST_MESSAGE']
                        ]
                    ]
                ],
            ];
            $body = new RequestBody();
            $body->write(json_encode($data));
            $req = Request::createFromEnvironment($env)->withBody($body);
            $this->app->getContainer()['request'] = $req;//Include the Request in the App
            $response = $this->app->run(TRUE);//TRUE is to hide body

            //Test 1
            $this->assertSame(404,$response->getStatusCode());

            //Test 2
            $result = json_decode($response->getBody()->__toString(), true);
            $this->assertEquals(NULL,$result);
        }

        public function testVerifyWebhook()
        {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/webhook',
                'QUERY_STRING' => 'hub.verify_token=1234&hub.challenge=CHALLENGE_ACCEPTED&hub.mode=subscribe',
                'CONTENT_TYPE'   => 'application/json',
            ]);
            $body = new RequestBody();
            $req = Request::createFromEnvironment($env)->withBody($body);
            $this->app->getContainer()['request'] = $req;//Include the Request in the App
            $response = $this->app->run(TRUE);//TRUE is to hide body

            //Test 1
            $this->assertSame(200,$response->getStatusCode());

            //Test 2
            $result = json_decode($response->getBody()->__toString(), true);
            $this->assertEquals('CHALLENGE_ACCEPTED',$result);
        }

        public function testVerifyTokenFails()
        {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/webhook',
                'QUERY_STRING' => 'hub.verify_token=12345&hub.challenge=CHALLENGE_ACCEPTED&hub.mode=subscribe',
                'CONTENT_TYPE'   => 'application/json',
            ]);
            $body = new RequestBody();
            $req = Request::createFromEnvironment($env)->withBody($body);
            $this->app->getContainer()['request'] = $req;//Include the Request in the App
            $response = $this->app->run(TRUE);//TRUE is to hide body

            //Test 1
            $this->assertSame(403,$response->getStatusCode());

            //Test 2
            $result = json_decode($response->getBody()->__toString(), true);
            $this->assertEquals(NULL,$result);
        }
    }
