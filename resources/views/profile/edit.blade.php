<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800">
            Profile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Update Profile -->
            <div class="p-4 bg-white shadow rounded-lg">
                <div class="max-w-lg">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 bg-white shadow rounded-lg">
                <div class="max-w-lg">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="p-4 bg-white shadow rounded-lg md:col-span-2">
                <div class="max-w-lg">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
