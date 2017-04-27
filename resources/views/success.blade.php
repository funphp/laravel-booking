@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Your Booking Confimred</div>

                    <div class="panel-body">
                       <p>Your booking has been confirmed for date:{{$booking->date}} {{$booking->time}}<br>
                       assigend cleaner: {{$cleaner->first_name}} {{$cleaner->last_name}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
