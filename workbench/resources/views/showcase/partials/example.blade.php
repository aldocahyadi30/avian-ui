{{-- One usage example. Expects `$example` with a `title`, a `code` and an optional `text` explanation. --}}
<div class="aui-showcase-example">
    <h5 class="aui-showcase-example-title">{{ $example['title'] }}</h5>

    @if (filled($example['text'] ?? null))
        <p class="aui-showcase-text">{{ $example['text'] }}</p>
    @endif

    <pre class="aui-showcase-code"><code>{{ $example['code'] }}</code></pre>
</div>
