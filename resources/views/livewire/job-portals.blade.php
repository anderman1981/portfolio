<div class="py-8 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-2">🌐 Job Search Portals</h2>
            <p class="text-gray-600 text-lg">33 plataformas curadas para encontrar tu próxima oportunidad</p>
        </div>

        <!-- Featured Section -->
        @if($featured->count())
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">⭐ Featured Portals</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($featured as $portal)
                <a href="{{ $portal->url }}" target="_blank"
                   class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $portal->icon_color }}-50 to-{{ $portal->icon_color }}-100"></div>
                    <div class="relative p-6 text-center">
                        <div class="text-3xl mb-2">
                            @switch($portal->name)
                                @case('LinkedIn')
                                    💼
                                @break
                                @case('Indeed')
                                    🔍
                                @break
                                @case('Glassdoor')
                                    💎
                                @break
                                @case('FlexJobs')
                                    🏠
                                @break
                                @case('We Work Remotely')
                                    🌍
                                @break
                                @case('Upwork')
                                    ⚡
                                @break
                                @case('Angel.co')
                                    🚀
                                @break
                                @case('Kickresume')
                                    ✨
                                @break
                                @default
                                    📌
                            @endswitch
                        </div>
                        <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $portal->name }}</h4>
                        <p class="text-sm text-gray-700 mb-3">{{ $portal->description }}</p>
                        <span class="inline-block text-xs font-semibold text-{{ $portal->icon_color }}-600 bg-{{ $portal->icon_color }}-100 px-3 py-1 rounded-full">
                            {{ $portal->category }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Search & Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search Portals</label>
                    <input
                        type="text"
                        placeholder="Find portals..."
                        wire:model.live="searchPortal"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                    <select
                        wire:model.live="selectedCategory"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Portals Grid -->
        <div class="space-y-8">
            @forelse($portals->groupBy('category') as $category => $categoryPortals)
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded"></div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $category }}</h3>
                    <span class="text-sm text-gray-500">{{ $categoryPortals->count() }} portales</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categoryPortals as $portal)
                    <a href="{{ $portal->url }}" target="_blank"
                       class="group bg-white rounded-lg shadow hover:shadow-xl transition-all duration-300 overflow-hidden hover:translate-y-[-4px] border border-gray-200 hover:border-blue-300">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $portal->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $portal->category }}</p>
                                </div>
                                <span class="inline-block text-lg opacity-60 group-hover:opacity-100 transition">
                                    @switch($category)
                                        @case('Remote')
                                            🌍
                                        @break
                                        @case('Freelance')
                                            💰
                                        @break
                                        @case('Tech')
                                            💻
                                        @break
                                        @case('Creative')
                                            🎨
                                        @break
                                        @case('Writing')
                                            ✍️
                                        @break
                                        @case('Services')
                                            🛠️
                                        @break
                                        @case('Tools')
                                            ⚙️
                                        @break
                                        @default
                                            📌
                                    @endswitch
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">{{ $portal->description }}</p>

                            @if($portal->specialty)
                            <p class="text-xs text-gray-500 italic mb-4 pb-4 border-t border-gray-100">
                                💡 {{ $portal->specialty }}
                            </p>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="inline-block text-xs font-medium text-{{ $portal->icon_color }}-600 bg-{{ $portal->icon_color }}-50 px-2.5 py-1 rounded">
                                    {{ $portal->category }}
                                </span>
                                <span class="text-xs text-gray-400 group-hover:text-blue-500 transition">
                                    Visitar →
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No portals found. Try a different search.</p>
            </div>
            @endforelse
        </div>

        <!-- Stats Footer -->
        <div class="mt-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-2">Ready to find your next opportunity?</h3>
            <p class="text-blue-100 mb-6">Explora estas {{ $portals->count() }} plataformas curadas y encuentra el trabajo perfecto para ti.</p>
            <a href="/" class="inline-block bg-white text-blue-600 font-bold px-6 py-3 rounded-lg hover:bg-blue-50 transition">
                ← Volver al Portfolio
            </a>
        </div>
    </div>
</div>
