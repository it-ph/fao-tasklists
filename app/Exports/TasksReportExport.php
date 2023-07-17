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

    private $tasks;

    public function __construct($tasks)
    {
        $this->tasks = $tasks;
    }

    public function view(): View
    {
        return view('pages.admin.reports.exports.tasks_report',[
            'tasks' => $this->tasks,
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $sheetname = 'TASKS_REPORT';
    }
}
