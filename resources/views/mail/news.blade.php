<@component('mail::message')
#{{ $news->header }}

{!! $news->content !!}

@if($news->files->count())
## Denna nyhet har bifogade filer

Klicka på filnamnen för att visa filen. Inloggning krävs.
    @foreach($news->files as $file)
[{{ $file->name }}]({{ route('files.show', $file->id)  }})
    @endforeach

@endif

Med vänlig hälsning,<br>
RZ-gruppen

@component('mail::button', ['url' => route('news.show', $news), 'color' => 'blue'])
    Läs nyheten på intranätet
@endcomponent

@endcomponent
