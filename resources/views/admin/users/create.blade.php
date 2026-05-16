@extends('layouts.app')

@section('title', 'Provision New User')

@section('content')
<div class="w-full space-y-4 max-w-4xl">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Provision Digital Identity</h1>
        <p class="text-xs text-slate-500">Configure corporate parameters, assign node division, and establish baseline security clearing.</p>
    </div>

    <form action="{{ route('users.store') }}" method="post" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Employee ID <span class="text-rose-500">*</span></label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="e.g. EMP-0092" required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                @error('employee_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                @error('full_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Corporate Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Initial Password <span class="text-rose-500">*</span></label>
                <input type="password" name="password" minlength="8" required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Operational Division <span class="text-rose-500">*</span></label>
                <select name="division_id" required class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                    <option value="">-- Mapped Division --</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                    @endforeach
                </select>
                @error('division_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Clearance Role <span class="text-rose-500">*</span></label>
                <select name="role_id" required class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                    <option value="">-- Clearance Security --</option>
                    @foreach($roles as $id => $name)
                        <option value="{{ $id }}" {{ old('role_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('role_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('users.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">Cancel</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 transition-all">Provision Account</button>
        </div>
    </form>
</div>
@endsection