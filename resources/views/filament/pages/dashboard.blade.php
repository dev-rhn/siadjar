<x-filament-panels::page>
    <div class="space-y-6">
        @forelse ($this->widgets() as $widget)
            <div>
                {{ $widget }}
            </div>
        @empty
            <p class="text-gray-500">No widgets available</p>
        @endforelse
    </div>
</x-filament-panels::page>

