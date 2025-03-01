# AdminArtworksController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminArtworksController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the artworks.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### create

Display the form for creating a new artwork.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### store

Store a newly created artwork in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### edit

Display the form for editing the specified artwork.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Artwork $artwork
- `App\Models\Artwork $artwork`: No description available

**Returns:** `mixed`

---

### update

Update the specified artwork in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param  \App\Models\Artwork  $artwork
- `App\Models\Artwork $artwork`: No description available

**Returns:** `mixed`

---

### destroy

Remove the specified artwork from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param  \App\Models\Artwork  $artwork
- `App\Models\Artwork $artwork`: No description available

**Returns:** `mixed`

---

### trashed

Display a listing of the trashed artworks.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### restore

Restore a soft-deleted artwork.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the artwork to restore.

**Returns:** `mixed`

---

### forceDelete

Permanently delete the specified artwork from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param int $id
- `mixed $id`: No description available

**Returns:** `mixed`

---

### generateUniqueSlug

Generate a unique slug for an artwork based on its name.
This method takes a name, converts it to a slug, and ensures the slug is unique
by appending a counter if necessary. It checks the database for existing slugs
and increments the counter until a unique slug is found.

**Visibility:** private

**Parameters:**

- `mixed $name`: The name of the artwork to generate a slug for.

**Returns:** `mixed`

---

