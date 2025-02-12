<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Artist;
use App\Models\Event;

class GalleryComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $selectedArtists = [];
    public $selectedEvent = null;
    public $sortBy = 'newest';
    public $filtersVisible = true;

    protected $listeners = [
        'refreshGallery' => '$refresh',
    ];

    public function updating($property)
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        // Reset all filters
        $this->reset(['search', 'selectedCategories', 'selectedArtists', 'selectedEvent', 'sortBy']);

        // Force Livewire to update the UI properly
        $this->dispatch('$refresh');
    }

    public function toggleFilters()
    {
        $this->filtersVisible = !$this->filtersVisible;
    }

    public function render()
    {
        $query = Artwork::query();

        // Ensure selectedCategories and selectedArtists are arrays
        $this->selectedCategories = is_array($this->selectedCategories) ? $this->selectedCategories : [];
        $this->selectedArtists = is_array($this->selectedArtists) ? $this->selectedArtists : [];

        // 🔹 Search Filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('artist', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            });
        }

        // 🔹 Category Filter
        $filteredCategories = array_filter($this->selectedCategories, fn($id) => is_numeric($id));
        if (!empty($filteredCategories)) {
            $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $filteredCategories));
        }

        // 🔹 Artist Filter
        $filteredArtists = array_filter($this->selectedArtists, fn($id) => is_numeric($id));
        if (!empty($filteredArtists)) {
            $query->whereIn('artist_id', $filteredArtists);
        }

        // 🔹 Event Filter
        if (!empty($this->selectedEvent) && is_numeric($this->selectedEvent)) {
            $query->where('event_id', $this->selectedEvent);
        }

        // 🔹 Sorting Logic
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'featured':
                $query->orderBy('featured', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return view('livewire.gallery-component', [
            'artworks' => $query->paginate(12),
            'categories' => Category::all(),
            'artists' => Artist::all(),
            'events' => Event::all(),
        ]);
    }
}
