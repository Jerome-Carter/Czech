<?php

namespace Czech\Controllers\Auth;

use Czech\Models\Student;
use Respect\Validation\Validator as v;
use Czech\Controllers\MainController;

class NewUserController extends MainController{

    public function newUser($request, $response) {
        return $this->view->render($response, 'auth/newUser.twig');
    }

    public function newUserPost($request, $response) {

        $validation = $this->validator->validate($request, [
            'first_name' => ['First Name', v::noWhitespace()->notEmpty()->alpha()],
            'last_name' => ['Last Name', v::noWhitespace()->notEmpty()->alpha()],
            'email' => ['Email', v::noWhitespace()->notEmpty()->email()->unique($this->container, 'students', 'email')],
            'auth' => ['Account Type', v::noWhitespace()->numeric()]
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('newUser'));
        }

        $user = Student::create([
            'first_name' => $request->getParam('first_name'),
            'last_name' => $request->getParam('last_name'),
            'email' => $request->getParam('email'),
            'password' => password_hash($pass = rand(100000, 10000000), PASSWORD_DEFAULT, ['cost' => 11]),
            'is_admin' => $request->getParam('auth')
        ]);

        $mail = $this->phpmailer;
        $mail->isHTML(true);
        $mail->Subject = "Welcome!";
        $mail->Body = "<div leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\">
                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"background-color:#ffffff; border:10px solid #eeeeee; margin:0 auto 0 auto\">
                <tbody>
                <tr>
                    <td bgcolor=\"#000000;\" height=\"40\" style=\"background-color:#000000\"></td>
                    <td valign=\"middle\" bgcolor=\"#000000;\" height=\"40\" style=\"text-transform:uppercase; text-align:right; color:#888888; font-weight:bold; font-size:16px; font-family:Helvetica,Arial,sans-serif\">
                        <span id=\"0.7605549213842255\" class=\"highlight\" name=\"searchHitInReadingPane\">Welcome!</span>
                    </td>
                    <td bgcolor=\"#000000;\" height=\"40\" style=\"background-color:#000000\"></td>
                </tr>
                <tr>
                    <td height=\"50\"></td>
                    <td height=\"50\"></td>
                    <td height=\"50\"></td>
                </tr>
                <tr>
                    <td width=\"40\"></td>
                    <td style=\"font-family:Georgia,Times New Roman,serif; font-size:18px; color:#888888\">
                        <h1 style=\"color:#555555; font-size:28px; font-weight:bold; font-family:Helvetica,Arial,sans-serif\">
                            Hello " . $request->getParam('first_name') . ",
                        </h1>
                        <p style=\"color:#888888; font-size:18px; line-height:28px; font-family:Georgia,Times New Roman,serif; text-decoration:none\">
                            Welcome to the PPCS Intern App, an app created and developed by Jerome Carter for the PPCS interns.
                        </p>
                        <p style=\"color:#888888; font-size:18px; line-height:28px; font-family:Georgia,Times New Roman,serif; text-decoration:none\">
                            The purpose of this app is to replace timesheets. Now, when you arrive at or leave your internship, you simply open the app and check in or out.
                        </p>
                        <p style=\"color:#888888; font-size:18px; line-height:28px; font-family:Georgia,Times New Roman,serif; text-decoration:none\">
                            To login, use your email and " . $pass . ", your password.
                        </p>
                        <p style=\"color:#888888; font-size:18px; line-height:28px; font-family:Georgia,Times New Roman,serif; text-decoration:none\">
                            Please contact Ms. Pozmantier if you have any questions.
                        </p>
                    </td>
                    <td width=\"40\"></td>
                </tr>
                <tr>
                    <td height=\"50\"></td>
                    <td height=\"50\"></td>
                    <td height=\"50\"></td>
                </tr>
                <tr>
                    <td bgcolor=\"#eeeeee\">
                </td>
                <td bgcolor=\"#eeeeee\" height=\"120\" style=\"background-color:#eeeeee; height:120px; vertical-align:middle\">
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width:100%\">
                        <tbody>
                            <tr>
                                <td>
                                    <span style=\"color:#888888; font-weight:bold; font-size:12px; font-family:Helvetica,Arial,sans-serif\"></span>
                                    <br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td bgcolor=\"#eeeeee\"></td>
                </tr>
                </tbody>
            </table>
            </div>";
        $mail->addAddress($request->getParam('email'));

        if(!$mail->send()){
            $this->flash->addMessage('error', 'Failed to send email.');
            return $response->withRedirect($this->router->pathFor('main'));
        }

        $this->flash->addMessage('pass', 'User created.');
        return $response->withRedirect($this->router->pathFor('manageInterns'));

    }

}