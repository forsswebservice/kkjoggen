<h2 class="text-xl sm:col-span-6 font-bold bg-green-600 -mx-14 px-14 py-2 text-white border-b-2 border-b-green-800 mb-5">Löpare</h2>
@foreach($competitor->children as $child)
    <div class="flex justify-between">
        <div>{{ $child->firstname }} {{ $child->lastname }} - {{ $child->competitionClass->name }}</div>
        @if($child->rebate)
            <div class="font-bold"><span class="line-through text-gray-500">{{ number_format($child->price + $child->rebate, 2, ',', ' ') }} kr</span> {{ number_format($child->price, 2, ',', ' ') }}kr</div>
        @else
            <div class="font-bold">{{ number_format($child->price, 2, ',', ' ') }} kr</div>
        @endif
    </div>
@endforeach
<div class="border-t mt-2 pt-2">
    @if($competitor->rebate)
        <div class="flex justify-between">
            <div class="font-bold">Rabatt</div>
            <div class="font-bold">{{ number_format($competitor->rebate, 2, ',', ' ') }} kr</div>
        </div>
    @endif
    <div class="flex justify-between">
        <div class="font-bold">Att betala</div>
        <div class="font-bold">{{ number_format($competitor->price, 2, ',', ' ') }} kr</div>
    </div>
    <div class="flex justify-between">
        <div class="font-bold">Varav moms</div>
        <div class="font-bold">{{ number_format($competitor->price - $competitor->price * 0.8, 2, ',', ' ') }} kr</div>
    </div>
</div>
