<?php

namespace Czech\Controllers\Auth;

use Czech\Models\Student;
use Respect\Validation\Validator as v;
use Czech\Controllers\MainController;

class LoginController extends MainController{

    public function logout($request, $response) {
        $this->flash->addMessage('pass', 'You\'re now logged out.');
        $this->student->logout();
        return $response->withRedirect($this->router->pathFor('main'));
    }

    public function login($request, $response) {
        return $this->view->render($response, 'auth/login.twig');
    }

    public function loginPost($request, $response) {
        $validation = $this->validator->validate($request, [
            'email' => ['Email', v::noWhitespace()->notEmpty()->email()],
            'password' => ['Password', v::noWhitespace()->notEmpty()]
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('login'));
        }

        if ($user = Student::where('email', $request->getParam('email'))->first()) {
            if (password_verify($request->getParam('password'), $user->password)) {
                $this->student->login($user);
                $this->flash->addMessage('pass', 'You\'re now logged in.');
                return $response->withRedirect($this->router->pathFor('main'));
            }
        }

        $this->flash->addMessage('error', 'Those details are invalid.');
        return $response->withRedirect($this->router->pathFor('login'));
        
    }

}