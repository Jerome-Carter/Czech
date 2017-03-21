<?php

namespace Czech;

use Czech\Models\Czech as DB;

class Czech {

    public function lastCzech($student) {
        return DB::where('student_id', $student)->orderBy('created_at', 'desc')->first();
    }

    public function lastCzechWasToday($student) {
        return DB::where('student_id', $student)->whereDay('created_at', '=', date('d'))->whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->first();
    }

}