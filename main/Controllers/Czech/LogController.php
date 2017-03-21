<?php

namespace Czech\Controllers\Czech;

use Czech\Models\Czech as DB;

class LogController extends \Czech\Controllers\MainController {
    public function log($request, $response) {
        $logs = DB::get();
        foreach ($logs as $log) {
            $student = $this->student->getData($log->student_id);
            if ($student) {
                $log['name'] = $student->first_name . ' ' . $student->last_name;
            }else {
                $log->delete();
                $log['status'] = null;
                $log = null;
            }
            $log['date'] = date_format($log->created_at, 'g:i a \o\n l\, F jS\, Y');
            $lat = $log->lat;
            $lng = $log->long;
            if($lat == '' || $lng == ''){
                $loc = 'Unknown';
            }else{
                $loc = "<a href='https://www.google.com/maps/preview/@$lat,$lng,19z' target=\"blank\">" . $log->lat . $log->long . '</a>';
                $log['location'] = $loc;
            }
        }
        return $this->view->render($response, 'logs.twig', ['logs' => $logs]);
    }
    public function spec($request, $response) {
        $student = $this->student->getData($request->getAttribute('route')->getArgument('user'));
        $logs = DB::where('student_id', $request->getAttribute('route')->getArgument('user'))->get();
        foreach ($logs as $log) {
            if (!$student) {
                $log->delete();
                $log['status'] = null;
                $log = null;
            }
            $log['date'] = date_format($log->created_at, 'g:i a \o\n l\, F jS\, Y');
            $lat = $log->lat;
            $lng = $log->long;
            if($lat == '' || $lng == ''){
                $loc = 'Unknown';
            }else{
                $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=true';
                $json = @file_get_contents($url);
                $data=json_decode($json);
                $status = $data->status;
                if($status=="OK")
                    $loc = "<a href='https://www.google.com/maps/preview/@$lat,$lng,19z' target=\"blank\">" . $data->results[0]->formatted_address . '</a>';
                else
                    $loc = 'Unknown';
                $log['location'] = $loc;
            }
        }
        return $this->view->render($response, 'user.twig', ['logs' => $logs, 'name' => $student->first_name . ' ' . $student->last_name]);
    }
}