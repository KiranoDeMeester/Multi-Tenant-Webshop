<?php

namespace App\Livewire\Tenant\Categories;

use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.tenant')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteCategory(string $id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->exists()) {
            session()->flash('error', __('Deze categorie kan niet worden verwijderd omdat er nog producten aan gekoppeld zijn.'));
            return;
        }

        $category->delete();
        session()->flash('message', __('Categorie succesvol verwijderd.'));
    }

    public function render()
    {
        return view('livewire.tenant.categories.index', [
            'categories' => Category::where('name', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
