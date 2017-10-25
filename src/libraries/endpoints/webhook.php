<?php
    declare(strict_types=1);
    namespace cocina\endpoints;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    final class webhook
    {
        public function processReq(Request $req)
        {
            if($req->isPost()){
                return $this->processPost($req->getParsedBody());
            }
            return 'Que tranza compa?';
        }

        private function processPost($body)
        {
            if($body['object']==='page'){
                return 'EVENT_RECEIVED';
            }
        }
    }
