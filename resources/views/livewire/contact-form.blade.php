<form wire:submit="submit" class="space-y-4">
    @if ($sent)
        <div x-data x-init="window.gsap?.from($el, { scale: .9, opacity: 0, duration: .6 })" class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 p-6 text-center text-emerald-200">
            <p class="text-xl font-semibold">{{ __('Köszönöm az üzenetet!') }} 🎉</p>
            <p class="mt-1 text-sm opacity-80">{{ __('Hamarosan válaszolok.') }}</p>
            <button type="button" wire:click="$set('sent', false)" class="mt-4 text-sm underline">{{ __('Új üzenet küldése') }}</button>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <input wire:model="name" type="text" placeholder="{{ __('Neved') }}" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-[var(--accent-from)] focus:bg-white/10">
                @error('name') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <input wire:model="email" type="email" placeholder="{{ __('E-mail címed') }}" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-[var(--accent-from)] focus:bg-white/10">
                @error('email') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
            </div>
        </div>
        <input wire:model="website" type="text" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
        <div>
            <textarea wire:model="body" rows="5" placeholder="{{ __('Üzeneted') }}" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-[var(--accent-from)] focus:bg-white/10"></textarea>
            @error('body') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
        </div>
        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary w-full justify-center disabled:opacity-60">
            <span wire:loading.remove>{{ __('Üzenet küldése') }}</span>
            <span wire:loading>{{ __('Küldés…') }}</span>
        </button>
    @endif
</form>
