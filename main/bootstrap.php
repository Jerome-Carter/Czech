<?php

use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/PHPMailer/PHPMailerAutoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

$container['czech'] = function ($container) {
    return new \Czech\Czech();
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../pages', [
        'cache' => false
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('student', [
        'state' => $container->student->getState(),
        'data' => $container->student->getData(),
        'rank' => $container->student->getRank()
    ]);

    // if ($lastCzech = $container->czech->lastCzech($container->student->getData()->id)) {
    //     if ($lastCzech->status == "out") {
    //         $container->flash->addMessage('error', 'You\'ve already checked out.');
    //     } else {
    //         $view->getEnvironment()->addGlobal('lastCzech', $lastCzech);
    //     }
    // } else {
    //     $view->getEnvironment()->addGlobal('lastCzech', false);
    // }
    // if ($c = $container->czech->lastCzech($container->student->getData()->id)) {
    //     if ($c->status == 'in') {
    //         if ($lc = $container->czech->lastCzechWasToday($container->student->getData()->id) && $lc->status = "in") {
    //             $view->getEnvironment()->addGlobal('lastCzech', 'Out');
    //         } else {
    //             $view->getEnvironment()->addGlobal('lastCzech', 'In');
    //         }
    //     } elseif ($c->status == 'out') {
    //         if ($lc = $container->czech->lastCzechWasToday($container->student->getData()->id) && $lc->status = "out") {
    //             $view->getEnvironment()->addGlobal('lastCzech', false);
    //         } else {
    //             $view->getEnvironment()->addGlobal('lastCzech', 'In');
    //         }
    //     }
    // } else {
    //     $status = "in";
    // }

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$container['config'] = function ($container) {
    return \Noodlehaus\Config::load(__DIR__ . '/../config/main.php');
};

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->config->get('db'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['validator'] = function ($container) {
    return new \Czech\Validation\Validator;
};

$container['phpmailer'] = function ($container) {
    $mailer = new PHPMailer();
    $mailer->IsSMTP();
    $mailer->CharSet = 'UTF-8';
    $mailer->Host = "smtp.office365.com";
    $mailer->SMTPAuth= true;
    $mailer->Port = 587;
    $mailer->Username = "jerome@gadgetsoftware.com";
    $mailer->Password = "Vuzu1909";
    $mailer->SMTPSecure = 'tls';
    $mailer->From = "jerome@gadgetsoftware.com";
    $mailer->FromName= "Gadget Software";
    return $mailer;
};

$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard();
};

$container['student'] = function ($container) {
    return new \Czech\Auth\Student();
};

$container['password'] = function ($container) {
    return new \Czech\Auth\Password();
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$app->add(new \Czech\Middleware\Form\Validation\ErrorMiddleware($container));
$app->add(new \Czech\Middleware\Form\PreserveDataMiddleware($container));

//$app->add($container->csrf);

v::with('\\Czech\\Validation\\Rules\\');

require __DIR__ . '/Controllers/controllers.php';
