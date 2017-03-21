<?php

namespace Czech\Middleware\Form\Validation;

class ErrorMiddleware extends \Czech\Middleware\MainMiddleware {

    public function __invoke($request, $response, $next) {
        $this->view->getEnvironment()->addGlobal('form_errors', $_SESSION['form_errors']);
        unset($_SESSION['form_errors']);
        $response = $next($request, $response);
        return $response;
    }

}