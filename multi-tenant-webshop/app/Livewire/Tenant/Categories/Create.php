<?php

namespace App\Livewire\Tenant\Categories;

use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class Create extends Component
{
    public string $name = '';
    public string $description = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'description' => $this->description,
        ]);

        session()->flash('message', __('Categorie succesvol aangemaakt!'));

        return redirect()->route('tenant.categories.index', ['tenant' => request()->route('tenant')]);
    }

    public function render()
    {
        return view('livewire.tenant.categories.create');
    }
}
