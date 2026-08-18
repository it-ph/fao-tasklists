<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation; 

class UploadTaskAssignmentExport implements FromView, WithEvents
{
    use RegistersEventListeners;

    public function view(): View
    {
        return view('pages.admin.task-assignments.exports.upload_tasks_template');
    }

    public function registerEvents(): array
    {
        return [
    AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();

        // 1. Explicitly clear any glitchy dropdown configurations on your note rows (Rows 2-5)
        for ($cleanRow = 2; $cleanRow <= 5; $cleanRow++) {
            $sheet->getCell("E{$cleanRow}")->setDataValidation(new DataValidation());
        }

        // 2. Define strict text dropdown choices (enclosed in literal quotes)
        $options = '"Procure to Pay (P2P),Order to Cash (O2C),Record to Report (R2R),Personiv Admin,Client Admin"';

        // 3. Configure validation rules starting strictly on Row 6
        $validation = $sheet->getCell('E6')->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true); // Required for active search filtering
        
        // On-Click Tooltip Suggestions
        // $validation->setShowInputMessage(true); // Triggers the suggestion popup on hover/click
        // $validation->setPromptTitle('Approved Options:');
        // $validation->setPrompt("• Procure to Pay (P2P)\n• Order to Cash (O2C)\n• Record to Report (R2R)\n• Personiv Admin\n• Client Admin");

        // Error Alert Box configuration
        $validation->setErrorTitle('Invalid Selection');
        $validation->setError('Please select a valid option from the dropdown menu.');
        $validation->setFormula1($options);

        // 4. Force clone this rule structure down through row 504 explicitly
        for ($i = 6; $i <= 504; $i++) {
            $sheet->getCell("E{$i}")->setDataValidation(clone $validation);
        }
    },
];

    }
}
