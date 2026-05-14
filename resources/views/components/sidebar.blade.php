<aside class="w-64 bg-slate-950 text-slate-400 flex-shrink-0 flex flex-col min-h-screen border-r border-slate-900 sticky top-0 z-20">
    
    <div class="p-6 flex items-center gap-3">
        <div class="h-9 w-9 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-2xl">E</div>
        <span class="text-white font-bold tracking-tight text-xl">E-DMS Portal</span>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1">
        
        @include('components.sidebar.general')

        @hasanyrole('Admin|Manager')
            @include('components.sidebar.workflow')
        @endhasanyrole

        @role('Admin')
            @include('components.sidebar.admin')
        @endrole

    </nav>

    @include('components.sidebar.profile')

</aside>