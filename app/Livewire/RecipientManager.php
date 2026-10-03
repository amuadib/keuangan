<?php

namespace App\Livewire;

use App\Models\Recipient;
use Livewire\Component;

class RecipientManager extends Component
{
    public $recipients;

    public $name;

    public $recipientId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->loadRecipients();
    }

    public function loadRecipients()
    {
        $this->recipients = Recipient::orderBy('name', 'asc')->get();
    }

    public function saveRecipient()
    {
        $this->validate();

        if ($this->recipientId) {
            Recipient::find($this->recipientId)->update([
                'name' => $this->name,
            ]);
            session()->flash('message', 'Penerima berhasil diperbarui.');
        } else {
            Recipient::create([
                'name' => $this->name,
            ]);
            session()->flash('message', 'Penerima berhasil ditambahkan.');
        }

        $this->reset(['name', 'recipientId']);
        $this->loadRecipients();
    }

    public function editRecipient($id)
    {
        $recipient = Recipient::find($id);
        $this->recipientId = $recipient->id;
        $this->name = $recipient->name;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'recipientId']);
    }

    public function deleteRecipient($id)
    {
        Recipient::find($id)->delete();
        $this->loadRecipients();
        session()->flash('message', 'Penerima berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.recipient-manager')->layout('layouts.app');
    }
}
