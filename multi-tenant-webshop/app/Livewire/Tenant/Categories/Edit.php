<?php

namespace App\Livewire\Tenant\Categories;

use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class Edit extends Component
{
    public Category $category;
    public string $name = '';
    public string $description = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
    }

    public function save()
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', __('Categorie succesvol bijgewerkt!'));

        return redirect()->route('tenant.categories.index', ['tenant' => request()->route('tenant')]);
    }

    public function render()
    {
        return view('livewire.tenant.categories.edit');
    }
}
