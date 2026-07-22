{{--
    Dialog root. Holds the open state.
      open  initial open state
      id    optional — enables "dispatchable" mode: the dialog also opens/closes from
            anywhere via $dispatch('open-dialog-{id}') / $dispatch('close-dialog-{id}').
            Lets a trigger inside a @foreach/partial drive a single shared modal defined
            elsewhere (use <x-ui.dialog-trigger for="{id}"> or dispatch the event yourself).
--}}
@props(['open' => false, 'id' => null])

@php
    $wireModel = \Illuminate\View\ComponentAttributeBag::hasMacro('wire') ? $attributes->wire('model') : null;
    $hasWire = $wireModel && is_string($wireModel->value()) && $wireModel->value() !== '';
    if ($hasWire) { $attributes = $attributes->whereDoesntStartWith('wire:model'); }
@endphp

<div
    data-slot="dialog"
    x-data="{ open: @if ($hasWire)@entangle($wireModel)@else @js((bool) $open)@endif }"
    @if ($id)
        @open-dialog-{{ $id }}.window="open = true"
        @close-dialog-{{ $id }}.window="open = false"
    @endif
    x-id="['blat-dialog']"
    {{ $attributes }}
>
    {{ $slot }}
</div>
