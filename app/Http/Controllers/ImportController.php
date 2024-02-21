<?php

namespace App\Http\Controllers;

use App\Imports\TasksImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientActivityImport;
use App\Imports\DashboardActivityImport;

class ImportController extends Controller
{
    public function importTasks(Request $request)
    {
        $request->validate([
            'import_file'   => 'required'
        ],$messages = array('import_file.required' => 'File to upload is required'));

        $path = $request->file('import_file')->getRealPath();

        $import = new TasksImport;
        Excel::import($import, $path);

        $errors = $import->getErrors();
        if(count(array_unique($errors)) > 1)
        {
            return redirect()->back()->withErrors(array_unique($errors));
        }
        else
        {
            return redirect()->back()->with('with_success', 'Tasks Uploaded Succesfully!');
        }
    }

    public function importClientActivity(Request $request)
    {
        $request->validate([
            'import_file'   => 'required'
        ],$messages = array('import_file.required' => 'File to upload is required'));

        $path = $request->file('import_file')->getRealPath();

        $import = new ClientActivityImport;
        Excel::import($import, $path);

        $errors = $import->getErrors();
        if(count(array_unique($errors)) > 1)
        {
            return redirect()->back()->withErrors(array_unique($errors));
        }
        else
        {
            return redirect()->back()->with('with_success', 'Activity Uploaded Succesfully!');
        }
    }

    public function importDashboardActivity(Request $request)
    {
        $request->validate([
            'import_file'   => 'required'
        ],$messages = array('import_file.required' => 'File to upload is required'));

        $path = $request->file('import_file')->getRealPath();

        $import = new DashboardActivityImport;
        Excel::import($import, $path);

        $errors = $import->getErrors();
        if(count(array_unique($errors)) > 1)
        {
            return redirect()->back()->withErrors(array_unique($errors));
        }
        else
        {
            return redirect()->back()->with('with_success', 'Dashboard Activity Uploaded Succesfully!');
        }
    }
}
