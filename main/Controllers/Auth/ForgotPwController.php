<?php

namespace Czech\Controllers\Auth;

use Czech\Models\Student;
use Respect\Validation\Validator as v;
use Czech\Controllers\MainController;

class ForgotPwController extends MainController{

  public function forgotPw($request, $response) {
      return $this->view->render($response, 'auth/forgotPw.twig');
  }

  public function forgotPwPost($request, $response) {
      $validation = $this->validator->validate($request, [
          'email' => ['Email', v::noWhitespace()->notEmpty()->email()]
      ]);

      if ($validation->failed()) {
          return $response->withRedirect($this->router->pathFor('forgotPw'));
      }

      if ($user = Student::where('email', $request->getParam('email'))->first()) {
          $user->update(['password' => password_hash($pass = rand(100000, 10000000), PASSWORD_DEFAULT, ['cost' => 11])]);
          $mail = $this->phpmailer;
          $mail->isHTML(true);
          $mail->Subject = "Password Changed!";
          $mail->Body = "<div leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\">
                  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"background-color:#ffffff; border:10px solid #eeeeee; margin:0 auto 0 auto\">
                  <tbody>
                  <tr>
                      <td bgcolor=\"#000000;\" height=\"40\" style=\"background-color:#000000\"></td>
                      <td valign=\"middle\" bgcolor=\"#000000;\" height=\"40\" style=\"text-transform:uppercase; text-align:right; color:#888888; font-weight:bold; font-size:16px; font-family:Helvetica,Arial,sans-serif\">
                          <span id=\"0.7605549213842255\" class=\"highlight\" name=\"searchHitInReadingPane\">Your Password Was Changed!</span>
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
                              Hello " . $user->first_name . ",
                          </h1>
                          <p style=\"color:#888888; font-size:18px; line-height:28px; font-family:Georgia,Times New Roman,serif; text-decoration:none\">
                              Your new password is " . $pass . ".
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
      }

      $this->flash->addMessage('pass', 'Check your email.');
      return $response->withRedirect($this->router->pathFor('main'));

  }

}
