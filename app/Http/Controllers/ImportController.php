<?php

namespace App\Http\Controllers;

use App\Imports\TaskAssignmentsImport;
use Illuminate\Http\Request;
use App\Imports\ClientActivityImport;
use App\Imports\DashboardActivityImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class ImportController extends Controller
{
    public function importTaskAssignments(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx',
        ], [
            'import_file.required' => 'File to upload is required.',
            'import_file.mimes' => 'File to upload must be a valid excel file.',
        ]);

        $path = $request->file('import_file')->getRealPath();
        $import = new TaskAssignmentsImport;

        // 1. Open Database Transaction State Lock
        DB::beginTransaction();

        try {
            // --- ADDED: SAFE COOLDOWN GUARD FOR RANDOM EXCEL FILES ---
            $headingImport = (new \Maatwebsite\Excel\HeadingRowImport)->toArray($path);
            $uploadedHeaders = $headingImport[0][0] ?? [];
            $requiredHeaders = ['email_address', 'activity_name', 'applicable_month', 'client_function', 'eclerx_function', 'schedule'];

            if ($uploadedHeaders !== $requiredHeaders) {
                DB::rollBack();
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Invalid template format. Please ensure all required header columns match the official template layout.',
                    'error' => ['The uploaded file layout does not match the official template structure.']
                ]);
            }
            // --- END OF SAFE COOLDOWN GUARD ---

            Excel::import($import, $path);
            
            $errors = $import->getErrors();
            $errorsArray = array_unique($errors);

            // 2. Evaluate if any line failed validation rules or role guards
            if (count($errorsArray) > 0) {
                // Cancel all row insertions executed up to this point
                DB::rollBack();
                
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Import failed due to validation errors.',
                    'error' => array_values($errorsArray) // clears non-sequential index offsets caused by array_unique
                ]);
            }

            // 3. Confirm all modifications permanently into storage 
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Task Assignments uploaded successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Handles non-excel formats (CSV, PDF, TXT) that crash the excel package reader
            return response()->json([
                'status' => 'warning',
                'message' => 'Invalid file type or format. Please upload a valid, uncorrupted Excel (.xlsx) file.',
                'error' => ['The system cannot read this file extension or internal content format.']
            ]);
        }
    }

    public function importClientActivity(Request $request)
    {
        $request->validate([
            'import_file'   => 'required|file|mimes:xlsx',
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
            'import_file'   => 'required|file|mimes:xlsx',
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
