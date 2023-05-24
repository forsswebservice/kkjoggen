<x-guest-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 text-gray-900">
            Din anmälan är redan betald.
            <a href="{{ $competitor->confirmationURL() }}" class="rounded-md bg-green-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600  border-b-2 border-b-green-800">Gå till bokningsbekräftelse</a>
        </div>
    </div>
</x-guest-layout>
