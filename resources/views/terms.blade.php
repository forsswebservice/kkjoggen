<x-guest-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl mb-2">Villkor</h1>
            Följande villkor gäller vid anmälan till {{ rescue(fn() => $year->name, null, false) ?: 'KK-joggen' }}:
            @include('partials.terms')
        </div>
    </div>
</x-guest-layout>
