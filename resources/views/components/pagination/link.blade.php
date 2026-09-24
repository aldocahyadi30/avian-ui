{{--
    One page link inside `<x-avian::pagination>`. Renders a `gotoPage()`
    button for Livewire, or a plain link to the paginator's URL otherwise.
--}}
@props([
    'paginator',
    'page',
    'livewire' => false,
])

@php
    $tag = $livewire ? 'button' : 'a';
@endphp

<{{ $tag }}
    {{ $attributes->class(['aui-pagination-link'])->merge([
        'type' => $livewire ? 'button' : null,
        'wire:click' => $livewire ? "gotoPage({$page}, '{$paginator->getPageName()}')" : null,
        'href' => $livewire ? null : $paginator->url($page),
    ]) }}
>{{ $slot }}</{{ $tag }}>
