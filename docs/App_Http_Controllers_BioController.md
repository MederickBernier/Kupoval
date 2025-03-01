# BioController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/BioController.php`

## Description

No documentation available

## Methods

### index

Display a listing of the artists.
This method retrieves all artists ordered by name in ascending order.
If no artists are found, it redirects to the home page with a warning message.
If only one artist is found, it redirects to the bio show page for that artist.
Otherwise, it returns the bio index view with the list of artists.

**Visibility:** public

**Returns:** `mixed`

---

### show

Display the specified artist's biography.

**Visibility:** public

**Parameters:**

- `App\Models\Artist $artist`: The artist whose biography is to be displayed.

**Returns:** `mixed`

---

