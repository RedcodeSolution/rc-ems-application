@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Meeting Dashboard</h1>
        
        <!-- Quick Actions -->
        <div class="flex gap-4 mb-6">
            <a href="{{ route('meetings.generate-today') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Generate Today's Meeting
            </a>
            <a href="{{ route('meetings.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Create New Meeting
            </a>
            <a href="{{ route('meetings.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                View All Meetings
            </a>
        </div>
    </div>

    <!-- Today's Meeting -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Today's Stand-up Meeting</h2>
        
        @if($todayMeeting)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-semibold text-blue-900">{{ $todayMeeting->title }}</h3>
                        <p class="text-gray-600 mt-2">{{ $todayMeeting->description }}</p>
                        <div class="mt-4 space-y-2">
                            <p><strong>Date:</strong> {{ $todayMeeting->meeting_date->format('l, F j, Y') }}</p>
                            <p><strong>Time:</strong> {{ $todayMeeting->getFormattedTime() }}</p>
                            <p><strong>Duration:</strong> {{ $todayMeeting->getDuration() }} minutes</p>
                            <p><strong>Status:</strong> 
                                <span class="px-2 py-1 rounded text-sm font-medium
                                    @if($todayMeeting->status === 'scheduled') bg-yellow-100 text-yellow-800
                                    @elseif($todayMeeting->status === 'ongoing') bg-green-100 text-green-800
                                    @elseif($todayMeeting->status === 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($todayMeeting->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($todayMeeting->isOngoing())
                            <a href="{{ route('meetings.join', $todayMeeting) }}" 
                               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                                Join Meeting
                            </a>
                        @else
                            <a href="{{ route('meetings.join', $todayMeeting) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                                Join Meeting
                            </a>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Meeting Link:</h4>
                    <div class="flex items-center gap-2">
                        <input type="text" value="{{ $todayMeeting->meeting_link }}" 
                               class="flex-1 p-2 border border-gray-300 rounded" readonly>
                        <button onclick="copyToClipboard('{{ $todayMeeting->meeting_link }}')" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <p class="text-yellow-800">No meeting scheduled for today.</p>
                <a href="{{ route('meetings.generate-today') }}" 
                   class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                    Generate Today's Meeting
                </a>
            </div>
        @endif
    </div>

    <!-- Upcoming Meetings -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Upcoming Meetings</h2>
        
        @if($upcomingMeetings->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingMeetings as $meeting)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $meeting->title }}</h3>
                                <p class="text-gray-600 text-sm">{{ $meeting->description }}</p>
                                <div class="mt-2 text-sm text-gray-500">
                                    <span>{{ $meeting->meeting_date->format('l, F j, Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $meeting->getFormattedTime() }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('meetings.show', $meeting) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                <a href="{{ route('meetings.edit', $meeting) }}" 
                                   class="text-green-600 hover:text-green-800 text-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No upcoming meetings scheduled.</p>
        @endif
    </div>

    <!-- Recent Meetings -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Recent Meetings</h2>
        
        @if($recentMeetings->count() > 0)
            <div class="space-y-4">
                @foreach($recentMeetings as $meeting)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $meeting->title }}</h3>
                                <p class="text-gray-600 text-sm">{{ $meeting->description }}</p>
                                <div class="mt-2 text-sm text-gray-500">
                                    <span>{{ $meeting->meeting_date->format('l, F j, Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $meeting->getFormattedTime() }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($meeting->status === 'completed') bg-green-100 text-green-800
                                        @elseif($meeting->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('meetings.show', $meeting) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No recent meetings found.</p>
        @endif
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