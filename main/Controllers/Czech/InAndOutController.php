<?php

namespace Czech\Controllers\Czech;

use Czech\Models\Czech;
use Respect\Validation\Validator as v;

class InAndOutController extends \Czech\Controllers\MainController {

    public function czech($request, $response) {
        $validation = $this->validator->validate($request, [
            'uid' => ['User', v::noWhitespace()->notEmpty()->numeric()->user($this->container)],
            'latitude' => ['Latitude', v::noWhitespace()->notEmpty()],
            'longitude' => ['Longitude', v::noWhitespace()->notEmpty()]
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'An error occured.');
            return $response->withRedirect($this->router->pathFor('main'));
        }

        if ($c = $this->czech->lastCzech($this->student->getData()->id)) {
            if ($c->status == 'in') {
                if ($lc = $this->czech->lastCzechWasToday($this->student->getData()->id) && $lc->status = "in") {
                    $status = "out";
                } else {
                    $status = 'in';
                }
            } elseif ($c->status == 'out') {
                if ($lc = $this->czech->lastCzechWasToday($this->student->getData()->id) && $lc->status = "out") {
                    $this->flash->addMessage('error', 'You\'ve already checked out.');
                    return $response->withRedirect($this->router->pathFor('main'));
                } else {
                    $status = 'in';
                }
            }
        } else {
            $status = "in";
        }

        $user = Czech::create([
            'student_id' => $request->getParam('uid'),
            'status' => $status,
            'lat' => $request->getParam('latitude'),
            'long' => $request->getParam('longitude')
        ]);

        $this->flash->addMessage('pass', 'See you later.');
        return $response->withRedirect($this->router->pathFor('main'));
    }

}