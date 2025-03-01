# AdminArtistsController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminArtistsController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the artists.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### create

Display the artist creation page.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### store

Store a newly created artist in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### edit

Display the form for editing the specified artist.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Artist $artist
- `App\Models\Artist $artist`: No description available

**Returns:** `mixed`

---

### update

Update the specified artist in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param  \App\Models\Artist  $artist
- `App\Models\Artist $artist`: No description available

**Returns:** `mixed`

---

### destroy

Remove the specified artist from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Artist $artist
- `App\Models\Artist $artist`: No description available

**Returns:** `mixed`

---

### trashed

Display a listing of the trashed artists.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### restore

Restore a soft-deleted artist.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param int $id
- `mixed $id`: No description available

**Returns:** `mixed`

---

### forceDelete

Permanently delete an artist.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param int $id
- `mixed $id`: No description available

**Returns:** `mixed`

---

### validationRules

Get the validation rules for the artist form.

**Visibility:** private

**Parameters:**

- `mixed $id`: The ID of the artist being validated (optional).

**Returns:** `mixed`

---

### generateUniqueSlug

Generate a unique slug for an artist based on their name.
This method creates a URL-friendly slug from the given name and ensures
its uniqueness by appending a counter if necessary. If an ID is provided,
it will exclude that ID from the uniqueness check, which is useful for
updating existing records.

**Visibility:** private

**Parameters:**

- `mixed $name`: The name to generate the slug from.
- `mixed $id`: The ID to exclude from the uniqueness check (optional).

**Returns:** `mixed`

---

