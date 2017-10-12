@extends('layouts.app')

@section('content')

    @foreach($matches as $match)
        <img height="40"
             src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$match->team1->logo)}}">{{$match->team1->name}} vs {{$match->team2->name}}
        <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$match->team2->logo)}}">
        <span data-countdown="{{$match->start_time}}" class="match-when" title="{{$match->start_time}}">
    </span>
        <br/>
    @endforeach
    <br/><br/>
    @foreach($tournaments as $tournament)
        <div>
            {{$tournament->tier}} <img height="20"
                                       src="{{asset(env('STORAGE_DISK_PATH')."/tournaments/".$tournament->logo)}}"> {{$tournament->name}}
        </div>
    @endforeach
    <br/> <br/>
    @foreach($teams as $team)
        <div>
            <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$team->logo)}}">
            {{$team->name}}
            <ul>
                @foreach($team->players as $player)
                    <li>
                        <img height="25"
                             src="{{asset(env('STORAGE_DISK_PATH')."/players/".$player->image)}}">{{$player->name}}
                        [ {{$player->solo_mmr}} ]
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

@endsection

@section('scripts')
    <script src="https://static.vgbuff.com/js/jstz.main.js"></script>
    <script src="https://static.vgbuff.com/js/moment.js"></script>
    <script src="https://static.vgbuff.com/js/moment-timezone-with-data.js"></script>
    {{-- match timer countdown --}}
    <script src="https://static.vgbuff.com/js/jquery.countdown.min.js"></script>


    <script>
        var timezone = jstz.determine();
        $(".match-when").each(function () {
            var start = $(this).attr("title");
            var localMoment = moment.tz(start, 'Europe/Copenhagen');
            var clientMoment = moment.tz(start, timezone.name()).format('ZZ');
            var zoneAbbr = (moment.tz(start, timezone.name()).zoneAbbr());
            var clientDate = localMoment.utcOffset(clientMoment).format("DD-MM-YYYY HH:mm");
            var clientTime = localMoment.utcOffset(clientMoment).format("HH:mm");
            $(this).attr("title", clientDate + ' ' + zoneAbbr);

            $('[data-countdown]').each(function () {
                var $this = $(this), finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function (event) {
                    {{-- if countdown done. then hide countdown timer--}}
                    {{-- check if there is 1 day left --}}


                    if (parseInt(event.strftime('%D')) == 1) {
                        $(this).html(
                                event.strftime('%-D day %-H hour %-M min %-S sec')
                        );
                    }
                    {{-- check if there is any days left. if none dont display days --}}
                    if (parseInt(event.strftime('%D')) == 0) {
                        $(this).html(
                                event.strftime('%-H hour %-M min %-S sec')
                        );
                        {{-- check if there is 1 hour left. if there is then display days --}}
                        if (parseInt(event.strftime('%H')) == 1) {
                            $(this).html(
                                    event.strftime('%-H hour %-M min %-S sec')
                            );
                        }
                        {{-- check if there is any hours left. if none dont display hours --}}
                        if (parseInt(event.strftime('%H')) == 0) {
                            $(this).html(
                                    event.strftime('%-M min %-S sec')
                            );
                            {{-- check if there is any minutes left. if none dont display minutes --}}
                            if (parseInt(event.strftime('%M')) == 0) {
                                $(this).html(
                                        event.strftime('%-S sec')
                                );

                            }
                        }

                        {{-- if there is days then display with days --}}
                    } else {
                        $(this).html(
                                event.strftime('%-D day %-H hour %-M min %-S sec')
                        );
                    }
                    if (event.elapsed) {
                        $(this).html('<span class="live-match" style="font-weight: bold;color:#117b11">Live</span>')
                    }



                });
            });

        });


    </script>

    <script type="text/javascript">

    </script>



@endsection