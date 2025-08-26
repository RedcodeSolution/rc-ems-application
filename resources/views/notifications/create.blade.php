@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Notification</h1>
    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="notifi_id" class="form-label">Notification ID</label>
            <input type="text" class="form-control" id="notifi_id" name="notifi_id" required>
        </div>
        <div class="mb-3">
            <label for="timestamp" class="form-label">Timestamp</label>
            <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" id="type" name="type" required>
        </div>
        <div class="mb-3">
            <label for="delivery_channel" class="form-label">Delivery Channel</label>
            <input type="text" class="form-control" id="delivery_channel" name="delivery_channel" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
