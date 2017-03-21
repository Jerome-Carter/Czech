<?php

namespace Czech\Middleware\Access;

class StudentMiddleware extends \Czech\Middleware\MainMiddleware {

    public function __invoke($request, $response, $next) {
        if ($this->student->getState()) {
            if ($this->student->getRank()) {
                return $response->withRedirect($this->router->pathFor('main'));
            }
        } else {
            return $response->withRedirect($this->router->pathFor('main'));
        }
        $response = $next($request, $response);
        return $response;
    }

}