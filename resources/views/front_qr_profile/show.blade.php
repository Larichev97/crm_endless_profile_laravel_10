@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@if(Agent::isTablet())
    <!-- Tablet content -->
    @include('front_qr_profile.tablet.content')
@elseif(Agent::isMobile())
    <!-- Mobile content -->
    @include('front_qr_profile.mobile.content')
@else
    <!-- Desktop content -->
    @include('front_qr_profile.desktop.content')
@endif
