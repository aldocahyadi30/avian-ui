/*!
 * Avian UI - Alpine behaviours
 *
 * Alpine itself is never bundled here. This file only adds components to it, so
 * it has to run BEFORE Alpine starts: put this script tag above Alpine's own.
 * A Livewire application loads Alpine itself, at the end of the page, so a tag
 * in <head> is already early enough.
 *
 * The factories are also published as plain globals, which is what x-data falls
 * back to for markup that Alpine initialises later (a lazily injected fragment,
 * for instance).
 */
(function () {
    'use strict';

    /* Body scroll locking, reference counted so nested overlays behave. */
    var locks = 0;

    function lockScroll() {
        locks += 1;
        document.body.classList.add('aui-scroll-locked');
    }

    function unlockScroll() {
        locks = Math.max(0, locks - 1);

        if (locks === 0) {
            document.body.classList.remove('aui-scroll-locked');
        }
    }

    function releaseScroll() {
        locks = 0;
        document.body.classList.remove('aui-scroll-locked');
    }

    /* Read the modal name out of a browser event, however it was dispatched. */
    function eventName(event) {
        var detail = event.detail;

        if (typeof detail === 'string') {
            return detail;
        }

        if (detail && typeof detail === 'object') {
            if (typeof detail.name === 'string') {
                return detail.name;
            }

            /* Livewire wraps positional dispatch params in an array. */
            if (Array.isArray(detail.params) && typeof detail.params[0] === 'string') {
                return detail.params[0];
            }
        }

        return null;
    }

    var components = {
        /**
         * Modal / dialog.
         *
         * Open it from anywhere with a browser event:
         *   window.AvianUI.openModal('edit')                      // plain JS
         *   $dispatch('aui-modal-open', { name: 'edit' })         // Alpine
         *   $this->dispatch('aui-modal-open', name: 'edit');      // Livewire
         */
        auiModal: function (config) {
            config = config || {};

            return {
                open: config.open === true,
                name: config.name || null,
                closeOnEscape: config.closeOnEscape !== false,
                closeOnOverlay: config.closeOnOverlay !== false,
                locked: false,

                init: function () {
                    var self = this;

                    this.$watch('open', function (value) {
                        value ? self.lock() : self.unlock();
                    });

                    this.onOpen = function (event) {
                        if (self.name && eventName(event) === self.name) {
                            self.show();
                        }
                    };

                    this.onClose = function (event) {
                        var target = eventName(event);

                        if (! target || (self.name && target === self.name)) {
                            self.hide();
                        }
                    };

                    window.addEventListener('aui-modal-open', this.onOpen);
                    window.addEventListener('aui-modal-close', this.onClose);

                    if (this.open) {
                        this.lock();
                    }
                },

                destroy: function () {
                    window.removeEventListener('aui-modal-open', this.onOpen);
                    window.removeEventListener('aui-modal-close', this.onClose);

                    this.unlock();
                },

                lock: function () {
                    if (! this.locked) {
                        this.locked = true;
                        lockScroll();
                    }
                },

                unlock: function () {
                    if (this.locked) {
                        this.locked = false;
                        unlockScroll();
                    }
                },

                show: function () {
                    this.open = true;
                },

                hide: function () {
                    this.open = false;
                },

                toggle: function () {
                    this.open = ! this.open;
                },

                escape: function () {
                    if (this.closeOnEscape) {
                        this.hide();
                    }
                },

                overlay: function (event) {
                    if (this.closeOnOverlay && event.target === event.currentTarget) {
                        this.hide();
                    }
                },
            };
        },

        /** Dropdown menu. */
        auiDropdown: function (config) {
            config = config || {};

            return {
                open: config.open === true,
                closeOnSelect: config.closeOnSelect !== false,

                toggle: function () {
                    this.open = ! this.open;
                },

                show: function () {
                    this.open = true;
                },

                hide: function () {
                    this.open = false;
                },

                select: function () {
                    if (this.closeOnSelect) {
                        this.open = false;
                    }
                },
            };
        },

        /** Tab set. */
        auiTabs: function (config) {
            config = config || {};

            return {
                active: config.active || null,

                select: function (tab) {
                    this.active = tab;

                    this.$dispatch('aui-tab-changed', { tab: tab });
                },

                isActive: function (tab) {
                    return this.active === tab;
                },
            };
        },

        /** Dismissible element (alerts, banners). */
        auiDismiss: function (config) {
            config = config || {};

            return {
                visible: config.visible !== false,

                dismiss: function () {
                    this.visible = false;

                    this.$dispatch('aui-dismissed');
                },
            };
        },

        /**
         * Searchable select (combobox).
         *
         * A `<select>` replacement with a search box. By default it filters
         * client-side, against the rendered option labels. Pass a
         * `searchProperty` (Livewire only) to hand filtering to the server
         * instead — the parent returns an already-filtered option list, so
         * `filter()` becomes a no-op and the search input binds with
         * `wire:model` directly.
         *
         * The dropdown is teleported to <body> and positioned from the
         * trigger's bounding rect, so it never gets clipped by an
         * `overflow: hidden` ancestor (a card, for instance).
         */
        auiSearchableSelect: function (config) {
            config = config || {};

            return {
                open: false,
                search: '',
                property: config.property || null,
                searchProperty: config.searchProperty || null,
                labels: config.labels || {},
                localValue: config.value || null,

                top: 0,
                left: 0,
                width: 0,

                /* Read through $wire so a server-side change reaches the
                   label too; falls back to local state when there is no
                   wire:model. */
                get selectedValue() {
                    var raw = this.property ? this.$wire.$get(this.property) : this.localValue;

                    return raw === null || raw === undefined || raw === '' ? null : String(raw);
                },

                get selectedLabel() {
                    var value = this.selectedValue;

                    return value === null ? null : (this.labels[value] ?? null);
                },

                remember: function (value, label) {
                    if (value === null || value === '') {
                        return;
                    }

                    this.labels[String(value)] = label;
                },

                isSelected: function (value) {
                    return this.selectedValue !== null && this.selectedValue === String(value);
                },

                items: function () {
                    return this.$refs.list ? Array.from(this.$refs.list.querySelectorAll('.aui-combobox-item')) : [];
                },

                visibleItems: function () {
                    return this.items().filter(function (item) {
                        return ! item.hidden;
                    });
                },

                filter: function () {
                    if (this.searchProperty) {
                        return;
                    }

                    var term = this.search.trim().toLowerCase();

                    this.items().forEach(function (item) {
                        var text = (item.dataset.label || item.textContent || '').toLowerCase();
                        item.hidden = term !== '' && text.indexOf(term) === -1;
                        item.classList.remove('is-highlighted');
                    });

                    if (this.$refs.empty) {
                        this.$refs.empty.hidden = this.visibleItems().length > 0;
                    }
                },

                reposition: function () {
                    var rect = this.$refs.trigger.getBoundingClientRect();

                    this.top = rect.bottom + 6;
                    this.left = rect.left;
                    this.width = rect.width;
                },

                toggle: function () {
                    if (this.open) {
                        this.close();

                        return;
                    }

                    var self = this;

                    this.reposition();
                    this.open = true;

                    if (! this.searchProperty) {
                        this.search = '';
                        this.filter();
                    }

                    this.$nextTick(function () {
                        if (self.$refs.search) {
                            self.$refs.search.focus();
                        }
                    });
                },

                close: function () {
                    this.open = false;
                },

                choose: function (value, label) {
                    this.remember(value, label);
                    this.localValue = String(value);

                    /* Queued deferred so it rides along with the request the
                       value change fires — the next open starts from the
                       full list again. */
                    if (this.searchProperty) {
                        this.$wire.$set(this.searchProperty, '', false);

                        if (this.$refs.search) {
                            this.$refs.search.value = '';
                        }
                    } else {
                        this.search = '';
                    }

                    this.$refs.input.value = value;
                    this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
                    this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));

                    this.close();
                    this.$refs.trigger.focus();
                },

                move: function (step) {
                    var items = this.visibleItems();

                    if (! items.length) {
                        return;
                    }

                    var current = items.findIndex(function (item) {
                        return item.classList.contains('is-highlighted');
                    });
                    var next = current === -1
                        ? (step > 0 ? 0 : items.length - 1)
                        : (current + step + items.length) % items.length;

                    items.forEach(function (item) {
                        item.classList.remove('is-highlighted');
                    });
                    items[next].classList.add('is-highlighted');
                    items[next].scrollIntoView({ block: 'nearest' });
                },

                chooseHighlighted: function () {
                    var items = this.visibleItems();
                    var item = (this.$refs.list && this.$refs.list.querySelector('.aui-combobox-item.is-highlighted')) || items[0];

                    if (item) {
                        item.click();
                    }
                },
            };
        },

        /** File input with a filename readout. */
        auiFile: function (config) {
            config = config || {};

            return {
                placeholder: config.placeholder || 'No file chosen',
                names: [],

                get label() {
                    if (this.names.length === 0) {
                        return this.placeholder;
                    }

                    if (this.names.length === 1) {
                        return this.names[0];
                    }

                    return this.names.length + ' files selected';
                },

                browse: function () {
                    this.$refs.input.click();
                },

                update: function (event) {
                    var files = event.target.files;

                    this.names = files ? Array.prototype.map.call(files, function (file) {
                        return file.name;
                    }) : [];
                },

                reset: function () {
                    this.$refs.input.value = '';
                    this.names = [];
                },
            };
        },
    };

    /*
     * x-data resolves an unknown name against the global scope, so exposing the
     * factories here keeps the components working even when this file is loaded
     * after Alpine has already started.
     */
    Object.keys(components).forEach(function (name) {
        if (! window[name]) {
            window[name] = components[name];
        }
    });

    var registered = false;

    function register(Alpine) {
        if (registered || ! Alpine) {
            return;
        }

        registered = true;

        Object.keys(components).forEach(function (name) {
            Alpine.data(name, components[name]);
        });
    }

    /* Imperative API for code that is not running inside Alpine. */
    window.AvianUI = window.AvianUI || {
        openModal: function (name) {
            window.dispatchEvent(new CustomEvent('aui-modal-open', { detail: { name: name } }));
        },
        closeModal: function (name) {
            window.dispatchEvent(new CustomEvent('aui-modal-close', { detail: { name: name || null } }));
        },
    };

    document.addEventListener('alpine:init', function () {
        register(window.Alpine);
    });

    /* wire:navigate replaces the body: never leave it locked behind. */
    document.addEventListener('livewire:navigating', releaseScroll);
})();
