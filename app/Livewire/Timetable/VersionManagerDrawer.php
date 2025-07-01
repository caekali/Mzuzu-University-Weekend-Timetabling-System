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

    public $scheduleVersions = [];

    public bool $showVersionDrawer = false;

    public int|null $editingVersionId = null;

    public string $editableLabel = '';

    public function mount()
    {
        $this->refreshVersions();
    }

    public function refreshVersions()
    {
        $this->scheduleVersions = ScheduleVersion::all();
    }

    public function startEditing($id, $label)
    {
        $this->editingVersionId = $id;
        $this->editableLabel = $label;
    }

    public function viewVersion($versionId)
    {
        $this->dispatch('version-selected', id: $versionId);
    }


    public function saveLabel($id)
    {
        $version = ScheduleVersion::findOrFail($id);
        if ($version->label != $this->editableLabel) {
            $version->label = $this->editableLabel;
            $version->save();
            $this->notification()->success(
                'Label Saved',
                'Version label updated successfully.',
                'check'
            );

            $this->refreshVersions();
        }

        $this->editingVersionId = null;
        $this->editableLabel = '';
    }


    public function unpublishVersion($id)
    {
        ScheduleVersion::where('id', $id)->update(['is_published' => false]);
        $this->notification()->success(
            'Unpublished',
            'Version has been unpublished.',
            'info'
        );

        $this->refreshVersions();
    }

    public function publishVersion($id)
    {
        DB::transaction(function () use ($id) {
            ScheduleVersion::where('is_published', true)->update(['is_published' => false]);
            $version = ScheduleVersion::find($id);
            $version->is_published = true;
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

        $this->refreshVersions();
    }

    public function deleteVersion($id)
    {
        ScheduleVersion::findOrFail($id)->delete();
        $this->notification()->success(
            'Version Deleted',
            'The selected version has been deleted successfully.',
            'check'
        );
        $this->refreshVersions();
    }


    public function render()
    {
        return view('livewire.timetable.version-manager-drawer');
    }
}
