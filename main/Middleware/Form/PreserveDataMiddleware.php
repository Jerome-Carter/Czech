<?php

namespace Czech\Middleware\Form;

class PreserveDataMiddleware extends \Czech\Middleware\MainMiddleware {

    public function __invoke($request, $response, $next) {
        $this->view->getEnvironment()->addGlobal('preserved_data', $_SESSION['preserved_data']);
        $_SESSION['preserved_data'] = $request->getParams();
        $response = $next($request, $response);
        return $response;
    }

}