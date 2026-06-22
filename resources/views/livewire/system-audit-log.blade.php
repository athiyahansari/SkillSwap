<div>
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">System Activity Logs</h3>
        
        <div class="flex items-center space-x-2">
            <label for="timeframe" class="text-sm font-medium text-slate-500">Filter:</label>
            <select wire:model.live="timeframe" id="timeframe" class="text-sm border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="today">Today</option>
                <option value="7_days">Past 7 Days</option>
                <option value="30_days">Past Month</option>
                <option value="all">All Time</option>
            </select>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-1/5">Timestamp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-1/5">Event</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-32">User ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-1/5">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-full">Payload Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md 
                                    @if(str_contains($log->event_type, 'login') || str_contains($log->event_type, 'logout')) bg-blue-100 text-blue-800 
                                    @elseif(str_contains($log->event_type, 'admin')) bg-purple-100 text-purple-800 
                                    @elseif($log->event_type == 'deleted') bg-rose-100 text-rose-800
                                    @else bg-emerald-100 text-emerald-800 @endif">
                                    {{ ucwords(str_replace('_', ' ', $log->event_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 font-medium">
                                {{ $log->user_id ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if($log->new_values)
                                    <details class="cursor-pointer group">
                                        <summary class="font-semibold text-indigo-600 hover:text-indigo-800">View Data</summary>
                                        <pre class="mt-2 p-3 bg-slate-800 text-emerald-400 rounded-lg text-xs overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    </details>
                                @else
                                    <span class="text-slate-400 italic">No payload</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No audit logs found for this timeframe.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
