@extends('layouts.livewire')

@section('main')
<div class="container">

    <div class="row mt-5">
        <div class="col-md-6">
            @livewire('ticket')
        </div>
        <div class="col-md-6">
            @livewire('comments')
        </div>
    </div>
    
   
   
</div>
@endsection