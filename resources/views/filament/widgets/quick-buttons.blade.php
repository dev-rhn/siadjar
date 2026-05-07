<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-wrap gap-4">
            <div class="transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <x-filament::button 
                    href="{{ route('filament.admin.resources.santris.index') }}" 
                    tag="a" 
                    icon="heroicon-m-users" 
                    color="info">
                    Data Santri
                </x-filament::button>
            </div>

            <div class="transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <x-filament::button 
                    href="{{ route('filament.admin.resources.surats.index') }}" 
                    tag="a" 
                    icon="heroicon-m-document-text" 
                    color="success">
                    Data Surat
                </x-filament::button>
            </div>

            <div class="transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <x-filament::button 
                    href="{{ route('filament.admin.resources.users.index') }}" 
                    tag="a" 
                    icon="heroicon-m-user-circle" 
                    color="gray">
                    Data Pegawai
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>