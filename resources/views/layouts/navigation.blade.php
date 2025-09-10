<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-48 bg-red-100 text-red-800 shadow">
       <div class="p-4 flex items-center space-x-2 text-lg font-bold">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('imgs/icon.png') }}" alt="App Icon" class="w-[10.5rem] h-[3.5rem] -mt-[9px]">
            </a>
        </div>


        <nav class="p-4 space-y-2 list-disc list-inside">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 px-3 py-2 rounded 
                {{ request()->routeIs('dashboard') ? 'bg-red-200 font-semibold' : 'hover:bg-red-100' }}">
                <i class="fa fa-home text-sm"></i> Dashboard
            </a>
            
            <a href="{{ route('customers.index') }}" 
                class="flex items-center gap-2 px-3 py-2 rounded 
                {{ request()->is('customers*') ? 'bg-red-200 font-semibold' : 'hover:bg-red-100' }}">
                <i class="fa fa-users text-sm"></i> Customers
            </a>
            
            <a href="{{ route('transactions.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded 
                {{ request()->is('transactions*') ? 'bg-red-200 font-semibold' : 'hover:bg-red-100' }}">
                <i class="fa fa-money-bill-wave text-sm"></i> Transactions
            </a>                
        </nav>
    </aside>                    

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <nav class="bg-red-50 px-4 py-3 flex justify-between items-center shadow-md border-b border-red-200">
            <h1 class="text-xl font-semibold text-red-800">
                {{ $header ?? 'Dashboard' }}
            </h1>

            <!-- Profile Dropdown -->
            <x-dropdown align="right" width="48">
              <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-transparent hover:bg-transparent shadow-none">
                        <div>{{ Auth::user()->name }}</div>
                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 
                            10.586l3.293-3.293a1 1 0 111.414 
                            1.414l-4 4a1 1 0 01-1.414 
                            0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')"><i class="fa-solid fa-user"></i> Profile</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                           <i class="fa-solid fa-right-from-bracket"></i> Log Out
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </nav>

        <!-- Page Slot -->
        <main class="p-4 flex-1 bg-gradient-to-b from-red-50 via-white to-red-100">
            {{ $slot }}
        </main>
    </div>
</div>
