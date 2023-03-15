@extends('layouts.app')

@section('content')

<div class="container">
    <div class="panel-body">
        <form action="{{ url('/') }}" method="GET" class="form-inline">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" name="url" placeholder="Enter the link here">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">
                    Shorten URL
                </button>
            </div>
        </form>
    </div>

    <div class="panel-body">
    <p><a href="{{$shortURL}}" target="_blank">Short URL</a></p> 
    </div>
</div>

@endsection