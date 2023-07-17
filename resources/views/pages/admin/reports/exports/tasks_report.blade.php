<table>
    <thead>
    <tr>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">EMPLOYEE NAME</th>
        <th style="width: 120px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">SHIFT DATE</th>
        <th style="width: 170px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">CLUSTER</th>
        <th style="width: 170px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">CLIENT</th>
        <th style="width: 170px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">DASHBOARD ACTIVITY</th>
        <th style="width: 350px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">CLIENT ACTIVITY</th>
        <th style="width: 350px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">DESCRIPTION</th>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">START DATE</th>
        <th style="width: 200px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">END DATE</th>
        <th style="width: 180px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">ACTUAL HANDLING TIME</th>
        <th style="width: 100px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">VOLUME</th>
        <th style="width: 350px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">REMARKS</th>
        <th style="width: 120px; text-align: center; font-weight: bold; background-color: #00599D; border: 1px solid #000000; color: white">STATUS</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td style="vertical-align: top; border: 1px solid #000000;">
                    @isset($task->theagent->employeeprofile) {{ $task->theagent->employeeprofile->fullname }} {{ $task->theagent->employeeprofile->last_name }} @endisset
                </td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">{{ date('m/d/Y', strtotime($task->shift_date)) }}</td>
                <td style="vertical-align: top; border: 1px solid #000000;">{{ $task->thecluster->name }}</td>
                <td style="vertical-align: top; border: 1px solid #000000;">{{ $task->theclient->name }}</td>
                <td style="vertical-align: top; word-wrap: break-word; white-space: nowrap; border: 1px solid #000000;">{{ $task->thedashboardactivity->name }}</td>
                <td style="vertical-align: top; word-wrap: break-word; white-space: nowrap; border: 1px solid #000000;">{{ $task->theclientactivity->name }}</td>
                <td style="vertical-align: top; word-wrap: break-word; white-space: nowrap; border: 1px solid #000000;">{{ $task->description }}</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->start_date){{ date('m/d/Y h:i:s a', strtotime($task->start_date)) }}@endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">@isset($task->end_date){{ date('m/d/Y h:i:s a', strtotime($task->end_date)) }}@endisset</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">{{ $task->actual_handling_time }}</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">{{ $task->volume }}</td>
                <td style="vertical-align: top; word-wrap: break-word; white-space: nowrap; border: 1px solid #000000;">{{ $task->remarks }}</td>
                <td style="vertical-align: top; text-align:center; border: 1px solid #000000;">
                    @if($task->status == "In Progress")
                        In Progress
                    @elseif($task->status == "Completed")
                        Completed
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
