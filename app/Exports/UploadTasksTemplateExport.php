<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;

class UploadTasksTemplateExport implements FromView, WithEvents
{
    use RegistersEventListeners;

    public function view(): View
    {
        return view('pages.admin.tasks.exports.upload_tasks_template');
    }
}
