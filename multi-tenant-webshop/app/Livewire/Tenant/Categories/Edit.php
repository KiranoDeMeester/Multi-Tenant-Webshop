<?php

namespace App\Livewire\Tenant\Categories;

use App\Models\Tenant\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.tenant')]
class Edit extends Component
{
    use WithFileUploads;

    public Category $category;

    public string $name = '';

    public string $description = '';

    public string $meta_title = '';

    public string $meta_description = '';

    public $image;

    public string $image_url = '';

    public bool $has_uploaded_image = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->meta_title = $category->meta_title ?? '';
        $this->meta_description = $category->meta_description ?? '';
        $this->has_uploaded_image = $category->hasMedia('categories');
        $this->image_url = $category->getFirstMediaUrl('categories') ?: '';
    }

    public function save()
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        if ($this->image) {
            $this->category->clearMediaCollection('categories');
            $this->category->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('categories');

            $this->image_url = $this->category->getFirstMediaUrl('categories');
            $this->has_uploaded_image = true;
            $this->image = null;
        }

        session()->flash('message', __('Categorie succesvol bijgewerkt!'));

        return redirect()->route('tenant.categories.index', ['tenant' => request()->route('tenant')]);
    }

    public function deleteImage()
    {
        $this->category->clearMediaCollection('categories');
        $this->has_uploaded_image = false;
        $this->image_url = '';
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.tenant.categories.edit');
    }
}
