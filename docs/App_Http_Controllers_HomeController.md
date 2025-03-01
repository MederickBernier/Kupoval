# HomeController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/HomeController.php`

## Description

No documentation available

## Methods

### index

Display the homepage.
This method loads featured and recent artworks, as well as upcoming events,
and passes them to the 'public.home' view. If a database error occurs, it logs
the error and redirects to the home page with an error message. If any other
exception occurs, it logs the error and redirects to the home page with a generic
error message.

**Visibility:** public

**Returns:** `mixed`

---

### about

Display the About page.
This method loads the static content for the About page and the first artist record,
then passes them to the 'public.about' view. If an exception occurs, it logs the error
and redirects to the home page with an error message.

**Visibility:** public

**Returns:** `mixed`

---

### contact

Display the contact page.
This method attempts to load the contact page by retrieving site settings
such as address, phone, and email from the database. If successful, it
returns the contact view with the settings. If a database error occurs,
it logs the error and redirects to the home page with an error message.
If any other exception occurs, it logs the error and redirects to the
home page with a generic error message.

**Visibility:** public

**Returns:** `mixed`

---

### gallery

Display the gallery page.
This method attempts to load the gallery page view. If an exception occurs during the process,
it logs the error and redirects the user to the home page with an error message.

**Visibility:** public

**Returns:** `mixed`

---

### events

Display a listing of upcoming events grouped by month and year.
This method retrieves events from the database that have a start date
greater than or equal to the current date. The events are then grouped
by their start date's month and year, and passed to the 'public.events' view.
Returns the events view if successful, or redirects to the home page
with an error message if an exception occurs.
If a database error occurs while retrieving the events.
If any other error occurs while loading the events page.

**Visibility:** public

**Returns:** `mixed`

---

