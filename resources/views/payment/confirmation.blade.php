<x-guest-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @csrf
        <div class="text-gray-900">
            <h1 class="text-3xl mb-5">Kvitto registrering {{ $competitor->competitionYear->name }}</h1>
            <p>Din bokning är nu betalt och du är anmäld till {{ $competitor->competitionYear->name }}.</p>
            @if($competitor->email)
                <p class="mb-5">Ett bekräftelsemail har skickats till {{ $competitor->email }}.</p>
            @endif
            <div class="mb-5">
                <p>Din anmälan har referensnummer: <b>{{ $competitor->id }}</b></p>
                @if($reference_number = rescue(fn() => array_slice(explode('/', $competitor->getPaymentData('current-payment-paid')['paymentOrder']['id']), -1)[0], null, false))
                    <p>Din betalning har referensnummer: <b>{{ $reference_number }}</b></p>
                @endif
            </div>
            <div class="mb-5">
                <p>Om du har frågor angående din anmälan eller betalning så kan du kontakta oss på <a href="mailto:kkjoggen@gmail.com" class="text-green-500 underline">kkjoggen@gmail.com</a>.</p>
            </div>
            @include('partials.payer', ['competitor' => $competitor])
            @include('partials.children', ['competitor' => $competitor])
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="javascript:window.print()" class="rounded-md bg-green-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600  border-b-2 border-b-green-800">Skriv ut</a>
        </div>
    </div>
</x-guest-layout>
