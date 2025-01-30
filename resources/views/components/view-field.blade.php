<div id="{{ $field }}-container">
    <span id="{{ $field }}-text">{{ $value }}</span>
    <i class="bi bi-pencil text-gray-500 hover:text-gray-700 cursor-pointer"
       hx-get="{{ route('user.edit-field', ['field' => $field]) }}"
       hx-target="#{{ $field }}-container"
       hx-swap="outerHTML"></i>
</div>
