<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UploadTasksTemplateExport;
use App\Exports\UploadClientActivityTemplateExport;

class ExportController extends Controller
{
    public function uploadTasksTemplate()
    {
        return Excel::download(new UploadTasksTemplateExport, 'FAO-tasklists-upload-template.xlsx');
    }

    public function uploadClientActivityTemplate()
    {
        return Excel::download(new UploadClientActivityTemplateExport, 'client-activity-upload-template.xlsx');
    }
}
