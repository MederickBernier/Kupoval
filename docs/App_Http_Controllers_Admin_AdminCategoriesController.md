# AdminCategoriesController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminCategoriesController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the categories.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### store

Store a newly created category in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### edit

Display the form for editing the specified category.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Category $category
- `App\Models\Category $category`: No description available

**Returns:** `mixed`

---

### update

Update the specified category in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Category $category
- `App\Models\Category $category`: No description available

**Returns:** `mixed`

---

### destroy

Remove the specified category from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Category $category
- `App\Models\Category $category`: No description available

**Returns:** `mixed`

---

### trashed

Display a listing of the trashed categories.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\Response

**Returns:** `mixed`

---

### restore

Restore a soft-deleted category.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param int $id
- `mixed $id`: No description available

**Returns:** `mixed`

---

### forceDelete

Permanently delete a category.
This method attempts to permanently delete a category identified by its ID.
It first checks if the user has the necessary permissions to perform this action.
If the category is linked to any artworks, it cannot be deleted and an error message is returned.
If the deletion is successful, the user is redirected to the trashed categories list with a success message.
In case of any errors during the process, an error message is logged and returned.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the category to be permanently deleted.

**Returns:** `mixed`

---

