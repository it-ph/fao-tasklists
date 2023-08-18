<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;

class UploadDashboardActivityTemplateExport implements FromView, WithEvents
{
    use RegistersEventListeners;

    public function view(): View
    {
        return view('pages.admin.dashboard-activities.exports.upload_dashboard_activity_template');
    }
}
