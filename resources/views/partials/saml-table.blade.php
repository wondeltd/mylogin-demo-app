<div class="p-4">
    <table class="min-w-full">
        <thead class="bg-white border-b">
            <th scope="col" class="text-lg text-gray-900 px-6 py-4 text-left font-bold">Key</th>
            <th scope="col" class="text-lg text-gray-900 py-4 text-left">Value</th>
        </thead>

        @foreach($userData['content'] as $key => $value)
            <tr class="{{ $loop->even ? 'bg-gray-100 border-b' : 'bg-white border-b' }}">
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"> {{ $key }}</td>
                <td> {{ is_array($value) ? collect($value)->first() : $value }}</td>
            </tr>
        @endforeach
    </table>
</div>