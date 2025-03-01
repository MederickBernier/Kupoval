# AdminOrdersController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminOrdersController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the orders.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### show

Display the specified order details.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Order $order
- `App\Models\Order $order`: No description available

**Returns:** `mixed`

---

### create

Display the form for creating a new order.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### store

Store a newly created order in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### update

Update the specified order in storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param  \App\Models\Order  $order
- `App\Models\Order $order`: No description available

**Returns:** `mixed`

---

### edit

Edit the specified order.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `App\Models\Order $order`: The order instance to be edited.

**Returns:** `mixed`

---

### destroy

Remove the specified order from storage.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param \App\Models\Order $order
- `App\Models\Order $order`: No description available

**Returns:** `mixed`

---

### trashed

Display a listing of the trashed orders.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\Response

**Returns:** `mixed`

---

### restore

Restore a soft-deleted order.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the order to restore.

**Returns:** `mixed`

---

### forceDelete

Permanently delete a trashed order.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the order to be permanently deleted.

**Returns:** `mixed`

---

