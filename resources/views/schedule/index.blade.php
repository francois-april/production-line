@extends('layouts.app')

<link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />

@section('title')
Schedule
@endsection

@section('content')
<x-bladewind::timeline-group
    stacked="true"
    position="right"
    anchor="big">
    @foreach ($schedule as $scheduleItem)
        @if ($scheduleItem['changeover'])
            <x-bladewind::timeline
                date="Start: {{ $scheduleItem['startTime']->format('Y-m-d H:i') }}" 
                align_left="true">
                <x-slot:content>
                    Changeover
                    <br>
                    Estimated changeover time: {{ $scheduleItem['productionTime']->forHumans() }}
                </x-slot:content>
            </x-bladewind::timeline>
        @else
            <x-bladewind::timeline
                date="Start: {{ $scheduleItem['startTime']->format('Y-m-d H:i') }}" 
                align_left="true">
                <x-slot:content>
                    <h2>Order #{{ $scheduleItem['order']->id }}</h2>
                    <h4>Client: {{ $scheduleItem['order']->client->name }}</h4>
                    <h4>Product type: {{ $scheduleItem['order']->productType->name }}</h4>
                    Estimated order production time: {{ $scheduleItem['productionTime']->forHumans() }}
                </x-slot:content>
            </x-bladewind::timeline>
            @foreach ($scheduleItem['items'] as $orderItem)
            <x-bladewind::timeline
                date="Start: {{ $orderItem['startTime']->format('Y-m-d H:i') }}">
                <x-slot:content>
                    <h5>Product: {{ $orderItem['item']->product->name }}</h5>
                    Estimated item production time: {{ $orderItem['productionTime']->forHumans() }}
                </x-slot:content>
            </x-bladewind::timeline>
            @endforeach
        @endif
        <br>
    @endforeach
</x-bladewind::timeline-group>
@endsection