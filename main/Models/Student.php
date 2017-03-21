<?php

namespace Czech\Models;

class Student extends \Illuminate\Database\Eloquent\Model {
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_admin'
    ];
}
