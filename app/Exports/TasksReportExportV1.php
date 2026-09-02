<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class TasksReportExport implements FromView, WithEvents, WithTitle
{
    use RegistersEventListeners;

    private $tasks, $task_type;

    public function __construct($tasks, $task_type)
    {
        $this->tasks = $tasks;
        $this->task_type = $task_type;
    }

    public function view(): View
    {
        return view("pages.admin.reports.exports.{$this->task_type}_report",[
            'tasks' => $this->tasks,
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $sheetname = strtoupper($this->task_type).'_REPORT';
    }
}
