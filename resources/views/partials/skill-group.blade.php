<div class="card p-6" data-reveal>
    <div class="mb-5 flex items-center justify-between">
        <h3 class="font-display text-lg font-semibold">{{ $group->name }}</h3>
        <span class="text-xl">{{ $group->icon }}</span>
    </div>
    <div class="space-y-4">
        @foreach ($group->skills as $skill)
            <div>
                <div class="mb-1.5 flex justify-between text-sm"><span class="text-white/80">{{ $skill->name }}</span><span class="text-white/40">{{ $skill->level }}%</span></div>
                <div class="h-1 overflow-hidden rounded-full bg-white/10"><div data-bar class="grad-bg h-full origin-left rounded-full" style="width: {{ $skill->level }}%"></div></div>
            </div>
        @endforeach
    </div>
</div>
