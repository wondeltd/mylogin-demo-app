<div class="p-6">
    <code class="min-w-full" x-data="{ thing: JSON.stringify({{ json_encode($userData['content']) }}, null, 4) }">
        <pre x-text="thing"></pre>
    </code>
</div>
