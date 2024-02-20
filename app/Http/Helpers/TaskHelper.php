<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class TaskHelper {
    public function getActualHandlingTime($task)
    {
        if($task->actual_handling_time)
        {
            $old_handling_time = explode(":", $task->temp_handling_time);
            $old_D = ltrim($old_handling_time[0],'0') ? : '0';
            $old_H = ltrim($old_handling_time[1],'0') ? : '0';
            $old_I = ltrim($old_handling_time[2],'0') ? : '0';
            $old_S = ltrim($old_handling_time[3],'0') ? : '0';

            // echo "OLD: " . $old_D." days ".$old_H." hours ". $old_I." minutes ".$old_S." seconds".'<br>';

            $new_dt = Carbon::now();
            $old_dt = $task->old_dt;

            $new_actual_handling_time = $new_dt->diff($old_dt)->format("%D:%H:%I:%S");
            $new_actual_handling_time = explode(":", $new_actual_handling_time);

            $new_D = ltrim($new_actual_handling_time[0],'0') ? : '0';
            $new_H = ltrim($new_actual_handling_time[1],'0') ? : '0';
            $new_I = ltrim($new_actual_handling_time[2],'0') ? : '0';
            $new_S = ltrim($new_actual_handling_time[3],'0') ? : '0';

            // echo "ADD: ". $new_D." days ".$new_H." hours ". $new_I." minutes ".$new_S." seconds".'<br>';

            $D = ($old_D * 86400) + ($new_D * 86400);
            $H = ($old_H * 3600) + ($new_H * 3600);
            $I = ($old_I * 60) + ($new_I * 60);
            $S = $old_S + $new_S;

            $time_elapsed_in_seconds = $D + $H + $I + $S;
            $temp_handling_time = sprintf('%02d:%02d:%02d:%02d', $time_elapsed_in_seconds/86400, $time_elapsed_in_seconds/3600%24, $time_elapsed_in_seconds/60%60, $time_elapsed_in_seconds%60);
        }
        else
        {
            $new_dt = $task->start_date;
            $old_dt = Carbon::now();

            $new_actual_handling_time = $new_dt->diff($old_dt)->format("%D:%H:%I:%S");
            $new_actual_handling_time = explode(":", $new_actual_handling_time);

            $new_D = ltrim($new_actual_handling_time[0],'0') ? : '0';
            $new_H = ltrim($new_actual_handling_time[1],'0') ? : '0';
            $new_I = ltrim($new_actual_handling_time[2],'0') ? : '0';
            $new_S = ltrim($new_actual_handling_time[3],'0') ? : '0';

            $D = $new_D * 86400;
            $H = $new_H * 3600;
            $I = $new_I * 60;
            $S = $new_S;

            $time_elapsed_in_seconds = $D + $H + $I + $S;

            $temp_handling_time = $new_dt->diff($old_dt)->format("%D:%H:%I:%S");
        }

        $actual_handling_time = $temp_handling_time;

        $values = array(
            'old_dt' => $old_dt,
            'temp_handling_time' => $temp_handling_time,
            'actual_handling_time' => $actual_handling_time,
            'time_elapsed_in_seconds' => $time_elapsed_in_seconds
        );

        return $values;
    }
}
