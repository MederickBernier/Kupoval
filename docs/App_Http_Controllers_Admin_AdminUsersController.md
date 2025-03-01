# AdminUsersController

Namespace: `App\Http\Controllers\Admin`

File: `app/Http/Controllers/Admin/AdminUsersController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the users.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### destroy

Delete the specified user.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `App\Models\User $user`: The user instance to be deleted.

**Returns:** `mixed`

---

### trashed

Display a listing of trashed users.
This method retrieves users that have been soft deleted (trashed) and displays them
in a paginated format. It also handles any exceptions that may occur during the process.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The incoming request instance.

**Returns:** `mixed`

---

### restore

Restore a soft-deleted user.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The current request instance.
- `mixed $id`: The ID of the user to restore.

**Returns:** `mixed`

---

### forceDelete

Permanently delete a user.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @param int $id
- `mixed $id`: No description available

**Returns:** `mixed`

---

