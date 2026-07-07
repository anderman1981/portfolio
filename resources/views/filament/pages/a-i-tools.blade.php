<x-filament-panels::page>
    <style>
        [class*="heroicon"] {
            max-width: 100% !important;
            width: auto !important;
            height: auto !important;
        }
        svg[class*="heroicon"] {
            max-height: 24px;
            max-width: 24px;
        }
        .fi-section svg {
            max-height: 20px;
            max-width: 20px;
        }
    </style>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Tools Section -->
        <div class="md:col-span-8 space-y-6">
            <x-filament::section heading="Herramientas Disponibles" description="Módulos de IA integrados en el ecosistema.">
                <div class="space-y-3">
                    <!-- Agent Memory -->
                    <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-lg transition">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 p-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-md">
                                <x-heroicon-o-cpu-chip class="w-4 h-4"/>
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <h4 class="text-sm font-semibold truncate">Agent Memory</h4>
                                    <x-filament::badge color="info" size="xs">Core</x-filament::badge>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Sincronización de memoria persistente entre sesiones mediante SQLite.</p>
                                <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                    <span class="flex items-center gap-1"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Online</span>
                                    <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Docs</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LaraClaude -->
                    <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-lg transition">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 p-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-md">
                                <x-heroicon-o-shield-check class="w-4 h-4"/>
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <h4 class="text-sm font-semibold truncate">LaraClaude Toolkit</h4>
                                    <x-filament::badge color="success" size="xs">Security</x-filament::badge>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Auditoría de seguridad y análisis estático de código impulsado por Claude AI.</p>
                                <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                    <span>v1.0.4</span>
                                    <a href="#" class="text-emerald-500 hover:text-emerald-600 font-medium">Auditar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Design.md -->
                    <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-lg transition">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 p-1.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-md">
                                <x-heroicon-o-document-text class="w-4 h-4"/>
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <h4 class="text-sm font-semibold truncate">Design.md Standard</h4>
                                    <x-filament::badge color="warning" size="xs">UI/UX</x-filament::badge>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Guía maestra de componentes y estilos para la generación de interfaces.</p>
                                <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                    <span>v2.4.0</span>
                                    <a href="#" class="text-amber-600 hover:text-amber-700 font-medium">Ver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section heading="Logs Recientes">
                <div class="text-xs text-slate-400 italic">No hay actividad reciente para mostrar.</div>
            </x-filament::section>
        </div>

        <!-- Sidebar Section -->
        <div class="md:col-span-4 space-y-4">
            <x-filament::section heading="Estadísticas" class="h-fit">
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                        <span class="text-xs text-slate-500">Chat Messages</span>
                        <span class="font-bold text-sm">{{ \App\Models\ChatMessage::count() }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-800 pb-2">
                        <span class="text-xs text-slate-500">Memory Nodes</span>
                        <span class="font-bold text-sm">{{ \App\Models\ChatMemory::count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Repositories</span>
                        <span class="font-bold text-sm">{{ \App\Models\Repository::count() }}</span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section heading="Estado del Sistema" class="h-fit">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-medium text-slate-700 dark:text-slate-200">Operativo</span>
                </div>
                <div class="h-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500"></div>
                </div>
            </x-filament::section>

            <div class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg">
                <h4 class="text-xs font-bold uppercase tracking-tight text-slate-400 mb-2">Sincronizar</h4>
                <x-filament::button color="gray" icon="heroicon-o-arrow-path" size="sm" class="w-full">
                    Actualizar
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
