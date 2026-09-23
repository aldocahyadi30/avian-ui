{{--
    A `<select>` replacement with a search box, for option lists too long to
    scan in a native dropdown. Behaves like `<x-avian::select>`: same field
    chrome, error resolution, old-input repopulation and `wire:model`
    support.

    Usage:
        <x-avian::searchable-select
            name="role"
            label="Role"
            :options="['admin' => 'Administrator', 'editor' => 'Editor']"
            wire:model="role"
        />

    By default filtering happens client-side, in the browser, against the
    rendered option labels — no Livewire component required.

    Pass `search-model` to hand filtering to the server instead (Livewire
    only): the parent owns the search property and hands back an already
    filtered `options` list, exactly like it already owns the selected value
    through `wire:model` + `:value`:

        <x-avian::searchable-select
            wire:model.live="filter.status"
            :value="$filter['status']"
            :options="$this->statusOptions"
            search-model="filter.statusSearch"
        />

    In `search-model` mode, `options` will not contain the selected value
    once a search term filters it out, so the trigger label is resolved
    client-side from a small `value => label` cache: seeded with the current
    selection on first render, topped up by every option the dropdown renders
    (see searchable-select/option.blade.php) and by whatever the user picks.

    Custom option markup: drop `options` and pass children instead —

        <x-avian::searchable-select wire:model="itemNo" :value="$itemNo">
            @foreach ($items as $item)
                <x-avian::searchable-select.option :value="$item->id" :label="$item->name" :selected="$itemNo">
                    <strong>{{ $item->id }}</strong> <small>{{ $item->name }}</small>
                </x-avian::searchable-select.option>
            @endforeach
        </x-avian::searchable-select>

    The dropdown itself is teleported to <body> and positioned with fixed
    coordinates computed from the trigger's bounding rect — cards use
    `overflow: hidden` for their rounded corners, which would otherwise clip
    an absolutely-positioned dropdown that overflows the card.
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'options' => null,
    'label' => null,
    'placeholder' => 'Select an option',
    'searchPlaceholder' => 'Search...',
    'emptyText' => 'No results found.',
    'searchModel' => null,
    'searchDebounce' => '250ms',
    'hint' => null,
    'error' => null,
    'errorBag' => null,
    'required' => false,
    'size' => null,
    'field' => true,
    'disabled' => false,
])

@php
    $avianUi = app(\AvianUi\AvianUi\AvianUi::class);

    $inputError = $error ?? $avianUi->errorFor($name, $errorBag);
    $inputId =
        $id ?? (filled($name) ? 'aui-' . str_replace(['[', ']', '.', '_'], '-', trim((string) $name, '[]')) : null);

    $valueAttributes = $attributes->whereStartsWith('wire:model');
    $rootAttributes = $attributes->except(array_keys($valueAttributes->getAttributes()));

    $wired = $valueAttributes->isNotEmpty();
    $selected = $value;

    if ($selected === null && !$wired) {
        $selected = $avianUi->oldValue($name);
    }

    // `wire:model.live="filter.status"` → `filter.status`. Writes still go
    // through the hidden input below so every wire:model modifier keeps
    // behaving as usual; the property name is only used to *read* the value
    // back through `$wire.$get()`, which is what keeps the trigger label
    // honest when the server changes the selection on its own (a reset, or a
    // default applied inside an action).
    $modelAttributes = $valueAttributes->getAttributes();
    $valueProperty = $modelAttributes ? reset($modelAttributes) : null;

    // `options` may be an array or anything collectable (a Collection from
    // pluck(), for instance) — `null` is what puts the component in slot mode.
    $optionList = $options === null ? null : (is_array($options) ? $options : collect($options)->all());

    $hasValue = $selected !== null && $selected !== '';
    $selectedLabel = $hasValue && $optionList !== null ? $optionList[$selected] ?? null : null;
    $seedLabels = $hasValue && $selectedLabel !== null ? [(string) $selected => (string) $selectedLabel] : [];
@endphp

