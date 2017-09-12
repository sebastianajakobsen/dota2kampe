<div>
    <label>Navn
        @if ($errors->has('navn'))
            <span class="span-error">
                    :{{ $errors->first('navn') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="navn" @if(isset($team->name)) value="{{$team->name }}"
           @else value="{{ old('navn') }}" @endif>
</div>

<div>
    <label>Title
        @if ($errors->has('title'))
            <span class="span-error">
                    :{{ $errors->first('title') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="title" @if(isset($team->title)) value="{{$team->title }}"
           @else value="{{ old('title') }}" @endif>

</div>

<div>
    <label>Tag
        @if ($errors->has('tag'))
            <span class="span-error">
                    :{{ $errors->first('tag') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="tag" @if(isset($team->tag)) value="{{$team->tag }}"
           @else value="{{ old('tag') }}" @endif>
</div>
<div>
    <label>Wiki
        @if ($errors->has('wiki'))
            <span class="span-error">
                    :{{ $errors->first('wiki') }}
                                    </span>
        @endif
    </label>
    <input autocomplete="off" type="text" name="wiki" @if(isset($team->wiki)) value="{{$team->wiki }}"
           @else value="{{ old('wiki') }}" @endif>
</div>

<div>
    <select size="10" multiple name="players[]">
        @if(isset($team)) {{--  if edit page  --}}
        <option value="0">None</option>
        @foreach($players as $player)
            <option
                    @if($player->team_id == $team->id) selected @endif
            value="{{$player->name}}"> {{$player->name}}
            </option>
        @endforeach
        @else {{--  Show all players on create page --}}

        <option value="">None</option>
        @foreach($players as $player)
            <option
                    value="{{$player->name}}"> {{$player->name}}
            </option>
        @endforeach

        @endif
    </select>

</div>

<div>
    <label>Land
        @if ($errors->has('land'))
            <span class="span-error">
                    :{{ $errors->first('land') }}
                </span>
        @endif
    </label>
    <select name="land">
        @if(isset($team)) {{--  if edit page  --}}
        <option value="0">None</option>
        @foreach($countries as $country)
            <option
                    @if($country->id == $team->country_id) selected @endif
            value="{{$country->id}}"> {{$country->nicename}}
            </option>
        @endforeach
        @else {{--  Show all on create page --}}

        <option value="">None</option>
        @foreach($countries as $country)
            <option
                    value="{{$country->id}}"> {{$country->nicename}}
            </option>
        @endforeach

        @endif
    </select>
</div>
<div>
    <label>Logo - 120x50 størrelse
        @if ($errors->has('logo'))
            <span class="span-error">
                                        :{{ $errors->first('logo') }}
                                    </span>
        @endif
    </label>

    @if(isset($team->logo))
        <img src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$team->logo)}}">
    @endif
    <input type="file" name="logo">


</div>
<div>
    <button type="submit">
        {{$submitButton}}
    </button>

</div>