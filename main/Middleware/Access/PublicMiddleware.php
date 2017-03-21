<?php

namespace Czech\Middleware\Access;

class PublicMiddleware extends \Czech\Middleware\MainMiddleware {

    public function __invoke($request, $response, $next) {
        if ($this->student->getState()) {
            return $response->withRedirect($this->router->pathFor('main'));
        }
        $response = $next($request, $response);
        return $response;
    }
    
}