<x-avian::field :bare="!$field" :label="$label" :for="$inputId" :hint="$hint" :error="$inputError"
    :required="$required">
    {{-- `x-data` must stay identical across Livewire re-renders: when its
         expression changes, the morph patches the attribute and Alpine
         re-initialises the component — wiping the label cache the moment a
         search filters the selection out of `options`. Anything that varies
         per render is handed over through `data-*` attributes instead, which
         `init()` reads once and later morphs can patch harmlessly. --}}
    <div x-data="auiSearchableSelect({
        property: @js($valueProperty),
        searchProperty: @js($searchModel),
    })" data-aui-value="{{ $hasValue ? (string) $selected : '' }}"
        data-aui-labels="{{ json_encode((object) $seedLabels) }}" x-ref="wrapper"
        x-on:click.window="if (open && !$refs.wrapper.contains($event.target) && !$refs.dropdown.contains($event.target)) close()"
        x-on:resize.window="if (open) reposition()" x-on:scroll.window="if (open) reposition()"
        {{ $rootAttributes->class(['aui-combobox', 'is-disabled' => $disabled]) }}
        :class="{ 'is-open': open }">
        <button type="button" id="{{ $inputId }}" x-ref="trigger"
            class="aui-select aui-combobox-trigger{{ $size ? ' aui-select-' . $size : '' }}{{ filled($inputError) ? ' aui-select-invalid' : '' }}"
            x-on:click="toggle()" x-bind:aria-expanded="open" aria-haspopup="listbox"
            aria-invalid="{{ filled($inputError) ? 'true' : 'false' }}" @disabled($disabled)>
            {{-- `wire:ignore`: the server-rendered text is only a first-paint
                 fallback. Once a search filters the selection out, the server
                 would render the placeholder here, and since nothing reactive
                 changed, `x-text` would not run again to put the label back. --}}
            <span wire:ignore class="aui-combobox-value{{ $selectedLabel === null ? ' is-placeholder' : '' }}"
                :class="{ 'is-placeholder': selectedLabel === null }"
                x-text="selectedLabel ?? @js($placeholder)">{{ $selectedLabel ?? $placeholder }}</span>
            <i class="fas fa-chevron-down aui-combobox-arrow" aria-hidden="true"></i>
        </button>

        <input type="hidden" x-ref="input" @if ($name) name="{{ $name }}" @endif
            {{ $valueAttributes }} value="{{ $selected }}">

        {{-- Re-seeds the label cache whenever the server can resolve it,
             independent of whatever `options` the search term currently
             filters down to. `wire:key` ties this to (value, label), so it
             only remounts — and only then re-runs `remember()` — when either
             one actually changes; when the value falls out of the filtered
             list this simply stops rendering without touching the cache. --}}
        @if ($selectedLabel !== null)
            <span wire:key="{{ ($inputId ?? 'aui-combobox') . '-seed-' . $selected . '-' . md5($selectedLabel) }}"
                x-init="remember(@js((string) $selected), @js($selectedLabel))" hidden aria-hidden="true"></span>
        @endif

        <template x-teleport="body">
            <div x-ref="dropdown" class="aui-combobox-dropdown" x-show="open" x-cloak
                :style="{ top: top + 'px', left: left + 'px', width: width + 'px' }"
                x-on:keydown.escape.prevent="close(); $refs.trigger.focus()" x-on:keydown.down.prevent="move(1)"
                x-on:keydown.up.prevent="move(-1)" x-on:keydown.enter.prevent="chooseHighlighted()" role="listbox">
                <div class="aui-combobox-search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    @if ($searchModel)
                        <input type="text" x-ref="search" class="aui-combobox-search-input"
                            placeholder="{{ $searchPlaceholder }}"
                            wire:model.live.debounce.{{ $searchDebounce }}="{{ $searchModel }}">
                    @else
                        <input type="text" x-ref="search" class="aui-combobox-search-input"
                            placeholder="{{ $searchPlaceholder }}" x-model="search" x-on:input="filter()">
                    @endif
                </div>

                <div class="aui-combobox-list" x-ref="list">
                    @if ($optionList)
                        @foreach ($optionList as $optionValue => $optionLabel)
                            <x-avian::searchable-select.option :value="$optionValue" :label="$optionLabel" :selected="$selected" />
                        @endforeach
                    @elseif ($slot->isNotEmpty())
                        {{ $slot }}
                    @endif

                    @if ($searchModel)
                        @if (!$optionList && $slot->isEmpty())
                            <p class="aui-combobox-empty">{{ $emptyText }}</p>
                        @endif
                    @else
                        <p class="aui-combobox-empty" x-ref="empty" @if (filled($optionList) || $slot->isNotEmpty()) hidden @endif>
                            {{ $emptyText }}</p>
                    @endif
                </div>
            </div>
        </template>
    </div>
</x-avian::field>
