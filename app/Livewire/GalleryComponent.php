<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Artist;
use App\Models\Event;

/**
 * Class GalleryComponent
 *
 * This Livewire component handles the gallery functionality, including searching, filtering, and sorting artworks.
 *
 * Properties:
 * @property string $search The search term for filtering artworks.
 * @property array $selectedCategories The selected categories for filtering artworks.
 * @property array $selectedArtists The selected artists for filtering artworks.
 * @property int|null $selectedEvent The selected event for filtering artworks.
 * @property string $sortBy The sorting option for artworks (newest, oldest, featured).
 * @property bool $filtersVisible Indicates whether the filters are visible.
 *
 * Listeners:
 * @property array $listeners The event listeners for the component.
 *
 * Methods:
 * @method void updating($property) Resets the pagination when a property is updated.
 * @method void resetFilters() Resets all filters to their default values and refreshes the gallery.
 * @method void toggleFilters() Toggles the visibility of the filters.
 * @method \Illuminate\View\View render() Renders the gallery component with filtered and sorted artworks.
 */
class GalleryComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $selectedArtists = [];
    public $selectedEvent = null;
    public $sortBy = 'newest';
    public $filtersVisible = false;

    protected $listeners = [
        'refreshGallery' => '$refresh',
    ];

    public function updating($property)
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategories', 'selectedArtists', 'selectedEvent', 'sortBy']);
        $this->dispatch('$refresh');
    }

    public function toggleFilters()
    {
        $this->filtersVisible = !$this->filtersVisible;
    }

    public function render()
    {
        $query = Artwork::query();

        $this->selectedCategories = is_array($this->selectedCategories) ? $this->selectedCategories : [];
        $this->selectedArtists = is_array($this->selectedArtists) ? $this->selectedArtists : [];

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('artist', fn($q) => $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]));
            });
        }

        $filteredCategories = array_filter($this->selectedCategories, fn($id) => is_numeric($id));
        if (!empty($filteredCategories)) {
            $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $filteredCategories));
        }

        $filteredArtists = array_filter($this->selectedArtists, fn($id) => is_numeric($id));
        if (!empty($filteredArtists)) {
            $query->whereIn('artist_id', $filteredArtists);
        }

        if (!empty($this->selectedEvent) && is_numeric($this->selectedEvent)) {
            $query->where('event_id', $this->selectedEvent);
        }

        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc');
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
