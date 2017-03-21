<?php

namespace Czech\Controllers;

class StripeController extends MainController{

    public function stripe($request, $response) {
    	$mail = $this->phpmailer;
	// Be sure to replace this with your actual test API key (switch to the live key later)
	//sk_test_n2m7PdwK4wT1E78hlXjKDjhA
	\Stripe\Stripe::setApiKey("sk_live_QhGYZQghJCTgN9qeQanHEBVw");
	// Retrieve the request's body and parse it as JSON
	$input = @file_get_contents("php://input");
	$event_json = json_decode($input);
	$event = \Stripe\Event::retrieve($event_json->id);
	if (isset($event) && $event->type == "invoice.payment_failed") {
	    $customer = \Stripe\Customer::retrieve($event->data->object->customer);
	    $email = $customer->email;
	    // Sending your customers the amount in pennies is weird, so convert to dollars
	    $amount = sprintf('$%0.2f', $event->data->object->amount_due / 100.0);
	    $mail->isHTML(true);
	    $mail->Subject = "Payment Declined";
	    $mail->Body = $this->view->fetch("stripe/email.twig", ['amount' => $amount, 'email' => $email]);
	    $mail->addAddress($email);
	    //$mail->addBCC("monroe@gadgetsoftware.com");
	} else {
 	    if ($j = json_encode($input)) { return $response->w; }
	    return $response->withStatus(403);
	}

	if(!$mail->send()){
	    return $response->withStatus(501);
	}else{
	    return $response->withStatus(200);
	}
    }

}
