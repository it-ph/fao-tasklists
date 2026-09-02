<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class TasksReportExport implements WithMultipleSheets
{
    private $tasks, $task_assignments, $task_type;

    public function __construct($tasks, $task_assignments, $task_type)
    {
        $this->tasks            = $tasks;
        $this->task_assignments = $task_assignments;
        $this->task_type        = $task_type;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->task_type === 'all') {
            // Sheet 1: Task Lists (from Task model)
            $sheets[] = new class($this->tasks) implements FromView, WithTitle
            {
                private $tasks;
                public function __construct($tasks)
                {$this->tasks = $tasks;}
                public function view(): View
                {
                    return view("pages.admin.reports.exports.tasks_report", ['tasks' => $this->tasks]);
                }
                public function title(): string
                {return 'TASK LISTS';}
            };

            // Sheet 2: Task Assignments (from TaskAssignment model)
            $sheets[] = new class($this->task_assignments) implements FromView, WithTitle
            {
                private $task_assignments;
                public function __construct($task_assignments)
                {$this->task_assignments = $task_assignments;}
                public function view(): View
                {
                    return view("pages.admin.reports.exports.task_assignments_report", ['tasks' => $this->task_assignments]);
                }
                public function title(): string
                {return 'TASK ASSIGNMENTS';}
            };
        } else {
            // Single Sheet Export (either 'tasks' or 'task_assignments')
            $activeData = ($this->task_type === 'tasks') ? $this->tasks : $this->task_assignments;

            $sheets[] = new class($activeData, $this->task_type) implements FromView, WithTitle, WithEvents
            {
                use RegistersEventListeners;
                private $data, $task_type;
                public function __construct($data, $task_type)
                {
                    $this->data      = $data;
                    $this->task_type = $task_type;
                }
                public function view(): View
                {
                    return view("pages.admin.reports.exports.{$this->task_type}_report", ['tasks' => $this->data]);
                }
                public function title(): string
                {
                    return strtoupper($this->task_type) . '_REPORT';
                }
            };
        }

        return $sheets;
    }
}
