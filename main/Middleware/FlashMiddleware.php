<?php

namespace Czech\Middleware;

class FlashMiddleware extends \Czech\Middleware\MainMiddleware {

    public function __invoke($request, $response, $next) {
        $this->view->getEnvironment()->addGlobal();
        $response = $next($request, $response);
        return $response;
    }

}