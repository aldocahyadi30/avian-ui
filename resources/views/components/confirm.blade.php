{{--
    One shared confirmation dialog. Place it once in the layout, next to
    `<x-avian::scripts />`, and ask it for a decision from anywhere:

        <x-avian::confirm />

    Declaratively — any element or form carrying `data-aui-confirm` is held
    back until the user confirms, then its click (or submit) replays as if
    nothing happened, so `wire:click`, `href`, `wire:navigate` and plain form
    submits all keep working:

        <x-avian::button variant="danger" wire:click="delete({{ $id }})" confirm="Delete this order?">Delete</x-avian::button>

        <form method="POST" action="..." data-aui-confirm="Delete this order?" data-aui-confirm-variant="danger">

    From JavaScript — a promise that resolves to true or false:

        AvianUI.confirm({ title: 'Delete order?', message: 'This cannot be undone.' })
            .then((ok) => ok && $wire.delete(5));

    From Livewire — pass `event` (and optionally `params`) and the dialog
    dispatches it back on confirm, for an `#[On('order-delete')]` listener:

        $this->dispatch('aui-confirm', message: 'Delete this order?', event: 'order-delete', params: ['id' => 5]);

    Options, as JS object keys / Livewire named params — or as attributes
    next to `data-aui-confirm` (the message): `title`
    (`data-aui-confirm-title`), `message`, `confirmText`
    (`data-aui-confirm-text`), `cancelText` (`data-aui-cancel-text`) and
    `variant` (`data-aui-confirm-variant`; `danger` by default, also
    `primary`, `warning`, `success`, `info`). Without this component on the page, `AvianUI.confirm()` falls
    back to the browser's native `confirm()`.
--}}
@props([
    'title' => null,
    'confirmText' => null,
    'cancelText' => null,
    'variant' => 'danger',
])

@php
    $config = [
        'title' => $title ?? __('avian-ui::messages.confirm_title'),
        'confirmText' => $confirmText ?? __('avian-ui::messages.confirm'),
        'cancelText' => $cancelText ?? __('avian-ui::messages.cancel'),
        'variant' => $variant,
    ];
@endphp

<div
    x-data="auiConfirm({{ Illuminate\Support\Js::from($config) }})"
    x-on:keydown.escape.window="if (open) { $event.stopImmediatePropagation(); answer(false) }"
    x-cloak
    x-show="open"
    class="aui-modal-overlay aui-confirm-overlay"
    x-on:click="if ($event.target === $event.currentTarget) answer(false)"
    role="alertdialog"
    aria-modal="true"
    aria-labelledby="aui-confirm-title"
    aria-describedby="aui-confirm-message"
>
    <div {{ $attributes->class(['aui-modal', 'aui-modal-sm', 'aui-confirm']) }}>
        <div class="aui-modal-body aui-confirm-body">
            <span class="aui-confirm-icon" x-bind:class="'aui-confirm-icon-' + current.variant" aria-hidden="true">
                <i x-bind:class="icon"></i>
            </span>

            <div class="aui-confirm-content">
                <h2 class="aui-modal-title" id="aui-confirm-title" x-text="current.title"></h2>
                <p class="aui-confirm-message" id="aui-confirm-message" x-show="current.message" x-text="current.message"></p>
            </div>
        </div>

        <div class="aui-modal-footer">
            <button type="button" class="aui-btn aui-btn-light" x-ref="cancel" x-on:click="answer(false)" x-text="current.cancelText"></button>
            <button type="button" class="aui-btn" x-bind:class="'aui-btn-' + current.variant" x-on:click="answer(true)" x-text="current.confirmText"></button>
        </div>
    </div>
</div>
