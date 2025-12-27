@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!--admin dashboard-->
    @if(auth()->user()->is_team)
    @include('pages.everything.team.wrapper')
    @endif

    @if(auth()->user()->is_client)
    @endif



</div>
<!--main content -->
@endsection