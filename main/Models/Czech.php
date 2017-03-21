<?php

namespace Czech\Models;

class Czech extends \Illuminate\Database\Eloquent\Model {

        protected $table = 'dates';

        protected $fillable = [
            'student_id',
            'status',
            'loc',
        ];

}