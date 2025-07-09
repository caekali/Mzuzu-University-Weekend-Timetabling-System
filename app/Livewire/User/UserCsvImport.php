<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class UserCsvImport extends Component
{
    use WithFileUploads, WireUiActions;

    public $csvFile;
    public $previewData = [];
    public $imported = 0;
    public $failed = [];
    public $isReadyToImport = false;

    public function updatedCsvFile()
    {
        $this->reset(['previewData', 'imported', 'failed', 'isReadyToImport']);

        if (!$this->csvFile) return;

        $file = fopen($this->csvFile->getRealPath(), 'r');
        $header = fgetcsv($file);

        $rowNumber = 2; // (1 is header)
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) !== count($header)) {
                $rowNumber++;
                continue;
            }

            $rowData = array_combine($header, $row);
            $rowData['row_number'] = $rowNumber++;
            $this->previewData[] = $rowData;
        }

        fclose($file);
        $this->isReadyToImport = true;
        $this->notification()->success(
            'CSV parse',
            "parsed " . count($this->previewData) . " users."
        );
    }

    public function processUsers()
    {
        $this->dispatch('showImportProgress', $this->previewData)
            ->to('user.import-progress-modal');
    }

    public function clearImports()
    {
        $this->previewData = [];
    }

    public function downloadTemplate()
    {
        $headers = ['first_name', 'last_name', 'email', 'role', 'level', 'programme', 'department'];

        // Sample rows
        $rows = [
            ['John', 'Doe', 'john.doe@university.edu', 'student', '2', 'Computer Science', ''],
            ['Jane', 'Smith', 'jane.smith@university.edu', 'lecturer', '', '', 'Computer Science'],
            ['Bob', 'Johnson', 'bob.j@university.edu', 'hod', '', '', 'Mathematics'],
        ];

        // building CSV string
        $csv = implode(',', $headers) . "\n";

        foreach ($rows as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        $filename = 'user_import_template.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }


    public function render()
    {
        return view('livewire.user.user-csv-import');
    }
}
