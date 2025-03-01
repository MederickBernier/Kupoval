# AdminEventsController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminEventsController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the events.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### store

Store a newly created event in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### update

Update the specified event in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Event $event
- `App\Models\Event $event`: No description available

**Returns:** `mixed`

---

### destroy

Remove the specified event from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Event $event
- `App\Models\Event $event`: No description available

**Returns:** `mixed`

---

### trashed

Display a listing of the trashed events.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\Response

**Returns:** `mixed`

---

### restore

Restore a trashed event.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The HTTP request instance.
- `mixed $id`: The ID of the event to restore.

**Returns:** `mixed`

---

### forceDelete

Permanently delete a trashed event.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the event to be permanently deleted.

**Returns:** `mixed`

---

