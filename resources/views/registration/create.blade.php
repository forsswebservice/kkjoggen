<x-guest-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($errors->any())
            <div class="alert alert-danger  ">Du har ett eller flera fält som du missat att fylla i eller som innehåller fel. Vänligen kontrollera din anmälan.</div>
        @endif
        <form method="post" action="/">
            @csrf
            <div class="text-gray-900">
                <h1 class="text-3xl mb-5">Anmälan till {{ $year->name }}</h1>
                @if($year->closes_at)
                    <p>Anmälan via webben kan ske <b>senast {{ $year->closes_at }}</b>.</p>
                @endif

                @if($year->late_at)
                    <p>Sen anmälan <b>från och med {{ $year->closes_at }}</b>.</p>
                @endif
            </div>
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                <h2 class="text-xl sm:col-span-6 font-bold bg-green-600 -mx-14 px-14 py-2 text-white border-b-2 border-b-green-800">Betalare</h2>
                <div class="sm:col-span-3">
                    <label for="firstname" class="block text-sm font-medium leading-6 text-gray-900">Förnamn<span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="firstname" id="firstname" value="{{ old('firstname') }}" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('firstname')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="lastname" class="block text-sm font-medium leading-6 text-gray-900">Efternamn<span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('lastname')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="addres" class="block text-sm font-medium leading-6 text-gray-900">Gatuadress<span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="address" id="address" value="{{ old('address') }}" autocomplete="address" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('address')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-1">
                    <label for="zip_code" class="block text-sm font-medium leading-6 text-gray-900">Postnummer<span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('zip_code')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-5">
                    <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Ort<span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="city" id="city" value="{{ old('city') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('city')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Telefonnummer<span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}" autocomplete="phone" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('phone')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">E-postadress</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        @error('email')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="num_competitors" class="block text-sm font-medium leading-6 text-gray-900">Antal löpare</label>
                    <div class="mt-2">
                        <select name="num_competitors" id="num_competitors" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" onchange="changeRunners(event)">
                            @for($i = 1; $i <= $year->max_registration; $i++)
                                <option value="{{ $i }}" {{ $i == old("num_competitors") ? 'selected' : ''}}>{{ $i}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            @for($i = 1; $i <= $year->max_registration; $i++)
                <div id="competitor{{ $i }}" class="mt-10 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6 {{ $i > max(1, (int) old("num_competitors")) ? 'hidden' : '' }}">
                    <h2 class="text-xl sm:col-span-6 font-bold bg-green-600 -mx-14 px-14 py-2 text-white border-b-2 border-b-green-800">Löpare #{{ $i }}</h2>
                    <div class="sm:col-span-3">
                        <label for="firstname{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Förnamn<span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="firstname{{ $i }}" id="firstname{{ $i }}" value="{{ old("firstname{$i}") }}" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                            @error("firstname{$i}")
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="lastname" class="block text-sm font-medium leading-6 text-gray-900">Efternamn<span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="lastname{{ $i }}" id="lastname{{ $i }}" value="{{ old("lastname{$i}") }}" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                            @error("lastname{$i}")
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="born{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Födelseår<span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="born{{ $i }}" id="born{{ $i }}" value="{{ old("born{$i}") }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                            @error("born{$i}")
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="team{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Företag/förening/hemort<span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="team{{ $i }}" id="team{{ $i }}" value="{{ old("team{$i}") }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                            @error("team{$i}")
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="competition_class_id{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Löparklass<span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <select name="competition_class_id{{ $i }}" id="competition_class_id{{ $i }}" onchange="document.getElementById('is_local{{ $i }}').disabled = ![{{ $free_when_local_classes->map(fn($c) => $c->id)->join(',') }}].includes(parseInt(this.value));" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                @foreach($year->competitionClasses as $competition_class)
                                    <option value="{{ $competition_class->id }}" {{ $competition_class->id == old("competition_class_id{$i}") ? 'selected' : ''}}>{{ $competition_class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="competition_class_id{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Tröjstorlek</label>
                        <div class="mt-2">
                            <select name="shirt_size{{ $i }}" id="shirt_size{{ $i }}" value="{{ old("shirt_size{$i}") }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                @foreach(config('competition.shirt_sizes') as $shirt_size)
                                    <option value="{{ $shirt_size }}" {{ $shirt_size == old("shirt_size{$i}") ? 'selected' : ''}}>{{ $shirt_size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="shirt_name{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Namntryck</label>
                        <div class="mt-2">
                            <input type="text" name="shirt_name{{ $i }}" id="shirt_name{{ $i }}" value="{{ old("shirt_name{$i}") }}" maxlength="10" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                            <p class="text-sm mt-1 text-gray-500">Max 10 tecken. 50kr betalas på plats vid uthämtning av tröjan och inte vid anmälan till tävlingen.</p>
                        </div>
                    </div>
                    <!--
                    <div class="sm:col-span-6">
                        <label for="previous_starts{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Antal tidigare starter</label>
                        <div class="mt-2">
                            <input type="text" name="previous_starts{{ $i }}" id="previous_starts{{ $i }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    -->
                    <div class="sm:col-span-6">
                        <label for="time_10k{{ $i }}" class="block text-sm font-medium leading-6 text-gray-900">Miltid för seeding</label>
                        <div class="mt-2">
                            <input type="text" name="time_10k{{ $i }}" id="time_10k{{ $i }}" value="{{ old("time_10k{$i}") }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                            <p class="text-sm mt-1 text-gray-500">Avser endast kvartsmarathon.</p>
                        </div>
                    </div>

                    @if($free_when_local_classes->count() > 0)
                        <div class="sm:col-span-6">
                            <div class="mt-6 space-y-6">
                                <div class="relative flex gap-x-3">
                                    <div class="flex h-6 items-center">
                                        <input id="is_local{{ $i }}" name="is_local{{ $i }}" type="checkbox" value="1" {{ old("is_local{$i}") ? 'checked' : '' }} {{ $year->competitionClasses->first()->is_free_when_local ? '' : 'disabled' }} class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600">
                                    </div>
                                    <div class="text-sm leading-6">
                                        <label for="is_local{{ $i }}" class="font-medium text-gray-900">Bosatt i Katrineholms kommun</label>
                                        <p class="text-gray-500">Löpare <span class="underline">bosatta i Katrineholms Kommun</span> i löpklass {{ $free_when_local_classes->map(fn($c) => $c->name)->join(', ', ' och ') }} får sin avgift betald av Katrineholms Kommun.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endfor

            <div class="sm:col-span-6">
                <div class="mt-6 space-y-6">
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <input id="accepts" name="accepts" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="accepts{{ $i }}" class="font-medium text-gray-900">Jag godkänner villkoren för anmälan<span class="text-red-500">*</span></label>
                            <p class="text-gray-500">Genom att acceptera villkoren vid anmälan och betalning till {{ $year->name }} förbinder du dig att acceptera följande:</p>
                            <ul class="text-gray-500 my-2 list-disc pl-4">
                                <li>Att {{ $year->name }} får använda dina uppgifter i vårt adressregister.</li>
                                <li>Att ditt namn kommer finnas med i anmälnings- och resultatregistret och kan publiceras på Internet.</li>
                                <li>Att {{ $year->name }} kan publicera bild och video från loppet där du kan komma att vara med.</li>
                            </ul>
                            <p class="text-gray-500 mt-2">För att få tillbaka startavgiften kan du teckna en avbeställningsförsäkring på <a href="http://www.startklar.nu" target="_blank" class="text-green-500 underline">http://www.startklar.nu</a>. Den innehåller även en olycksfallsförsäkring.</p>
                            @error("accepts")
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" class="rounded-md bg-green-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600  border-b-2 border-b-green-800">Skicka anmälan</button>
            </div>
        </form>

        <script>
            function changeRunners(e) {
                for(var i = 1; i <= {{ $year->max_registration }}; i++) {
                    if(i <= e.target.value)
                        document.getElementById(`competitor${i}`).classList.remove('hidden');
                    else
                        document.getElementById(`competitor${i}`).classList.add('hidden');
                }
            }
        </script>
    </div>
</x-guest-layout>
