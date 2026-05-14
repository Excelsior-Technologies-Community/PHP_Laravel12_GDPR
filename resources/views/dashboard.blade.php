<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-3xl font-extrabold text-white tracking-wide">
                    GDPR Dashboard
                </h2>

                <p class="text-slate-400 mt-1 text-sm">
                    Secure Privacy & User Management System
                </p>
            </div>

            <div class="bg-cyan-500/20 border border-cyan-400/30 px-5 py-2 rounded-2xl backdrop-blur-lg shadow-lg">
                <span class="text-cyan-300 font-semibold">
                    👋 {{ auth()->user()->name }}
                </span>
            </div>

        </div>
    </x-slot>

    <div class="min-h-screen bg-[#0f172a] py-10">

        <div class="max-w-7xl mx-auto px-6 space-y-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-5 py-4 rounded-2xl shadow-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Top Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card -->
                <div class="bg-[#111c44] border border-slate-700 rounded-3xl p-7 shadow-2xl hover:scale-105 transition duration-300">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-400 text-sm">
                                Total Users
                            </p>

                            <h2 class="text-5xl font-bold text-white mt-3">
                                {{ $users->total() }}
                            </h2>

                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-cyan-500/20 flex items-center justify-center text-3xl">
                            👥
                        </div>

                    </div>

                </div>

                <!-- Card -->
                <div class="bg-[#111c44] border border-slate-700 rounded-3xl p-7 shadow-2xl hover:scale-105 transition duration-300">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-400 text-sm">
                                GDPR Security
                            </p>

                            <h2 class="text-3xl font-bold text-emerald-400 mt-3">
                                Protected
                            </h2>

                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-3xl">
                            🔐
                        </div>

                    </div>

                </div>

                <!-- Card -->
                <div class="bg-[#111c44] border border-slate-700 rounded-3xl p-7 shadow-2xl hover:scale-105 transition duration-300">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-400 text-sm">
                                Framework
                            </p>

                            <h2 class="text-3xl font-bold text-violet-400 mt-3">
                                Laravel 12
                            </h2>

                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-violet-500/20 flex items-center justify-center text-3xl">
                            ⚡
                        </div>

                    </div>

                </div>

            </div>

            <!-- GDPR Download -->
            <div class="bg-[#111c44] border border-slate-700 rounded-3xl p-8 shadow-2xl">

                <div class="flex items-center justify-between mb-8">

                    <div>

                        <h2 class="text-2xl font-bold text-white">
                            Download Personal Data
                        </h2>

                        <p class="text-slate-400 mt-1">
                            Export encrypted GDPR protected information
                        </p>

                    </div>

                    <div class="text-5xl">
                        📥
                    </div>

                </div>

                <form method="POST" action="/gdpr/download" class="space-y-5">
                    @csrf

                    <div>

                        <label class="block text-slate-300 mb-2 font-medium">
                            Confirm Password
                        </label>

                        <input type="password"
                               name="password"
                               placeholder="Enter your password"
                               class="w-full bg-[#0f172a] border border-slate-600 text-white rounded-2xl px-5 py-4 placeholder-slate-500 focus:ring-2 focus:ring-cyan-500 outline-none">

                    </div>

                    <button
                        class="bg-cyan-500 hover:bg-cyan-600 transition duration-300 text-white px-6 py-3 rounded-2xl shadow-lg font-semibold">
                        Download My Data
                    </button>

                </form>

            </div>

            <!-- User Management -->
            <div class="bg-[#111c44] border border-slate-700 rounded-3xl p-8 shadow-2xl">

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

                    <div>

                        <h2 class="text-3xl font-bold text-white">
                            User Management
                        </h2>

                        <p class="text-slate-400 mt-1">
                            Search, manage and monitor user data
                        </p>

                    </div>

                    <!-- Search -->
                    <form method="GET">

                        <div class="relative">

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search users..."
                                   class="w-72 bg-[#0f172a] border border-slate-600 text-white placeholder-slate-500 rounded-2xl px-5 py-3 pr-12 focus:ring-2 focus:ring-cyan-500 outline-none">

                            <span class="absolute right-4 top-3 text-slate-400">
                                🔍
                            </span>

                        </div>

                    </form>

                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-2xl border border-slate-700">

                    <table class="w-full">

                        <thead class="bg-[#0f172a]">

                            <tr>

                                <th class="px-6 py-4 text-left text-cyan-300 uppercase text-sm">
                                    ID
                                </th>

                                <th class="px-6 py-4 text-left text-cyan-300 uppercase text-sm">
                                    User
                                </th>

                                <th class="px-6 py-4 text-left text-cyan-300 uppercase text-sm">
                                    Email
                                </th>

                                <th class="px-6 py-4 text-left text-cyan-300 uppercase text-sm">
                                    SS Number
                                </th>

                                <th class="px-6 py-4 text-center text-cyan-300 uppercase text-sm">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($users as $user)

                                <tr class="border-t border-slate-700 hover:bg-slate-800/40 transition">

                                    <td class="px-6 py-5 text-slate-300 font-semibold">
                                        #{{ $user->id }}
                                    </td>

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4">

                                            <div class="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>

                                            <div>

                                                <h3 class="font-semibold text-white">
                                                    {{ $user->name }}
                                                </h3>

                                                <p class="text-sm text-slate-400">
                                                    GDPR User
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-6 py-5 text-slate-300">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-5 text-slate-300">
                                        {{ $user->ssnumber }}
                                    </td>

                                    <td class="px-6 py-5 text-center">

                                        <form action="{{ route('users.destroy', $user) }}"
                                              method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                onclick="return confirm('Delete User?')"
                                                class="bg-red-500 hover:bg-red-600 transition duration-300 text-white px-5 py-2 rounded-xl shadow-lg">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="text-center py-10 text-slate-400">

                                        No users found 🚫

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- Pagination -->
                <div class="mt-8 text-slate-300">
                    {{ $users->links() }}
                </div>

            </div>

        </div>

    </div>

</x-app-layout>