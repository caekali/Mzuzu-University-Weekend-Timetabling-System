<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleVersion;
use App\Models\User;
use App\Notifications\TimetablePublished;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class VersionManagerDrawer extends Component
{
    use WireUiActions;

    public $isOpen = true;

    public $versions;

    public $currentVersion;

    public $editingVersionId = null;

    public $editingLabel = '';

    protected $listeners = [
        'openVersionSlider' => 'open',
        'closeVersionSlider' => 'close',
    ];

    public function mount()
    {
        $this->versions = ScheduleVersion::all();
        $this->currentVersion = ScheduleVersion::published()->first() ? ScheduleVersion::published()->first() :  $this->versions->first();
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


                if ($this->currentVersion->id == $this->editingVersionId)
                    $this->dispatch('version-selected', $this->editingVersionId);

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

    public function selectVersion($versionId)
    {
        $this->currentVersion = ScheduleVersion::find($versionId);
        $this->dispatch('version-selected', $versionId);
    }

    public function createVersion()
    {
        // Find existing "New Version X" number
        $latestVersion = ScheduleVersion::where('label', 'like', 'New Version%')
            ->orderByRaw("CAST(SUBSTRING(label, 13) AS UNSIGNED) DESC")
            ->first();

        $nextNumber = 1;
        if ($latestVersion) {
            // Extract the number
            preg_match('/New Version (\d+)/', $latestVersion->label, $matches);
            if (isset($matches[1])) {
                $nextNumber = (int)$matches[1] + 1;
            }
        }

        ScheduleVersion::create([
            'label' => "New Version {$nextNumber}",
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->loadVersions();
    }



    public function duplicateVersion($versionId)
    {
        DB::beginTransaction();

        try {
            $original = ScheduleVersion::with('entries')->findOrFail($versionId);

            // Create close
            $newVersion = ScheduleVersion::create([
                'label' => 'Copy of ' . ($original->label ?? 'Version') . ' - ' . now()->format('Y-m-d H:i'),
                'is_published' => false,
                'published_at' => null,
            ]);

            // Clone entries
            foreach ($original->entries as $entry) {
                $newVersion->entries()->create([
                    'course_id' => $entry->course_id,
                    'lecturer_id' => $entry->lecturer_id,
                    'venue_id' => $entry->venue_id,
                    'programme_id' => $entry->programme_id,
                    'day' => $entry->day,
                    'start_time' => $entry->start_time,
                    'end_time' => $entry->end_time,
                    'level' => $entry->level,
                ]);
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


    public function unPublishVersion($id)
    {
        ScheduleVersion::where('id', $id)->update(['is_published' => false, 'published_at' => null]);
        $this->notification()->success(
            'Unpublished',
            'Version has been unpublished.',
            'info'
        );

        $this->loadVersions();
    }

    public function publishVersion($id)
    {
        DB::transaction(function () use ($id) {
            ScheduleVersion::where('is_published', true)->update(['is_published' => false, 'published_at' => null]);
            $version = ScheduleVersion::find($id);
            $version->is_published = true;
            $version->published_at = now();
            $version->save();

            $users = User::all();
            foreach ($users as $user) {
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

    public function deleteVersion($id)
    {
        if ($this->currentVersion->id != $id) {
            ScheduleVersion::findOrFail($id)->delete();
            $this->notification()->success(
                'Version Deleted',
                'The selected version has been deleted successfully.',
                'check'
            );
            $this->loadVersions();
        } else {
            $this->notification()->warning(
                'Version Delete',
                'Your are viewing this version.',
                'info'
            );
        }
    }

    public function loadVersions()
    {
        $this->versions = ScheduleVersion::all();
    }

    public function render()
    {
        return view('livewire.timetable.version-manager-drawer');
    }
}
