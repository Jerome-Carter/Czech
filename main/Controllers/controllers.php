<?php

$controllers = [
    ['Home', '/home', 'main', 'get', 'Empty'],
    ['Auth\\NewUser', '/auth/user/create', 'newUser', 'get', 'Access\\Admin'],
    ['Auth\\NewUser', '/auth/user/create', 'newUserPost', 'post', 'Access\\Admin'],
    ['Auth\\Login', '/auth/user/login', 'login', 'get', 'Access\\Public'],
    ['Auth\\Login', '/auth/user/login', 'loginPost', 'post', 'Access\\Public'],
    ['Auth\\Login', '/auth/user/logout', 'logout', 'get', 'Access\\User'],
    ['Auth\\Settings', '/auth/user/settings', 'settings', 'get', 'Access\\User'],
    ['Auth\\Settings', '/auth/user/settings', 'settingsPost', 'post', 'Access\\User'],
    ['Auth\\ManageInterns', '/auth/interns/manage', 'manageInterns', 'get', 'Access\\Admin'],
    ['Auth\\ManageInterns', '/auth/intern/delete/{student}', 'deleteIntern', 'get', 'Access\\Admin'],
    ['Auth\\ForgotPw', '/auth/user/pwc', 'forgotPw', 'get', 'Access\\Public'],
    ['Auth\\ForgotPw', '/auth/user/pwc', 'forgotPwPost', 'post', 'Access\\Public'],
    ['Czech\\InAndOut', '/intern/check', 'czech', 'post', 'Access\\Student'],
    ['Czech\\Log', '/timesheet/view', 'log', 'get', 'Access\\Admin'],
    ['Czech\\Log', '/intern/view/{user}', 'spec', 'get', 'Access\\Admin']
];

$known_controllers = [];

foreach ($controllers as $info) {

    if (!array_key_exists($info[0], $known_controllers)) {
        $known_controllers[$info[0]];
        $container[$info[0] . 'Controller'] = function ($container) use ($info) {
            $class = "\\Czech\\Controllers\\" . $info[0] . "Controller";
            return new $class($container);
        };
    }

    $mw = "\\Czech\\Middleware\\" . $info[4] . "Middleware";
    $app->{$info[3]}($info[1], $info[0] . 'Controller:' . $info[2])->setName($info[2])->add(new $mw($container));//->add(new \Czech\Middleware\Form\CSRFMiddleware($container));

}

$container["StripeController"] = function ($container) {

	return new \Czech\Controllers\StripeController($container);

};

$app->post('/epirts', 'StripeController:stripe');
