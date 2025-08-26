@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-3xl font-bold text-gray-900">{{ $meeting->title }}</h1>
                <div class="flex gap-4">
                    <a href="{{ route('meetings.index') }}" class="text-blue-600 hover:text-blue-800">
                        ← Back to Meetings
                    </a>
                    <a href="{{ route('meetings.edit', $meeting) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Edit Meeting
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Meeting Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Meeting Details</h2>
                    
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-600">{{ $meeting->description ?: 'No description provided.' }}</p>
                        </div>

                        <!-- Date and Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Date</h3>
                                <p class="text-gray-600">{{ $meeting->meeting_date->format('l, F j, Y') }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Time</h3>
                                <p class="text-gray-600">{{ $meeting->getFormattedTime() }}</p>
                            </div>
                        </div>

                        <!-- Duration and Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Duration</h3>
                                <p class="text-gray-600">{{ $meeting->getDuration() }} minutes</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Type</h3>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($meeting->type === 'daily_standup') bg-blue-100 text-blue-800
                                    @elseif($meeting->type === 'weekly') bg-green-100 text-green-800
                                    @elseif($meeting->type === 'monthly') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $meeting->type)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Status</h3>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($meeting->status === 'scheduled') bg-yellow-100 text-yellow-800
                                @elseif($meeting->status === 'ongoing') bg-green-100 text-green-800
                                @elseif($meeting->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($meeting->status) }}
                            </span>
                        </div>

                        <!-- Recurring Information -->
                        @if($meeting->is_recurring)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Recurring Schedule</h3>
                                <p class="text-gray-600">
                                    @if($meeting->recurring_days)
                                        Every 
                                        @php
                                            $days = [
                                                1 => 'Monday',
                                                2 => 'Tuesday',
                                                3 => 'Wednesday',
                                                4 => 'Thursday',
                                                5 => 'Friday',
                                                6 => 'Saturday',
                                                7 => 'Sunday'
                                            ];
                                            $selectedDays = collect($meeting->recurring_days)
                                                ->map(function($day) use ($days) {
                                                    return $days[$day] ?? '';
                                                })
                                                ->filter()
                                                ->implode(', ');
                                        @endphp
                                        {{ $selectedDays }}
                                    @else
                                        Recurring meeting
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Meeting Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Meeting Actions</h2>
                    
                    <div class="space-y-4">
                        <!-- Join Meeting -->
                        @if($meeting->status === 'scheduled' || $meeting->status === 'ongoing')
                            <div class="text-center">
                                @if($meeting->isOngoing())
                                    <a href="{{ route('meetings.join', $meeting) }}" 
                                       class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium block">
                                        Join Meeting Now
                                    </a>
                                    <p class="text-sm text-green-600 mt-2">Meeting is currently ongoing</p>
                                @else
                                    <a href="{{ route('meetings.join', $meeting) }}" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium block">
                                        Join Meeting
                                    </a>
                                    <p class="text-sm text-gray-600 mt-2">Meeting starts at {{ $meeting->start_time }}</p>
                                @endif
                            </div>
                        @endif

                        <!-- Meeting Link -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Meeting Link</h3>
                            <div class="space-y-2">
                                <input type="text" value="{{ $meeting->meeting_link }}" 
                                       class="w-full p-2 border border-gray-300 rounded text-sm" readonly>
                                <button onclick="copyToClipboard('{{ $meeting->meeting_link }}')" 
                                        class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                                    Copy Link
                                </button>
                            </div>
                        </div>

                        <!-- Status Update -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Update Status</h3>
                            <form action="{{ route('meetings.update-status', $meeting) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="w-full p-2 border border-gray-300 rounded mb-2">
                                    <option value="scheduled" {{ $meeting->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="ongoing" {{ $meeting->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ $meeting->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $meeting->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <!-- Delete Meeting -->
                        <div class="border-t pt-4">
                            <form action="{{ route('meetings.destroy', $meeting) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this meeting?')"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                    Delete Meeting
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Meeting link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection 