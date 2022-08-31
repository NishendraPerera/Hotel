<?php

namespace App\Repositories;

use Auth;
use \App\Model\Logging;

trait LogFunctions
{
    public function logging($reservation_id, $log_info)
    {
        $log = new Logging;
        $log->reservation_id = $reservation_id;
        $log->user_id = Auth::user()->id;
        $log->log = $log_info;
        $log->save();
    }
}