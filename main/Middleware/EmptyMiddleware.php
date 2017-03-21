<?php

namespace Czech\Middleware;

class EmptyMiddleware extends MainMiddleware {

    public function __invoke($request, $response, $next) {
        $response = $next($request, $response);
        return $response;
    }
    
}