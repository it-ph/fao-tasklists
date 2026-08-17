<table>
    <thead>
    <tr>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">EMPLOYEE NAME</th>
        <th style="width: 120px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">SCHEDULE</th>
        <th style="width: 350px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">ACTIVITY NAME</th>
        <th style="width: 150px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">APPLICABLE MONTH</th>
        <th style="width: 170px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">CLUSTER</th>
        <th style="width: 170px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">CLIENT</th>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">CLIENT FUNCTION</th>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">ECLERX FUNCTION</th>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">START DATE</th>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">END DATE</th>
        <th style="width: 120px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">DATE COMPLETED</th>
        <th style="width: 180px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">ACTUAL HANDLING TIME</th>
        <th style="width: 100px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">TIMELINESS</th>
        <th style="width: 100px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">QUALITY</th>
        <th style="width: 350px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">REMARKS</th>
        <th style="width: 120px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">STATUS</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td style="vertical-align: top; border: 1px solid #000000;">
                    @isset($task->theagent) {{ $task->theagent->fullname }} @endisset
                </td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">{{ date('m/d/Y', strtotime($task->schedule)) }}</td>
                <td style="vertical-align: top; word-wrap: break-word; white-space: nowrap; border: 1px solid #000000;">{{ $task->activity_name }}</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">{{ date('F Y', strtotime($task->applicable_month)) }}</td>
                <td style="vertical-align: top; border: 1px solid #000000;">{{ $task->thecluster->name }}</td>
                <td style="vertical-align: top; border: 1px solid #000000;">{{ $task->theclient->name }}</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->client_function){{ $task->client_function }} @else - @endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">{{ $task->eclerx_function }}</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->start_date){{ date('m/d/Y h:i:s a', strtotime($task->start_date)) }}@endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->end_date){{ date('m/d/Y h:i:s a', strtotime($task->end_date)) }} @else - @endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">
                    @if($task->status == "On Hold")
                        -
                    @else
                        @isset($task->end_date){{ date('m/d/Y', strtotime($task->end_date)) }} @else - @endisset
                    @endif
                </td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@if($task->status == "In Progress") - @else @isset($task->actual_handling_time) {{ $task->actual_handling_time }} @else - @endisset @endif</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->timeliness){{ $task->timeliness }} @else - @endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->quality){{ $task->quality }} @else - @endisset</td>
                <td style="vertical-align: top; word-wrap: break-word; white-space: nowrap; border: 1px solid #000000;">@isset($task->remarks){{ $task->remarks }} @endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">
                    @if($task->status == "Not Started")
                        Not Started
                    @elseif($task->status == "In Progress")
                        In Progress
                    @elseif($task->status == "On Hold")
                        On Hold
                    @elseif($task->status == "Completed")
                        Completed
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
