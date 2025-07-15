<?php

namespace App\Livewire\User;

use App\Models\Department;
use App\Models\Programme;
use App\Models\User;
use App\Notifications\SendDefaultPasswordNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ImportProgressModal extends Component
{
    use WireUiActions;

    public array $progress = [
        'total' => 0,
        'processed' => 0,
        'successful' => 0,
        'failed' => 0,
        'isProcessing' => false,
    ];

    public array $importedUsers = [];

    public array $failed = [];

    protected $listeners = [
        'showImportProgress' => 'showModal',
        'updateImportProgress' => 'updateProgress',
    ];

    public function mount(array $importedUsers = [])
    {
        $this->importedUsers = $importedUsers;
        $this->failed = collect($importedUsers)
            ->where('status', 'error')
            ->values()
            ->all();
    }

    public function showModal(array $importedUsers)
    {
        $this->importedUsers = $importedUsers;
        $this->failed = [];

        $this->progress = [
            'total' => count($importedUsers),
            'processed' => 0,
            'successful' => 0,
            'failed' => 0,
            'isProcessing' => true,
        ];


        $this->modal()->open('userImportprogress');
        $this->startImport();
    }

    public function resolveProgrammeId(?string $programmeNameOrCode): ?int
    {
        if (!$programmeNameOrCode) return null;
        $query = trim($programmeNameOrCode);
        return Programme::where('name', 'LIKE', $query)
            ->orWhere('code', 'LIKE', $query)
            ->value('id');
    }

    public function resolveDepartmentId(?string $departmentNameOrCode): ?int
    {
        if (!$departmentNameOrCode) return null;

        $query = trim($departmentNameOrCode);

        return Department::where('name', 'LIKE', $query)
            ->orWhere('code', 'LIKE', $query)
            ->value('id');
    }


    public function updateProgress(array $progress)
    {
        $this->progress = array_merge($this->progress, $progress);
    }

    public function retryFailed()
    {
        $this->emit('retryFailedImport');
    }

    public function downloadErrors()
    {
        if (empty($this->failed)) {
            return;
        }

        // Ensure exports directory exists
        if (!Storage::disk('local')->exists('exports')) {
            Storage::disk('local')->makeDirectory('exports');
        }

        $headers = [
            'row_number',
            'first_name',
            'last_name',
            'email',
            'role',
            'level',
            'programme',
            'department',
            'errors',
        ];

        $csv = implode(',', $headers) . "\n";

        foreach ($this->failed as $row) {
            $values = [];

            foreach ($headers as $key) {
                $value = $row[$key] ?? '';

                if ($key === 'errors' && is_array($value)) {
                    $value = implode('; ', $value);
                }

                $escaped = '"' . str_replace('"', '""', $value) . '"';
                $values[] = $escaped;
            }

            $csv .= implode(',', $values) . "\n";
        }

        $filename = 'import_failed_' . now()->format('Ymd_His') . '.csv';

        Storage::disk('local')->put("exports/{$filename}", $csv);

        $path = storage_path("app/private/exports/{$filename}");

        if (!file_exists($path)) {
            $this->notification()->error('Error', 'Failed to create error report file.');
            return;
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }


    public function startImport()
    {
        $updatedUsers = [];
        $success = 0;
        $failed = [];

        foreach ($this->importedUsers as $index => $data) {
            // Validate fields
            $validator = Validator::make($data, [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'email'      => [
                    'required',
                    'email',
                    'regex:/^[^@\s]+@(my\.mzuni\.ac\.mw|mzuni\.ac\.mw)$/'
                ],
                'role'       => 'required|in:student,lecturer',
                'level'      => 'required_if:role,student|nullable|integer',
                'programme'  => 'required_if:role,student|nullable|string',
                'department' => 'required_if:role,lecturer|nullable|string',
            ], [
                'email.regex' => 'Provide instutition email.',
            ]);

            $errors = $validator->errors()->all();

            // Check for existing user
            $exists = \App\Models\User::where('email', $data['email'])->exists();

            if ($exists) {
                $errors[] = "User with email already exists.";
            }

            // Resolve programme or department IDs
            $programmeId = null;
            $departmentId = null;

            if ($data['role'] === 'student') {
                $programmeId = $this->resolveProgrammeId($data['programme']);
                if (!$programmeId) {
                    $errors[] = "Programme '{$data['programme']}' not found.";
                }
            } elseif ($data['role'] === 'lecturer') {
                $departmentId = $this->resolveDepartmentId($data['department']);
                if (!$departmentId) {
                    $errors[] = "Department '{$data['department']}' not found.";
                }
            }

            // If errors, mark as failed and skip user creation
            if (!empty($errors)) {
                $data['status'] = 'error';
                $data['errors'] = $errors;
                $failed[] = $data;
                $updatedUsers[] = $data;
                $this->progress['processed'] = $index + 1;
                $this->progress['failed'] = count($failed);
                $this->dispatch('updateImportProgress', $this->progress);
                continue;
            }





            // Create user
            $password = str()->random(10);
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($password),
            ]);

            $roleMap = [
                'student' => 'Student',
                'lecturer' => 'Lecturer',
            ];

            $user->assignRole($roleMap[strtolower($data['role'])]);

            if ($data['role'] === 'student') {
                $user->student()->create([
                    'programme_id' => $programmeId,
                    'level' => $data['level'],
                ]);
            } elseif ($data['role'] === 'lecturer') {
                $user->lecturer()->create([
                    'department_id' => $departmentId,
                ]);
            }

            $data['status'] = 'success';
            $success++;

            // Send password via email
            $user->notify(new SendDefaultPasswordNotification($password));
            $updatedUsers[] = $data;

            // Update progress
            $this->progress['processed'] = $index + 1;
            $this->progress['successful'] = $success;
            $this->progress['failed'] = count($failed);
            $this->dispatch('updateImportProgress', $this->progress);

            usleep(30000);
        }

        $this->importedUsers = $updatedUsers;
        $this->failed = $failed;
        $this->progress['isProcessing'] = false;
    }


    public function closeModal()
    {
        $this->notification()->success(
            'Import Complete',
            "$this->success users imported, " . count($this->failed) . " failed."
        );
    }


    public function render()
    {
        return view('livewire.user.import-progress-modal');
    }
}
