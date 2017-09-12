<div>
    <label>Navn
        @if ($errors->has('navn'))
            <span class="span-error">
                    :{{ $errors->first('navn') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="navn" @if(isset($tournament->name)) value="{{$tournament->name }}"
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

    <input autocomplete="off" type="text" name="title" @if(isset($tournament->title)) value="{{$tournament->title }}"
           @else value="{{ old('title') }}" @endif>

</div>

<div>
    <label>Tier
        @if ($errors->has('tier'))
            <span class="span-error">
                    :{{ $errors->first('tier') }}
                                    </span>
        @endif
    </label>

    <input autocomplete="off" type="text" name="tier" @if(isset($tournament->tier)) value="{{$tournament->tier }}"
           @else value="{{ old('tier') }}" @endif>
</div>
<div>
    <label>Wiki
        @if ($errors->has('wiki'))
            <span class="span-error">
                    :{{ $errors->first('wiki') }}
                                    </span>
        @endif
    </label>
    <input autocomplete="off" type="text" name="wiki" @if(isset($tournament->wiki)) value="{{$tournament->wiki }}"
           @else value="{{ old('wiki') }}" @endif>
</div>


<div>
    <label>Start Dato
        @if ($errors->has('start_dato'))
            <span class="span-error">:{{ $errors->first('start_dato') }}</span>
        @endif
    </label>

    <input autocomplete="off" type="text" id="startDate"
           @if(isset($tournament->start_date) && ($tournament->start_date !== '0000-00-00')) value="{{$tournament->start_date}}"
           @else value="{{ old('start_dato') }}" @endif name="start_dato" class="form-control"/>
</div>

<div>
    <label>Slut Dato
        @if ($errors->has('slut_dato'))
            <span class="span-error">:{{ $errors->first('slut_dato') }}</span>
        @endif
    </label>

    <input autocomplete="off" type="text" id="endDate"
           @if(isset($tournament->end_date) && ($tournament->end_date !== '0000-00-00')) value="{{$tournament->end_date}}"
           @else value="{{ old('start_dato') }}" @endif name="slut_dato" class="form-control"/>
</div>

<div>
    <label>Logo - 50x50 størrelse
        @if ($errors->has('logo'))
            <span class="span-error">
                                        :{{ $errors->first('logo') }}
                                    </span>
        @endif
    </label>

    @if(isset($tournament->logo))
        <img src="{{asset(env('STORAGE_DISK_PATH')."/tournaments/".$tournament->logo)}}">
    @endif
    <input type="file" name="logo">


</div>
<div>
    <button type="submit">
        {{$submitButton}}
    </button>

</div>

@section('scripts')
    <script src="{{asset('js/jquery.datetimepicker.full.min.js')}}"></script>
    <script>
        jQuery('#startDate').datetimepicker({
            format: 'Y-m-d'
        });

        jQuery('#endDate').datetimepicker({
            format: 'Y-m-d'
        });

    </script>

@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.min.css')}}">
@endsection