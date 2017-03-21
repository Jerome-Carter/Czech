<?php

namespace Czech\Controllers;

class HomeController extends MainController{

    public function main($request, $response) {
        return $this->view->render($response, 'Home.twig');
    }

}