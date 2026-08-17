<?php

namespace App\Livewire\Tenant\Categories;

use App\Models\Tenant\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.tenant')]
class Create extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $description = '';

    public string $meta_title = '';

    public string $meta_description = '';

    public $image;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $category = Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.Str::random(5),
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        if ($this->image) {
            $category->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('categories');
        }

        session()->flash('message', __('Categorie succesvol aangemaakt!'));

        return redirect()->route('tenant.categories.index', ['tenant' => request()->route('tenant')]);
    }

    public function render()
    {
        return view('livewire.tenant.categories.create');
    }
}
