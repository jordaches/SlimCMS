<?php


namespace Workspace\Library;

use Slim\Http\Request;
use Slim\Http\Response;

class EnforceLogin
{
    private $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $req, Response $res, $next)
    {
        if($this->container->auth->isLoggedIn()){
            return $next($req,$res);
        }
        else{
            $this->container->flash->addMessage('error','Login required!');
            return $res->withRedirect($this->container->router->pathFor('auth.loginRender'));
        }
    }
}