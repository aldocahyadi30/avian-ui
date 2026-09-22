{{--
    Alpine is not shipped by this package; this script only registers components
    on it, so it must run before Alpine starts. Livewire loads Alpine at the end
    of the page, so this tag in <head> is early enough on its own. Without
    Livewire, keep your own Alpine tag below this one.
--}}
<script src="{{ app(\AvianUi\AvianUi\AvianUi::class)->scriptUrl() }}" {{ $attributes->merge(['defer' => true]) }}></script>
