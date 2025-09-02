<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-40 bg-gradient-to-b from-red-50 via-white to-red-100 text-gray-800">
        <div class="p-4 flex items-center space-x-2 text-lg font-bold">
            <img src="{{ asset('imgs/icon.png') }}" alt="App Icon" class="w-[10.5rem] h-[3.5rem]">
        </div>

        <nav class="p-4">
            <ul class="space-y-2 list-disc list-inside">
                <a href="{{ route('dashboard') }}"
                   class="block px-3 py-2 rounded 
                   {{ request()->routeIs('dashboard') ? 'bg-red-200 font-semibold' : 'hover:bg-red-100' }}">
                    Dashboard
                </a>

                <a href="{{ route('customers.index') }}"
                   class="block px-3 py-2 rounded 
                   {{ request()->is('customers*') ? 'bg-red-200 font-semibold' : 'hover:bg-red-100' }}">
                    Customers
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="block px-3 py-2 rounded 
                   {{ request()->is('transactions*') ? 'bg-red-200 font-semibold' : 'hover:bg-red-100' }}">
                    Transactions
                </a>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <nav class="bg-gradient-to-r from-red-50 via-white to-red-100 px-6 pt-[1.50rem] pb-[0.75rem] flex justify-between items-center shadow">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ $header ?? 'Dashboard' }}
                </h1>
            </div>

            <!-- Profile Dropdown -->
            <div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white hover:text-gray-800 rounded shadow">
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
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </nav>

        <!-- Page Slot -->
        <main class="flex-1 p-6 bg-gradient-to-b from-red-50 via-white to-red-100">
            {{ $slot }}
        </main>
    </div>
</div>
