# InvoiceController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/InvoiceController.php`

## Description

No documentation available

## Methods

### generateInvoice

Generate an invoice for a given order.
This method retrieves the order details, calculates the subtotal, tax, and total amounts,
and generates a PDF invoice using TCPDF. The generated invoice is then returned as a PDF response.

**Visibility:** public

**Parameters:**

- `mixed $orderId`: The ID of the order for which the invoice is to be generated.

**Returns:** `mixed`

---

