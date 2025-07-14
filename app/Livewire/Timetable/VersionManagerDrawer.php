<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleVersion;
use App\Models\User;
use App\Notifications\TimetablePublished;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WireUi\Traits\WireUiActions;

class VersionManagerDrawer extends Component
{
    use WireUiActions;

    public $isOpen = true;

    public $versions;

    public $currentVersion;

    public $editingVersionId = null;

    public $editingLabel = '';

    public $currentVersionId;

    protected $listeners = [
        'openVersionSlider' => 'open',
        'closeVersionSlider' => 'close',
    ];

    public function mount($currentVersionId)
    {
        $this->loadVersions();
        $this->currentVersionId = $currentVersionId;

        $version = ScheduleVersion::find($currentVersionId);
        if (!$version) {
            $this->currentVersion = null;
            $this->currentVersionId = null;
            return;
        }

        $this->currentVersion = $version;
    }

    public function open()
    {
        $this->isOpen = true;
        $this->dispatch('open-version-slider');
    }

    public function close()
    {
        $this->isOpen = false;
    }


    public function selectVersion($id)
    {
        $this->currentVersionId = $id;
        $this->dispatch('select-version', $id);
    }



    public function loadVersions()
    {
        $this->versions = ScheduleVersion::latest('generated_at')->get();
    }


    public function startEditingLabel($versionId)
    {
        $version = $this->versions->firstWhere('id', $versionId);
        if (!$version) return;

        $this->editingVersionId = $versionId;
        $this->editingLabel = $version->label;
    }

    public function saveLabel()
    {
        $this->validate([
            'editingLabel' => 'required|string|max:255',
        ]);

        if ($this->editingVersionId) {
            $version = ScheduleVersion::find($this->editingVersionId);
            if ($version) {
                $version->label = $this->editingLabel;
                $version->save();

                $this->notification()->success(
                    title: 'Version',
                    description: 'Label updated.'
                );

                if ($this->currentVersion?->id === $this->editingVersionId) {
                    $this->dispatch('version-selected', $this->editingVersionId);
                }

                $this->editingVersionId = null;
                $this->editingLabel = '';
                $this->loadVersions();
            }
        }
    }

    public function cancelEditing()
    {
        $this->editingVersionId = null;
        $this->editingLabel = '';
    }

    public function createVersion()
    {
        $latestVersion = ScheduleVersion::where('label', 'like', 'New Version%')
            ->orderByRaw("CAST(SUBSTRING(label, 13) AS UNSIGNED) DESC")
            ->first();

        $nextNumber = 1;
        if ($latestVersion && preg_match('/New Version (\d+)/', $latestVersion->label, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        }

        ScheduleVersion::create([
            'label' => "New Version {$nextNumber}",
            'is_published' => false,
            'published_at' => null,
            'generated_at' => now()
        ]);

        $this->dispatch('refresh-list');
        $this->loadVersions();
    }

    public function duplicateVersion($versionId)
    {
        DB::beginTransaction();

        try {
            $original = ScheduleVersion::with('entries')->findOrFail($versionId);

            $newVersion = ScheduleVersion::create([
                'label' => 'Copy of ' . ($original->label ?? 'Version') . ' - ' . now()->format('Y-m-d H:i'),
                'is_published' => false,
                'generated_at' => now(),
                'published_at' => null,
            ]);

            foreach ($original->entries as $entry) {
                $newVersion->entries()->create($entry->only([
                    'course_id',
                    'lecturer_id',
                    'venue_id',
                    'programme_id',
                    'day',
                    'start_time',
                    'end_time',
                    'level',
                ]));
            }

            DB::commit();

            $this->notification()->success(
                title: 'Version Duplicated',
                description: 'A new version has been created from the selected one.'
            );

            $this->loadVersions();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->notification()->error(
                title: 'Duplication Failed',
                description: 'An error occurred while duplicating the version.'
            );

            logger()->error('Failed to duplicate version: ' . $e->getMessage());
        }
    }

    public function publishVersion($id)
    {
        DB::transaction(function () use ($id) {
            ScheduleVersion::where('is_published', true)->update(['is_published' => false, 'published_at' => null]);

            $version = ScheduleVersion::find($id);
            $version->update([
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->currentVersion = $version;
            $this->dispatch('update-current');

            foreach (User::all() as $user) {
                $user->notify(new TimetablePublished($version->label));
            }
        });

        $this->notification()->success(
            'Version Published',
            'The selected version has been published successfully.',
            'check'
        );

        $this->loadVersions();
    }

    public function unPublishVersion($id)
    {
        ScheduleVersion::where('id', $id)->update(['is_published' => false, 'published_at' => null]);

        if ($this->currentVersion?->id === $id) {
            $this->dispatch('update-current');
        }

        $this->notification()->success(
            'Unpublished',
            'Version has been unpublished.',
            'info'
        );

        $this->loadVersions();
    }

    public function deleteVersion($id)
    {
        $version = ScheduleVersion::find($id);
        if (!$version) return;

        if ($version->is_published) {
            $this->dialog()->confirm([
                'title'       => 'Delete Schedule Version',
                'description' => 'Are you sure you want to delete this published version?',
                'icon'        => 'warning',
                'accept'      => [
                    'label'  => 'Yes, Delete',
                    'method' => 'deleteScheduleVersion',
                    'params' => $id,
                ],
                'reject' => [
                    'label'  => 'Cancel',
                ],
            ]);
        } else {
            $this->deleteScheduleVersion($id);
        }
    }

    // public function deleteScheduleVersion($id)
    // {
    //     $version_to_delete = ScheduleVersion::find($id);
    //     if (!$version_to_delete) return;

    //     $version_to_delete->delete();

    //     if ($this->currentVersion?->id == $id) {


    //         $this->currentVersion = null;
    //         $this->currentVersionId = null;
    //         $this->dispatch('selected-version-deleted');
    //     }
    //     $this->notification()->success(
    //         'Schedule version',
    //         'The schedule version deleted successfully.',
    //         'check'
    //     );
    // }
    public function deleteScheduleVersion($id)
    {
        if ($this->currentVersion?->id == $id) {
            $this->currentVersion = null;
            $this->currentVersionId = null;

            // Optional: clear editing state too
            $this->editingVersionId = null;
            $this->editingLabel = '';
        }

        ScheduleVersion::find($id)?->delete();

        $this->notification()->success(
            'Schedule version',
            'The schedule version deleted successfully.',
            'check'
        );

        $this->dispatch('selected-version-deleted');
        $this->loadVersions();
    }


    public function render()
    {
        return view('livewire.timetable.version-manager-drawer');
    }
}
