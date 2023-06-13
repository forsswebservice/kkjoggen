<h2 class="text-xl sm:col-span-6 font-bold bg-green-600 -mx-14 px-14 py-2 text-white border-b-2 border-b-green-800">Betalare/Anmälare</h2>
<div class="my-5">
    <div>{{ $competitor->firstname }} {{ $competitor->lastname }}</div>
    <div>{{ $competitor->address }}</div>
    <div>{{ $competitor->zip_code }} {{ $competitor->city }}</div>
    <div>{{ $competitor->phone }}</div>
    <div>{{ $competitor->email }}</div>
</div>
