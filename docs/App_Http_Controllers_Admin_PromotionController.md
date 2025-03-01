# PromotionController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/PromotionController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the promotions.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### create

Display the form for creating a new promotion.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### edit

Display the form for editing the specified promotion.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Promotion $promotion
- `App\Models\Promotion $promotion`: No description available

**Returns:** `mixed`

---

### show

Display the specified promotion.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Promotion $promotion
- `App\Models\Promotion $promotion`: No description available

**Returns:** `mixed`

---

### store

Store a newly created promotion in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### update

Update the specified promotion in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Promotion $promotion
- `App\Models\Promotion $promotion`: No description available

**Returns:** `mixed`

---

### destroy

Remove the specified promotion from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param  \App\Models\Promotion  $promotion
- `App\Models\Promotion $promotion`: No description available

**Returns:** `mixed`

---

### trashed

Display a listing of the trashed promotions.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\Response

**Returns:** `mixed`

---

### restore

Restore a trashed promotion.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the promotion to restore.

**Returns:** `mixed`

---

### forceDelete

Permanently delete a promotion.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the promotion to be deleted.

**Returns:** `mixed`

---

