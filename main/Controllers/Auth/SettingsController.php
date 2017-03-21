<?php

namespace Czech\Controllers\Auth;

use Czech\Models\Student;
use Respect\Validation\Validator as v;
use Czech\Controllers\MainController;

class SettingsController extends MainController{

    public function settings($request, $response) {
        return $this->view->render($response, 'auth/settings.twig');
    }

    public function settingsPost($request, $response) {
        $validation = $this->validator->validate($request, [
            'password' => ['Password', v::notEmpty()->length(6, 10)]
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('settings'));
        }

        $this->password->change($this->student->getData()->id, $request->getParam('password'));
        $this->student->logout();
        $this->flash->addMessage('pass', 'Login with your ne password.');
        return $response->withRedirect($this->router->pathFor('login'));
    }

}