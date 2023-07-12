<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;

class UploadClientActivityTemplateExport implements FromView, WithEvents
{
    use RegistersEventListeners;

    public function view(): View
    {
        return view('pages.admin.client-activities.exports.upload_client_activity_template');
    }
}
