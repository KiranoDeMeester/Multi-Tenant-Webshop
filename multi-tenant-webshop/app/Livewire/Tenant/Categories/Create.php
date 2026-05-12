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
    public string $meta_title = '';
    public string $meta_description = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:1000',
    ];

    public function save()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        session()->flash('message', __('Categorie succesvol aangemaakt!'));

        return redirect()->route('tenant.categories.index', ['tenant' => request()->route('tenant')]);
    }

    public function render()
    {
        return view('livewire.tenant.categories.create');
    }
}
