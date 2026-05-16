@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="w-full space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">User Management Directory</h1>
            <p class="text-xs text-slate-500">Manage corporate digital identities, division mappings, and system access clearance.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Provision New User
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                    <th class="p-4">Employee ID</th>
                    <th class="p-4">Full Name</th>
                    <th class="p-4">Corporate Email</th>
                    <th class="p-4">Division</th>
                    <th class="p-4">System Role</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($users as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-mono font-semibold text-slate-900">{{ $item->employee_id }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $item->full_name }}</td>
                        <td class="p-4 text-slate-500">{{ $item->email }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-medium text-slate-600">
                                {{ $item->divisionRelation?->name ?? 'Unassigned' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $item->role_id == 1 ? 'bg-purple-50 text-purple-700 border border-purple-100' : ($item->role_id == 2 ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-slate-100 text-slate-600') }}">
                                {{ $item->role_id == 1 ? 'Super Admin' : ($item->role_id == 2 ? 'Manager' : 'User') }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('users.edit', $item) }}" class="p-1 text-slate-400 hover:text-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                @if(auth()->id() != $item->id)
                                    <form action="{{ route('users.destroy', $item) }}" method="post" onsubmit="GlobalActions.confirmDelete(event, this)">
                                        @csrf
                                        @php echo method_field('DELETE'); @endphp
                                        <button class="p-1 text-slate-400 hover:text-rose-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 font-medium">No system identities found in this environment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-2">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection