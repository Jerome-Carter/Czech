<?php

namespace Czech\Auth;

use Czech\Models\Student as DB;

class Password {

    public function change($student, $new_pass) {
        DB::where('id', $student)->update([
            'password' => password_hash($new_pass, PASSWORD_DEFAULT, ['cost' => 11])
        ]);
    }

}