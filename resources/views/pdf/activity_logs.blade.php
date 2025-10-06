@php
    use Carbon\Carbon;
@endphp
<x-layouts.pdf :title="$title">
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-sm {
            font-size: 12px;
        }

        .text-xs {
            font-size: 10px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            background-color: #e5e7eb;
            font-size: 10px;
        }

        .description {
            max-width: 200px;
            word-wrap: break-word;
        }

        .subject,
        .causer {
            max-width: 120px;
        }

        .date-col {
            width: 100px;
        }
    </style>

    @if ($activityLogs->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Log Type</th>
                    <th>Description</th>
                    <th>Subject</th>
                    <th>User</th>
                    <th class="date-col">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activityLogs as $log)
                    <tr>
                        <td>
                            @if ($log->log_name)
                                <span class="badge">{{ str_replace('_', ' ', Str::title($log->log_name)) }}</span>
                            @else
                                <span class="text-xs">No log name</span>
                            @endif
                        </td>
                        <td class="description">
                            <div class="text-sm">{{ $log->description }}</div>
                        </td>
                        <td class="subject">
                            @if ($log->subject && $log->subject_type)
                                <div class="text-sm">
                                    @if (isset($log->subject->name))
                                        {{ $log->subject->name }}
                                    @elseif(isset($log->subject->title))
                                        {{ $log->subject->title }}
                                    @elseif(isset($log->subject->firstName) || isset($log->subject->lastName))
                                        {{ trim(($log->subject->firstName ?? '') . ' ' . ($log->subject->lastName ?? '')) }}
                                    @else
                                        ID: {{ $log->subject->id }}
                                    @endif
                                </div>
                                <div class="text-xs">{{ class_basename($log->subject_type) }}</div>
                            @else
                                <span class="text-xs">No subject</span>
                            @endif
                        </td>
                        <td class="causer">
                            @if ($log->causer && $log->causer_type)
                                <div class="text-sm">
                                    @if (isset($log->causer->name))
                                        {{ $log->causer->name }}
                                    @elseif(isset($log->causer->firstName) || isset($log->causer->lastName))
                                        {{ trim(($log->causer->firstName ?? '') . ' ' . ($log->causer->lastName ?? '')) }}
                                    @else
                                        ID: {{ $log->causer->id }}
                                    @endif
                                </div>
                                <div class="text-xs">{{ class_basename($log->causer_type) }}</div>
                            @else
                                <span class="text-xs">System</span>
                            @endif
                        </td>
                        <td class="text-sm">
                            <div>{{ Carbon::parse($log->created_at)->format('M d, Y') }}</div>
                            <div class="text-xs">{{ Carbon::parse($log->created_at)->format('h:i A') }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-sm" style="margin-top: 20px;">
            <strong>Total Records:</strong> {{ $activityLogs->count() }}
        </div>
    @else
        <div class="text-center" style="padding: 40px;">
            <p>No activity logs found for the selected criteria.</p>
        </div>
    @endif
</x-layouts.pdf>
