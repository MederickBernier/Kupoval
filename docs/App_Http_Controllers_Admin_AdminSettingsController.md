# AdminSettingsController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminSettingsController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the settings.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### store

Store a newly created setting in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### update

Update the specified setting in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param int $id
- `mixed $id`: No description available

**Returns:** `mixed`

---

### destroy

Remove the specified setting from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param  \App\Models\Setting  $setting
- `App\Models\Setting $setting`: No description available

**Returns:** `mixed`

---

