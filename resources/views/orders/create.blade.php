@php
    use App\Http\Controllers\OrderController;
@endphp

@extends('layouts.app')

@section('title')
Create Order
@endsection

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Create order</h1>

        <div class="container mt-4">
            <form method="POST" action="{{ action([OrderController::class, 'store']) }}">
                @csrf
                <label for="client-select" class="form-label">Client</label>
                <select class="form-select client-select" name="client">
                    <option hidden selected>Choose a client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                @error('client')
                    <div class="text-danger small">
                        {{ $message }}
                    </div>
                @enderror
                <br>
                <div class="form-group">
                    <label for="date-input" class="form-label">Due date</label>
                    <input type="datetime-local" class="form-control date-input" name="needByDate" style="width:auto" />
                    @error('needByDate')
                        <div class="text-danger small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <br>
                @livewire('order-item-form')
                <br>
                <button type="submit" class="btn btn-primary">Submit order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-default">Back</a>
                @error('items.*')
                    <div class="text-danger small">
                        {{ $message }}
                    </div>
                @enderror
            </form>
        </div>

    </div>
    @if(session('message'))
        <h6 class="alert alert-success">
            Order saved successfully
        </h6>
    @endif
    @error('*')
        <h6 class="alert alert-danger">
            Order did not save
        </h6>
    @enderror
@endsection