<?php

$url = "/slot/{$slot->id}/take";

$taken = false;
$self = false;
$other = false;

if(!empty($slot->user))
{
    $taken = true;

    if(Auth::check() && Auth::user()->id == $slot->user->id)
    {
        $self = true;
        $url = "/slot/{$slot->id}/release";
    }
    else
    {
        $other = true;
    }
}

?>

@extends('app')

@section('content')

    <h1>
        @if($taken)
            @if($self)
                Your Volunteer Shift for: 
            @else
                Occupied Volunteer Shift for: 
            @endif
        @else
            Available for Volunteering:
        @endif

        {{ $slot->department->name }} - {{ $slot->schedule->shift->name }}
    </h1>

    <hr>

    {!! Form::open(['url' => $url]) !!}
        <div>
            <label>Start Date</label>
            {{ $slot->start_date }}
        </div>

        <div>
            <label>Start Time</label>
            {{ $slot->start_time }}
        </div>

        <div>
            <label>End Time</label>
            {{ $slot->end_time }}
        </div>

        @if($slot->department->description)
            <label>About {{ $slot->department->name }}</label>
            <p>{!! nl2br(e($slot->department->description)) !!}</p>
        @endif

        @if($slot->schedule->shift->description)
            <label>About {{ $slot->schedule->shift->name }}</label>
            <p>{!! nl2br(e($slot->schedule->shift->description)) !!}</p>
        @endif

        @if($taken)
            <hr>

            @if($self)
                <p>
                    Are you sure you want to cancel volunteering for this shift?
                    By canceling, your slot will be available for other people to take.
                </p>

                <button type="submit" class="btn btn-danger">Release Shift</button>
            @else
                <p>
                    This slot has been taken by somebody. Go back to find a new one!
                </p>
            @endif
        @else
            {{-- If nobody has taken this slot, display more information and the take button --}}

            @if($slot->schedule->password)
                <div>
                    @include('partials/form/text', ['name' => 'password', 'label' => 'This shift requires a password', 'help' => "This shift has been reserved. You must recieve a password from the department lead in order to take this shift."])
                </div>
            @else            
                <div>
                    <label>Allowed User Groups</label>
                    <ul>
                        @foreach($slot->schedule->getRoles() as $scheduleRole)
                            <li>{{ ucwords($scheduleRole->role->name) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <hr>

            <p>
                Are you sure you want to volunteer for this shift?
                By registering, you will be expected to perform the duties of this shift for the entire time listed.
                Please arrive at least 15 minutes ahead of time to be briefed by the previous shift team and answer any questions you have.
            </p>

            <button type="submit" class="btn btn-success">Take Shift</button>
        @endif

        <a href="/event/{{ $slot->event->id }}" class="btn btn-primary">Cancel</a>
    {!! Form::close() !!}
@endsection