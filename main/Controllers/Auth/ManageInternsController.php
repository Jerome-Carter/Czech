<?php

namespace Czech\Controllers\Auth;

use Czech\Models\Student;
use Czech\Controllers\MainController;

class ManageInternsController extends MainController{

    public function manageInterns($request, $response) {
        $students = [];
        foreach (Student::get()->where('is_admin', 0) as $student) {
            $students[] = ['id' => $student->id, 'name' => $student->first_name . ' ' . $student->last_name, 'email' => $student->email];
        }
        return $this->view->render($response, 'auth/manageInterns.twig', ['students' => $students]);
    }

    public function deleteIntern($request, $response) {
        if ($student = Student::find($request->getAttribute('route')->getArgument('student'))) $student->delete();
        $this->flash->addMessage('pass', 'The user was deleted.');
        return $response->withRedirect($this->router->pathFor('manageInterns'));
    }

}