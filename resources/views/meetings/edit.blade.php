@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Edit Meeting</h1>
            <a href="{{ route('meetings.show', $meeting) }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Meeting Details
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('meetings.update', $meeting) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Meeting Title *
                        </label>
                        <input type="text" name="title" id="title" 
                               value="{{ old('title', $meeting->title) }}" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $meeting->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meeting Date -->
                    <div>
                        <label for="meeting_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Meeting Date *
                        </label>
                        <input type="date" name="meeting_date" id="meeting_date" 
                               value="{{ old('meeting_date', $meeting->meeting_date->format('Y-m-d')) }}" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('meeting_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meeting Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Meeting Type *
                        </label>
                        <select name="type" id="type" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Type</option>
                            <option value="daily_standup" {{ old('type', $meeting->type) === 'daily_standup' ? 'selected' : '' }}>
                                Daily Stand-up
                            </option>
                            <option value="weekly" {{ old('type', $meeting->type) === 'weekly' ? 'selected' : '' }}>
                                Weekly Meeting
                            </option>
                            <option value="monthly" {{ old('type', $meeting->type) === 'monthly' ? 'selected' : '' }}>
                                Monthly Meeting
                            </option>
                            <option value="special" {{ old('type', $meeting->type) === 'special' ? 'selected' : '' }}>
                                Special Meeting
                            </option>
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time *
                        </label>
                        <input type="time" name="start_time" id="start_time" 
                               value="{{ old('start_time', $meeting->start_time) }}" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time *
                        </label>
                        <input type="time" name="end_time" id="end_time" 
                               value="{{ old('end_time', $meeting->end_time) }}" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recurring -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_recurring" id="is_recurring" 
                                   value="1" {{ old('is_recurring', $meeting->is_recurring) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_recurring" class="ml-2 block text-sm text-gray-900">
                                This is a recurring meeting
                            </label>
                        </div>
                    </div>

                    <!-- Recurring Days (shown when recurring is checked) -->
                    <div class="md:col-span-2" id="recurring_days_container" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Recurring Days
                        </label>
                        <div class="grid grid-cols-5 gap-2">
                            @php
                                $days = [
                                    1 => 'Monday',
                                    2 => 'Tuesday', 
                                    3 => 'Wednesday',
                                    4 => 'Thursday',
                                    5 => 'Friday'
                                ];
                                $selectedDays = old('recurring_days', $meeting->recurring_days ?? [1,2,3,4,5]);
                            @endphp
                            @foreach($days as $dayNum => $dayName)
                                <div class="flex items-center">
                                    <input type="checkbox" name="recurring_days[]" 
                                           value="{{ $dayNum }}" id="day_{{ $dayNum }}"
                                           {{ in_array($dayNum, $selectedDays) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="day_{{ $dayNum }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $dayName }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('meetings.show', $meeting) }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Update Meeting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recurringCheckbox = document.getElementById('is_recurring');
    const recurringDaysContainer = document.getElementById('recurring_days_container');
    
    function toggleRecurringDays() {
        if (recurringCheckbox.checked) {
            recurringDaysContainer.style.display = 'block';
        } else {
            recurringDaysContainer.style.display = 'none';
        }
    }
    
    recurringCheckbox.addEventListener('change', toggleRecurringDays);
    toggleRecurringDays(); // Initial state
});
</script>
@endsection 