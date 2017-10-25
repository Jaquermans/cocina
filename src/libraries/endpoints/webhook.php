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
            } elseif ($req->isGet()) {
                return $this->verifyToken($req->getQueryParams());
            }
            return 'Invalid Request';
        }

        private function processPost($body)
        {
            if($body['object']==='page'){
                foreach($body['entry'] as $entry){
                    $webhookEvent = $entry['messaging'][0]['message'];
                }
                return $webhookEvent.' & EVENT_RECEIVED';
            }
        }

        private function verifyToken($params)
        {
            $verify_token = '1234';

            if(isset($params['hub_mode']) && $params['hub_verify_token']){
                if($params['hub_mode']==='subscribe' && $params['hub_verify_token']===$verify_token){
                    return $params['hub_challenge'];
                }
            }
        }
    }
