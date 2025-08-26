@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Meetings</h1>
            <div class="flex gap-4">
                <a href="{{ route('meetings.dashboard') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Dashboard
                </a>
                <a href="{{ route('meetings.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Create Meeting
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('meetings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search meetings..." 
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="">All Types</option>
                        <option value="daily_standup" {{ request('type') === 'daily_standup' ? 'selected' : '' }}>Daily Stand-up</option>
                        <option value="weekly" {{ request('type') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="special" {{ request('type') === 'special' ? 'selected' : '' }}>Special</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Filter
                    </button>
                    <a href="{{ route('meetings.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Meetings List -->
    <div class="bg-white rounded-lg shadow-md">
        @if($meetings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Meeting
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($meetings as $meeting)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $meeting->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $meeting->description }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $meeting->meeting_date->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $meeting->getFormattedTime() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($meeting->type === 'daily_standup') bg-blue-100 text-blue-800
                                        @elseif($meeting->type === 'weekly') bg-green-100 text-green-800
                                        @elseif($meeting->type === 'monthly') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $meeting->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($meeting->status === 'scheduled') bg-yellow-100 text-yellow-800
                                        @elseif($meeting->status === 'ongoing') bg-green-100 text-green-800
                                        @elseif($meeting->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('meetings.show', $meeting) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                                        <a href="{{ route('meetings.edit', $meeting) }}" 
                                           class="text-green-600 hover:text-green-900 text-sm">Edit</a>
                                        @if($meeting->status === 'scheduled' || $meeting->status === 'ongoing')
                                            <a href="{{ route('meetings.join', $meeting) }}" 
                                               class="text-purple-600 hover:text-purple-900 text-sm">Join</a>
                                        @endif
                                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this meeting?')"
                                                    class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $meetings->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <p class="text-gray-500 text-lg">No meetings found.</p>
                <a href="{{ route('meetings.create') }}" 
                   class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Create Your First Meeting
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 