<?php

namespace App\Livewire;

use App\Models\ReportCategory;
use Livewire\Component;

class CategoryManager extends Component
{
    public $categories;

    public $name;

    public $code;

    public $categoryId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255|unique:report_categories,code',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = ReportCategory::orderBy('name', 'asc')->get();
    }

    public function saveCategory()
    {
        $rules = $this->rules;
        if ($this->categoryId) {
            $rules['code'] = 'required|string|max:255|unique:report_categories,code,'.$this->categoryId;
        }

        $this->validate($rules);

        if ($this->categoryId) {
            ReportCategory::find($this->categoryId)->update([
                'name' => $this->name,
                'code' => $this->code,
            ]);
            session()->flash('message', 'Kategori berhasil diperbarui.');
        } else {
            ReportCategory::create([
                'name' => $this->name,
                'code' => $this->code,
            ]);
            session()->flash('message', 'Kategori berhasil ditambahkan.');
        }

        $this->reset(['name', 'code', 'categoryId']);
        $this->loadCategories();
    }

    public function editCategory($id)
    {
        $category = ReportCategory::find($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->code = $category->code;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'code', 'categoryId']);
    }

    public function deleteCategory($id)
    {
        ReportCategory::find($id)->delete();
        $this->loadCategories();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.category-manager')->layout('layouts.app');
    }
}
