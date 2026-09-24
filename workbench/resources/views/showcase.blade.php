@php
    // Each sidebar entry is a page under resources/views/showcase/, keyed by its view name.
    $nav = [
        'General' => [
            'getting-started' => ['label' => 'Getting started', 'icon' => 'fas fa-house'],
        ],
        'Actions & display' => [
            'components.button' => ['label' => 'Button', 'icon' => 'fas fa-hand-pointer'],
            'components.badge' => ['label' => 'Badge', 'icon' => 'fas fa-certificate'],
            'components.avatar' => ['label' => 'Avatar', 'icon' => 'fas fa-circle-user'],
            'components.progress' => ['label' => 'Progress', 'icon' => 'fas fa-chart-simple'],
            'components.spinner' => ['label' => 'Spinner', 'icon' => 'fas fa-spinner'],
        ],
        'Layout' => [
            'components.page-header' => ['label' => 'Page header', 'icon' => 'fas fa-heading'],
            'components.breadcrumbs' => ['label' => 'Breadcrumbs', 'icon' => 'fas fa-angles-right'],
            'components.card' => ['label' => 'Card', 'icon' => 'fas fa-square'],
            'components.stat' => ['label' => 'Stat', 'icon' => 'fas fa-chart-line'],
            'components.accordion' => ['label' => 'Accordion', 'icon' => 'fas fa-bars-staggered'],
            'components.divider' => ['label' => 'Divider', 'icon' => 'fas fa-grip-lines'],
        ],
        'Forms' => [
            'forms.form' => ['label' => 'Form & layout', 'icon' => 'fas fa-pen-to-square'],
            'forms.field' => ['label' => 'Field, label & error', 'icon' => 'fas fa-tag'],
            'forms.input' => ['label' => 'Input', 'icon' => 'fas fa-i-cursor'],
            'forms.textarea' => ['label' => 'Textarea', 'icon' => 'fas fa-align-left'],
            'forms.select' => ['label' => 'Select', 'icon' => 'fas fa-list'],
            'forms.searchable-select' => ['label' => 'Searchable select', 'icon' => 'fas fa-magnifying-glass'],
            'forms.multi-select' => ['label' => 'Multi select', 'icon' => 'fas fa-list-check'],
            'forms.datepicker' => ['label' => 'Datepicker', 'icon' => 'fas fa-calendar-days'],
            'forms.file' => ['label' => 'File', 'icon' => 'fas fa-paperclip'],
            'forms.checkbox' => ['label' => 'Checkbox', 'icon' => 'fas fa-square-check'],
            'forms.radio' => ['label' => 'Radio', 'icon' => 'fas fa-circle-dot'],
            'forms.switch' => ['label' => 'Switch', 'icon' => 'fas fa-toggle-on'],
        ],
        'Data & navigation' => [
            'components.table' => ['label' => 'Table', 'icon' => 'fas fa-table'],
            'components.datalist' => ['label' => 'Datalist', 'icon' => 'fas fa-grip'],
            'components.pagination' => ['label' => 'Pagination', 'icon' => 'fas fa-ellipsis'],
            'components.tabs' => ['label' => 'Tabs', 'icon' => 'fas fa-folder'],
            'components.dropdown' => ['label' => 'Dropdown', 'icon' => 'fas fa-caret-down'],
        ],
        'Overlays & feedback' => [
            'components.modal' => ['label' => 'Modal', 'icon' => 'fas fa-window-restore'],
            'components.drawer' => ['label' => 'Drawer', 'icon' => 'fas fa-table-columns'],
            'components.confirm' => ['label' => 'Confirm dialog', 'icon' => 'fas fa-circle-question'],
            'components.alert' => ['label' => 'Alert', 'icon' => 'fas fa-circle-info'],
            'components.empty' => ['label' => 'Empty state', 'icon' => 'fas fa-inbox'],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="emerald-green">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avian UI</title>

    {{--
        Fonts, icons and Alpine are pulled from a CDN for this local workbench
        preview only. The package itself never loads anything remote: it ships
        its own CSS and JS, and leaves fonts, icons and Alpine to the host app.
    --}}
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:600,700,800">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <x-avian::styles />

    {{-- The package script registers its Alpine components, so it loads first. --}}
    <x-avian::scripts />
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- The datepicker leaves flatpickr to the host app; the workbench loads it the documented way. --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.flatpickr-input').forEach((input) => {
                flatpickr(input, {
                    mode: input.dataset.fpMode,
                    dateFormat: input.dataset.fpDateFormat,
                    enableTime: input.dataset.fpEnableTime === 'true',
                    noCalendar: input.dataset.fpNoCalendar === 'true',
                    time_24hr: input.dataset.fpTime24hr === 'true',
                    minDate: input.dataset.fpMinDate || null,
                    maxDate: input.dataset.fpMaxDate || null,
                    minTime: input.dataset.fpMinTime || null,
                    maxTime: input.dataset.fpMaxTime || null,
                });
            });
        });
    </script>

    <style>
        body { margin: 0; padding: 0; background: #f5f7fa; font-family: var(--aui-font-sans); }
        [x-cloak] { display: none !important; }

        .aui-showcase { display: flex; align-items: flex-start; min-height: 100vh; }

        .aui-showcase-sidebar {
            position: sticky;
            top: 0;
            flex: 0 0 250px;
            box-sizing: border-box;
            height: 100vh;
            overflow-y: auto;
            padding: 24px 14px;
            background: #ffffff;
            border-right: 1px solid #e5e9f0;
        }
        .aui-showcase-brand { padding: 0 10px 18px; }
        .aui-showcase-brand strong { display: block; font-family: var(--aui-font-display); font-size: 18px; }
        .aui-showcase-brand span { color: #6b7280; font-size: 13px; }

        .aui-showcase-nav { display: flex; flex-direction: column; gap: 2px; }
        .aui-showcase-nav-group {
            padding: 16px 10px 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #9ca3af;
        }
        .aui-showcase-nav-group:first-child { padding-top: 0; }
        .aui-showcase-nav button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
            padding: 9px 10px;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #374151;
            cursor: pointer;
        }
        .aui-showcase-nav button i { width: 16px; text-align: center; color: #9ca3af; }
        .aui-showcase-nav button:hover { background: #f3f4f6; }
        .aui-showcase-nav button.is-active { background: var(--aui-primary, #16a34a); color: #fff; }
        .aui-showcase-nav button.is-active i { color: #fff; }

        .aui-showcase-content { flex: 1 1 auto; min-width: 0; padding: 30px; max-width: 1080px; margin: 0 auto; }
        .aui-showcase-content > * + * { margin-top: 24px; }
        .aui-showcase-section > * + * { margin-top: 24px; }

        .aui-showcase-code {
            margin: 0;
            padding: 14px 16px;
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 10px;
            overflow-x: auto;
            font-size: 12.5px;
            line-height: 1.6;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        .aui-showcase-lead { margin: 0 0 20px; font-size: 14px; line-height: 1.65; color: #4b5563; }
        .aui-showcase-text { margin: 0 0 10px; font-size: 13.5px; line-height: 1.6; color: #4b5563; }
        .aui-showcase-note { margin: 10px 0 0; font-size: 12.5px; color: #6b7280; }
        .aui-showcase-lead code, .aui-showcase-text code, .aui-showcase-list code, .aui-showcase-note code, .aui-showcase-props code {
            padding: 1px 5px;
            background: #f1f5f9;
            border-radius: 4px;
            font-size: .92em;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            color: #0f172a;
        }
        .aui-showcase-props code.aui-showcase-type { background: transparent; padding: 0; color: #64748b; }
        .aui-showcase-props td { vertical-align: top; }
        .aui-showcase-props td:nth-child(-n+3) { white-space: nowrap; }

        .aui-showcase-theme.is-active,
        .aui-showcase-theme.is-active:hover { background: var(--aui-primary); border-color: var(--aui-primary); color: #fff; }

        .aui-showcase-demo {
            padding: 22px;
            background: #f8fafc;
            border: 1px dashed #dbe1ea;
            border-radius: 12px;
        }

        .aui-showcase-block { margin-top: 30px; }
        .aui-showcase-heading {
            margin: 0 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eef1f5;
            font-family: var(--aui-font-display);
            font-size: 15px;
            color: #111827;
        }
        .aui-showcase-list { margin: 0; padding-left: 20px; font-size: 13.5px; line-height: 1.7; color: #4b5563; }
        .aui-showcase-list li + li { margin-top: 4px; }

        .aui-showcase-example + .aui-showcase-example { margin-top: 22px; }
        .aui-showcase-example-title { margin: 0 0 6px; font-size: 13.5px; font-weight: 600; color: #111827; }
        .aui-showcase-example .aui-showcase-code { margin-top: 0; }

        @media (max-width: 860px) {
            .aui-showcase { display: block; }
            .aui-showcase-sidebar { position: static; height: auto; width: auto; border-right: 0; border-bottom: 1px solid #e5e9f0; }
            .aui-showcase-content { max-width: none; padding: 16px; }
            .aui-showcase-content .aui-form-grid { grid-template-columns: minmax(0, 1fr); }
        }
    </style>
</head>
<body>
    <div class="aui-showcase" x-data="{ section: 'getting-started' }">
        <aside class="aui-showcase-sidebar">
            <div class="aui-showcase-brand">
                <strong>Avian UI</strong>
                <span>Component showcase</span>
            </div>

            <nav class="aui-showcase-nav">
                @foreach ($nav as $group => $items)
                    <span class="aui-showcase-nav-group">{{ $group }}</span>

                    @foreach ($items as $key => $item)
                        <button
                            type="button"
                            x-on:click="section = '{{ $key }}'; window.scrollTo(0, 0)"
                            x-bind:class="section === '{{ $key }}' ? 'is-active' : ''"
                        >
                            <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                            {{ $item['label'] }}
                        </button>
                    @endforeach
                @endforeach
            </nav>
        </aside>

        <main class="aui-showcase-content">
            @foreach ($nav as $items)
                @foreach (array_keys($items) as $key)
                    <section class="aui-showcase-section" x-show="section === '{{ $key }}'" x-cloak>
                        @include('showcase.'.$key)
                    </section>
                @endforeach
            @endforeach
        </main>
    </div>

    <x-avian::modal name="demo" title="New record" size="lg">
        <x-avian::input name="title" label="Title" />
        <x-avian::textarea name="description" label="Description" rows="3" />

        <x-slot:footer>
            <x-avian::button variant="light" x-on:click="hide()">Cancel</x-avian::button>
            <x-avian::button icon="fas fa-check">Create</x-avian::button>
        </x-slot:footer>
    </x-avian::modal>

    {{-- One shared confirm dialog for the whole page, as an app layout would have. --}}
    <x-avian::confirm />
</body>
</html>
