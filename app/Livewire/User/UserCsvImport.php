<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) !== count($header)) {
                continue; 
            }

            $this->previewData[] = array_combine($header, $row);
        }
        fclose($file);

        $this->isReadyToImport = true;
    }

    public function import()
    {
        $this->imported = 0;
        $this->failed = [];

        foreach ($this->previewData as $data) {
            $validator = Validator::make($data, [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'email'      => 'required|email|unique:users,email',
                'role'      => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $this->failed[] = [
                    'data' => $data,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email']
            ]);

            $user->assignRole($data['role']);

            $this->imported++;
        }

        // Reset preview after import
        $this->isReadyToImport = false;
        $this->previewData = [];

        $this->notification()->success(
            'Import',
            'Users imported successfully.'
        );
    }


    public function render()
    {
        return view('livewire.user.user-csv-import');
    }
}
