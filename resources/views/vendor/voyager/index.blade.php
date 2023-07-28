@extends('voyager::master')

@section('css')
    <style>
        .alert-warning{
            color: #856404  !important;
            background-color: #fff3cd  !important;
            border-color: #ffeeba  !important;
            padding-top: 6px;
            padding-bottom: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        <div class="row" style="padding-left:40px; ">
           <div class="text-dark "> 
                <h5>System State: <strong style="color: darkgreen">{{ setting('site.system_state') ? "ON": ""; }}</strong></h5> 
            </div>

            @if(!setting('site.system_state'))

                <div class="alert alert-warning" role="alert">
                   <strong>SYSTEM SUSPENDED, ONLY SUPER ADMIN CAN LOGIN.</strong>
                </div>  

            @endif
            
        </div>
        @include('voyager::dimmers')
        
    </div>
@stop


