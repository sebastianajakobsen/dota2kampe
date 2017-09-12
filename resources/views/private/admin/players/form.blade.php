<div>
    <label>Rigtig Navn
        @if ($errors->has('navn'))
            <span class="span-error">
                    :{{ $errors->first('navn') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="navn" @if(isset($player->realname)) value="{{$player->realname }}"
           @else value="{{ old('navn') }}" @endif>
</div>

<div>
    <label>Gamer Navn
        @if ($errors->has('gamernavn'))
            <span class="span-error">
                    :{{ $errors->first('gamernavn') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="gamernavn" @if(isset($player->name)) value="{{$player->name }}"
           @else value="{{ old('gamernavn') }}" @endif>

</div>

<div>
    <label>Solo MMR
        @if ($errors->has('solo_mmr'))
            <span class="span-error">
                    :{{ $errors->first('solo_mmr') }}
                                    </span>
        @endif
    </label>
    <input autocomplete="off" type="text" name="solo_mmr" @if(isset($player->solo_mmr)) value="{{$player->solo_mmr }}"
           @else value="{{ old('solo_mmr') }}" @endif>
</div>
<div>
    <label>Image - 250x250 størrelse
        @if ($errors->has('image'))
            <span class="span-error">
                                        :{{ $errors->first('image') }}
                                    </span>
        @endif
    </label>

    @if(isset($player->image))
        <img src="{{asset(env('STORAGE_DISK_PATH')."/players/".$player->image)}}">
    @endif
    <input type="file" name="image">


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
        @if(isset($player)) {{--  if edit page  --}}
        <option value="0">None</option>
        @foreach($countries as $country)
            <option
                    @if($country->id == $player->country_id) selected @endif
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
    <label>Hold
        @if ($errors->has('hold'))
            <span class="span-error">
                    :{{ $errors->first('hold') }}
                </span>
        @endif
    </label>
        <select name="hold">
            @if(isset($player)) {{--  if edit page  --}}
            <option value="0">None</option>
            @foreach($teams as $team)
                <option
                        @if($team->id == $player->team_id) selected @endif
                value="{{$team->id}}"> {{$team->name}}
                </option>
            @endforeach
            @else {{--  Show all on create page --}}

            <option value="">None</option>
            @foreach($teams as $team)
                <option
                        value="{{$team->id}}"> {{$team->name}}
                </option>
            @endforeach

            @endif
        </select>
</div>


<div>
    <button type="submit">
        {{$submitButton}}
    </button>

</div>