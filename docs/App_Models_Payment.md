# Payment

Namespace: `App\Models`

File: `app/Models/Payment.php`

## Description

Class Payment
This model represents a payment in the system.
It uses the HasFactory and SoftDeletes traits.

## Methods

### order

No documentation available

**Visibility:** public

**Returns:** `mixed`

---

### forceDelete

Force a hard delete on a soft deleted model.

**Visibility:** public

**Returns:** `mixed`

---

### forceDestroy

Destroy the models for the given IDs.

**Visibility:** public

**Parameters:**

- `mixed $ids`: * @return int

**Returns:** `mixed`

---

### performDeleteOnModel

Perform the actual delete query on this model instance.

**Visibility:** protected

**Returns:** `mixed`

---

### factory

Get a new factory instance for the model.

**Visibility:** public

**Parameters:**

- `mixed $count`: No description available
- `mixed $state`: No description available

**Returns:** `mixed`

---

### newFactory

Create a new factory instance for the model.

**Visibility:** protected

**Returns:** `mixed`

---

### bootSoftDeletes

Boot the soft deleting trait for a model.

**Visibility:** public

**Returns:** `mixed`

---

### initializeSoftDeletes

Initialize the soft deleting trait for an instance.

**Visibility:** public

**Returns:** `mixed`

---

### forceDeleteQuietly

Force a hard delete on a soft deleted model without raising any events.

**Visibility:** public

**Returns:** `mixed`

---

### runSoftDelete

Perform the actual delete query on this model instance.

**Visibility:** protected

**Returns:** `mixed`

---

### restore

Restore a soft-deleted model instance.

**Visibility:** public

**Returns:** `mixed`

---

### restoreQuietly

Restore a soft-deleted model instance without raising any events.

**Visibility:** public

**Returns:** `mixed`

---

### trashed

Determine if the model instance has been soft-deleted.

**Visibility:** public

**Returns:** `mixed`

---

### softDeleted

Register a "softDeleted" model event callback with the dispatcher.

**Visibility:** public

**Parameters:**

- `mixed $callback`: * @return void

**Returns:** `mixed`

---

### restoring

Register a "restoring" model event callback with the dispatcher.

**Visibility:** public

**Parameters:**

- `mixed $callback`: * @return void

**Returns:** `mixed`

---

### restored

Register a "restored" model event callback with the dispatcher.

**Visibility:** public

**Parameters:**

- `mixed $callback`: * @return void

**Returns:** `mixed`

---

### forceDeleting

Register a "forceDeleting" model event callback with the dispatcher.

**Visibility:** public

**Parameters:**

- `mixed $callback`: * @return void

**Returns:** `mixed`

---

### forceDeleted

Register a "forceDeleted" model event callback with the dispatcher.

**Visibility:** public

**Parameters:**

- `mixed $callback`: * @return void

**Returns:** `mixed`

---

### isForceDeleting

Determine if the model is currently force deleting.

**Visibility:** public

**Returns:** `mixed`

---

### getDeletedAtColumn

Get the name of the "deleted at" column.

**Visibility:** public

**Returns:** `mixed`

---

### getQualifiedDeletedAtColumn

Get the fully qualified "deleted at" column.

**Visibility:** public

**Returns:** `mixed`

---

