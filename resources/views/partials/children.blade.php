<h2 class="text-xl sm:col-span-6 font-bold bg-green-600 -mx-14 px-14 py-2 text-white border-b-2 border-b-green-800 mb-5">Löpare</h2>
@foreach($competitor->children as $child)
    <div class="flex justify-between">
        <div>{{ $child->firstname }} {{ $child->lastname }} - {{ $child->competitionClass->name }}</div>
        <div class="font-bold">{{ number_format($child->price, 2, ',', ' ') }} kr</div>
    </div>
@endforeach
<div class="border-t mt-2 pt-2">
    <div class="flex justify-between">
        <div class="font-bold">Att betala</div>
        <div class="font-bold">{{ number_format($competitor->price, 2, ',', ' ') }} kr</div>
    </div>
    <div class="flex justify-between">
        <div class="font-bold">Varav moms</div>
        <div class="font-bold">{{ number_format($competitor->price - $competitor->price * 0.8, 2, ',', ' ') }} kr</div>
    </div>
</div>
