<div class="min-h-screen bg-gradient-to-br from-[#eef0f8] via-white to-[#e8f4ef] px-6 py-10 lg:px-12">

    {{-- Header --}}
    <div class="max-w-4xl mx-auto text-center mb-14">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                     tracking-widest uppercase bg-[#22419A]/10 text-[#22419A] mb-4">
            <span class="w-1.5 h-1.5 rounded-full bg-[#439670] animate-pulse"></span>
            {{ __('managers.badge') }}
        </span>

        <h1 class="text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
            {{ __('managers.title') }}
        </h1>

        <p class="mt-2 text-2xl font-semibold">
            <span class="text-[#439670]">{{ __('managers.subtitle_add') }}</span>
            <span class="text-[#22419A]"> {{ __('managers.subtitle_delete') }}</span>
        </p>

        <div class="mx-auto mt-6 flex items-center justify-center gap-2 w-24">
            <span class="h-px flex-1 bg-[#22419A]/20"></span>
            <span class="w-2 h-2 rounded-full bg-[#439670]"></span>
            <span class="h-px flex-1 bg-[#439670]/20"></span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">

        {{-- Flash --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 px-4 py-3 rounded-2xl bg-[#439670]/10 border border-[#439670]/20 text-[#2d7a58] text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Bouton ajouter --}}
        <div class="flex justify-end mb-6">
            <button wire:click="$toggle('showForm')"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm
                       bg-gradient-to-br from-[#22419A] to-[#1a3278] text-white shadow-lg
                       shadow-[#22419A]/25 hover:-translate-y-0.5 hover:shadow-[#22419A]/40
                       transition-all duration-200">
                <span class="material-symbols-outlined text-[18px]">{{ $showForm ? 'close' : 'person_add' }}</span>
                {{ $showForm ? __('managers.cancel') : __('managers.btn_add') }}
            </button>
        </div>

        {{-- Formulaire --}}
        @if ($showForm)
            <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-6 mb-8 shadow-sm">
                <h2 class="text-lg font-bold text-[#22419A] mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    {{ __('managers.form_title') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 tracking-wide uppercase">{{ __('managers.field_name') }}</label>
                        <input wire:model="name" type="text"
                            class="w-full mt-1 border border-gray-200 rounded-2xl px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#22419A]/30 focus:border-[#22419A]/50
                                   bg-white/80 transition" />
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 tracking-wide uppercase">{{ __('managers.field_email') }}</label>
                        <input wire:model="email" type="email"
                            class="w-full mt-1 border border-gray-200 rounded-2xl px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#22419A]/30 focus:border-[#22419A]/50
                                   bg-white/80 transition" />
                        @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 tracking-wide uppercase">{{ __('managers.field_password') }}</label>
                        <input wire:model="password" type="password"
                            class="w-full mt-1 border border-gray-200 rounded-2xl px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#22419A]/30 focus:border-[#22419A]/50
                                   bg-white/80 transition" />
                        @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button wire:click="addManager"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl font-bold text-sm
                               bg-gradient-to-br from-[#439670] to-[#2d7a58] text-white shadow-lg
                               shadow-[#439670]/25 hover:-translate-y-0.5 transition-all duration-200">
                        <span class="material-symbols-outlined text-[18px]">check</span>
                        {{ __('managers.btn_confirm') }}
                    </button>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 tracking-widest uppercase">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 tracking-widest uppercase">{{ __('managers.col_name') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 tracking-widest uppercase">{{ __('managers.col_email') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 tracking-widest uppercase">{{ __('managers.col_created') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-400 tracking-widest uppercase">{{ __('managers.col_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($managers as $manager)
                        <tr class="hover:bg-[#22419A]/[0.02] transition-colors duration-150">
                            <td class="px-6 py-4 text-gray-300 font-mono text-xs">{{ $manager->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#EDEEF5] to-[#d8dcef]
                                                flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[#22419A] text-[16px]">person</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $manager->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $manager->email }}</td>
                            <td class="px-6 py-4 text-gray-400 text-xs">{{ $manager->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    wire:click="deleteManager({{ $manager->id }})"
                                    wire:confirm="{{ __('managers.confirm_delete') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold
                                           text-red-400 bg-red-50 hover:bg-red-100 hover:text-red-600
                                           transition-all duration-150">
                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                    {{ __('managers.btn_delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <span class="material-symbols-outlined text-gray-200 text-[48px] block mb-3">group_off</span>
                                <p class="text-gray-400 text-sm">{{ __('managers.empty') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>