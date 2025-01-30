<form hx-post="{{ route('user.update-field', ['field' => $field]) }}"
    hx-target="#{{ $field }}-container"
    hx-swap="outerHTML">
  <input type="text"
         name="{{ $field }}"
         value="{{ $value }}"
         class="border px-2 py-1 rounded w-full"
         hx-trigger="keyup[keyCode==13], blur">
</form>
