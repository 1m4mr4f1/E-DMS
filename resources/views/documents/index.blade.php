@extends('layouts.app')

@php 
    use Illuminate\Support\Str; 
@endphp

@section('title', 'Documents')

@section('content')
<div class="w-full space-y-4">
    
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Documents Repository</h1>
            <p class="text-xs text-slate-500">All available business documents and records within your access privileges.</p>
        </div>
        
        @can('create', App\Models\Document::class)
        <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Upload Document
        </a>
        @endcan
    </div>

    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('documents.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            
            <div class="flex-1 relative">
                <svg class="h-4 w-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, desc, or ID..." 
                    class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400">
            </div>
            
            <div class="w-full md:w-48">
                <select name="category_id" class="w-full text-xs rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-36">
                <select name="label" class="w-full text-xs rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    <option value="">All Labels</option>
                    <option value="fix" {{ request('label') === 'fix' ? 'selected' : '' }}>Fix</option>
                    <option value="draft" {{ request('label') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div class="w-full md:w-36">
                <select name="visibility" class="w-full text-xs rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    <option value="">All Visibilities</option>
                    <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-sm hover:bg-slate-800 transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'label', 'visibility']))
                    <a href="{{ route('documents.index') }}" class="flex items-center justify-center text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-lg text-xs font-semibold transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($documents->count())
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden w-full">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-bold">Document Title</th>
                            <th scope="col" class="px-4 py-3 font-bold">Description</th>
                            <th scope="col" class="px-4 py-3 font-bold">Category</th>
                            <th scope="col" class="px-4 py-3 font-bold">Label</th>
                            <th scope="col" class="px-4 py-3 font-bold">Visibility</th>
                            <th scope="col" class="px-4 py-3 font-bold">Created By</th>
                            <th scope="col" class="px-4 py-3 font-bold">Created At</th>
                            <th scope="col" class="px-4 py-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($documents as $document)
                            <tr class="{{ $document->trashed() ? 'bg-slate-100/70 hover:bg-slate-100' : 'hover:bg-slate-50/50' }} transition-colors">
                                
                                <td class="px-4 py-2.5 font-semibold text-slate-900 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-400 shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z" />
                                        </svg>
                                        
                                        <a href="{{ $document->trashed() ? '#' : route('documents.show', $document) }}" 
                                           class="{{ $document->trashed() ? 'text-slate-500 cursor-not-allowed pointer-events-none' : 'hover:text-blue-600' }} truncate max-w-[250px]" title="{{ $document->name }}">
                                            {{ $document->name }}
                                        </a>

                                        @if($document->trashed())
                                            <span class="inline-flex items-center gap-1 rounded bg-rose-100 px-1.5 py-0.5 text-[10px] font-bold text-rose-700 uppercase tracking-wide border border-rose-200 shadow-sm animate-pulse">
                                                Trash
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-2.5 text-slate-500 max-w-[350px] truncate" title="{{ $document->description }}">
                                    {{ $document->description ? Str::limit($document->description, 100) : 'No description provided' }}
                                </td>

                                <td class="px-4 py-2.5 whitespace-nowrap text-slate-700">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded border border-slate-200 bg-slate-50 font-medium text-slate-600">
                                        {{ $document->category?->name ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-4 py-2.5 whitespace-nowrap font-medium">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded border {{ $document->label === 'fix' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                                        {{ ucfirst($document->label) }}
                                    </span>
                                </td>

                                <td class="px-4 py-2.5 whitespace-nowrap font-medium">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded border {{ $document->visibility === 'public' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                        {{ ucfirst($document->visibility) }}
                                    </span>
                                </td>

                                <td class="px-4 py-2.5 text-slate-600 whitespace-nowrap">
                                    {{ $document->creator?->full_name ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-2.5 text-slate-500 whitespace-nowrap">
                                    {{ $document->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        
                                        @if($document->trashed())
                                            <form action="{{ route('documents.restore', $document->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="Restore Document" class="inline-flex items-center gap-1 rounded-md border border-emerald-200 bg-emerald-50 px-2 py-1 text-[11px] font-bold text-emerald-700 shadow-sm hover:bg-emerald-100 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                                    </svg>
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('documents.show', $document) }}" title="View Details"
                                               class="p-1 rounded-md text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>

                                            @can('update', $document)
                                            <a href="{{ route('documents.edit', $document) }}" title="Edit Metadata"
                                               class="p-1 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>
                                            @endcan

                                            @can('delete', $document)
                                            <form action="{{ route('documents.destroy', $document) }}" method="post" class="inline" onsubmit="GlobalActions.confirmDelete(event, this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete Document"
                                                        class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endcan
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($documents->hasPages())
                <div class="px-4 py-2 bg-slate-50 border-t border-slate-200">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-10 h-10 text-slate-400 mx-auto mb-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <p class="text-xs font-semibold text-slate-600">No matching records found.</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Try adjusting your search criteria or clear the applied filters.</p>
        </div>
    @endif
</div>
@endsection