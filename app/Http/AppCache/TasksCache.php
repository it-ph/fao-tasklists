<?php

namespace App\Http\AppCache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Models\Task;

class TasksCache
{
    // get all tasks based on status/authenticated agent
    public static function getAgentTasks($status)
    {
        $status = strtolower($status);
        $agent_id = Auth::user()->emp_id;
        $cachedTasks = Redis::get(preg_replace('/\s+/', '_',$status).'_tasks_of_agent_'.$agent_id);
        if(isset($cachedTasks))
        {
            $tasks = json_decode($cachedTasks, FALSE);
        }
        else
        {
            $tasks = Task::query()
                ->with([
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theagent:emp_id,email,emp_code,fullname,last_name',
                    'theclientactivity:id,name'
                ])
                ->where('agent_id', $agent_id);

            // filter by status
            if(in_array($status,(['all'])))
            {
                $tasks = $tasks->get();
            }
            else
            {
                $tasks = $tasks->where('status',$status)->get();
            }

            Redis::set(preg_replace('/\s+/', '_',$status).'_tasks_of_agent_'.$agent_id, $tasks);
        }

        return $tasks;
    }
}
