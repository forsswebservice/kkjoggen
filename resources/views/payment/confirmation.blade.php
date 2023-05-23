<x-guest-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @csrf
        <div class="text-gray-900">
            <h1 class="text-3xl mb-5">Bekräftelse registrering {{ $competitor->competitionYear->name }}</h1>
            @include('partials.payer', ['competitor' => $competitor])
            @include('partials.children', ['competitor' => $competitor])
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="javascript:window.print()" class="rounded-md bg-green-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600  border-b-2 border-b-green-800">Skriv ut</a>
        </div>
    </div>
</x-guest-layout>
