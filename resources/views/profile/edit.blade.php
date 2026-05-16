@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="w-full space-y-4">

    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Account Settings</h1>
            <p class="text-xs text-slate-500">Manage your personalized identity credentials, system avatar, and cryptographic security keys.</p>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" onsubmit="return validateProfileForm(event)">
        @csrf
        @php echo method_field('PATCH'); @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
            
            <div class="lg:col-span-2 space-y-4">
                
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Personal Information</h3>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide uppercase">Employee ID</label>
                            <input type="text" value="{{ $user->employee_id }}" disabled 
                                class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500 cursor-not-allowed outline-none select-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide uppercase">Assigned Division</label>
                            <input type="text" value="{{ $user->divisionRelation?->name ?? 'Information Technology' }}" disabled 
                                class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500 cursor-not-allowed outline-none select-none" />
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                            @error('full_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Corporate Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                            @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Security Credentials Update</h3>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">New Password</label>
                            <input type="password" name="password" id="password-field" minlength="8" placeholder="Leave blank to retain current"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                            @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password-confirm-field" placeholder="Re-type new password"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 lg:col-span-1">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm text-center space-y-4">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 text-left">Identity Avatar</h3>
                    
                    <div class="flex flex-col items-center py-2">
                        @if($user->avatar_url)
                            <img src="{{ asset('storage/' . $user->avatar_url) }}" class="h-24 w-24 rounded-xl object-cover border border-slate-200 p-1 shadow-sm" id="avatar-display" />
                        @else
                            <div class="h-24 w-24 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-200 text-3xl font-bold shadow-inner select-none" id="avatar-fallback">
                                {{ strtoupper(substr($user->full_name ?? $user->name, 0, 1)) }}
                            </div>
                        @endif

                        <label class="mt-4 inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 cursor-pointer transition-all">
                            Browse Photo
                            <input type="file" name="avatar" class="hidden" id="avatar-input" onchange="previewAvatar()" />
                        </label>
                        <p class="text-[10px] text-slate-400 mt-2">Supported: JPG, PNG (Max 2MB)</p>
                        @error('avatar')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-600 space-y-2 shadow-inner">
                    <h4 class="font-bold text-slate-700 uppercase tracking-wide text-[10px]">Session Context</h4>
                    <div class="flex justify-between border-b border-slate-200/60 pb-1">
                        <span class="text-slate-400">System Role</span>
                        <span class="font-semibold text-slate-800 uppercase text-[10px]">
                            {{ $user->role_id == 1 ? 'Super Admin' : ($user->role_id == 2 ? 'Manager' : 'Corporate User') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Joined Date</span>
                        <span class="font-semibold text-slate-800">{{ $user->created_at ? $user->created_at->format('d M Y') : '16 May 2026' }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-200 transition-all gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/slate-50" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Commit Profile Updates
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function previewAvatar() {
        const input = document.getElementById('avatar-input');
        const fallback = document.getElementById('avatar-fallback');
        let display = document.getElementById('avatar-display');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!display) {
                    display = document.createElement('img');
                    display.id = 'avatar-display';
                    display.className = 'h-24 w-24 rounded-xl object-cover border border-slate-200 p-1 shadow-sm';
                    fallback.parentNode.replaceChild(display, fallback);
                }
                display.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function validateProfileForm(event) {
        const password = document.getElementById('password-field').value;
        const confirmation = document.getElementById('password-confirm-field').value;

        if (password.length > 0) {
            if (password.length < 8) {
                event.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Security Requirement',
                        text: 'Cryptographic credentials must be at least 8 characters long.',
                        confirmButtonColor: '#2563eb',
                        customClass: { popup: 'rounded-xl' }
                    });
                }
                return false;
            }

            if (password !== confirmation) {
                event.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Credential Mismatch',
                        text: 'The password confirmation sequence does not match.',
                        confirmButtonColor: '#2563eb',
                        customClass: { popup: 'rounded-xl' }
                    });
                }
                return false;
            }
        }
        return true;
    }
</script>
@endsection