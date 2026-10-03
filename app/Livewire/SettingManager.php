<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;

class SettingManager extends Component
{
    public $settings;

    public $key;

    public $value;

    public $settingId = null;

    protected $rules = [
        'key' => 'required|string|max:255|unique:settings,key',
        'value' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->settings = Setting::orderBy('key', 'asc')->get();
    }

    public function saveSetting()
    {
        $rules = $this->rules;
        if ($this->settingId) {
            $rules['key'] = 'required|string|max:255|unique:settings,key,'.$this->settingId;
        }

        $this->validate($rules);

        if ($this->settingId) {
            Setting::find($this->settingId)->update([
                'key' => $this->key,
                'value' => $this->value,
            ]);
            session()->flash('message', 'Pengaturan berhasil diperbarui.');
        } else {
            Setting::create([
                'key' => $this->key,
                'value' => $this->value,
            ]);
            session()->flash('message', 'Pengaturan berhasil ditambahkan.');
        }

        $this->reset(['key', 'value', 'settingId']);
        $this->loadSettings();
    }

    public function editSetting($id)
    {
        $setting = Setting::find($id);
        $this->settingId = $setting->id;
        $this->key = $setting->key;
        $this->value = $setting->value;
    }

    public function cancelEdit()
    {
        $this->reset(['key', 'value', 'settingId']);
    }

    public function deleteSetting($id)
    {
        Setting::find($id)->delete();
        $this->loadSettings();
        session()->flash('message', 'Pengaturan berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.setting-manager')->layout('layouts.app', ['title' => 'Pengaturan']);
    }
}
