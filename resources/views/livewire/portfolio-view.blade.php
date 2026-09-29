<div class="min-h-screen" x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 20">

    {{-- ─── NAVBAR ─────────────────────────────────────────────────────────────── --}}
    <nav :class="scrolled ? 'shadow-md bg-white/95' : 'bg-white/80'"
         class="fixed top-0 w-full z-50 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                {{-- Logo --}}
                <a href="#about" class="flex items-center space-x-2 group">
                    <img src="{{ asset('images/AndersonMR.jpg') }}" alt="Anderson Martinez" width="36" height="36" class="w-9 h-9 rounded-xl object-cover shadow-md shadow-blue-200" loading="lazy">
                    <span class="font-bold text-base tracking-tight hidden sm:block">Anderson Martinez</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden lg:flex items-center space-x-1 text-sm font-medium text-slate-600">
                    @foreach([
                        ['href' => '#experience', 'label' => __('portfolio.nav.experience')],
                        ['href' => '#skills',     'label' => __('portfolio.nav.skills')],
                        ['href' => '#projects',   'label' => __('portfolio.nav.projects')],
                        ['href' => '#education',  'label' => __('portfolio.nav.education')],
                        ['href' => '#about',      'label' => __('portfolio.nav.contact')],
                    ] as $link)
                    <a href="{{ $link['href'] }}" class="px-3 py-2 rounded-lg hover:bg-slate-100 hover:text-blue-600 transition-all">{{ $link['label'] }}</a>
                    @endforeach

                    <div class="flex bg-slate-100 p-1 rounded-lg ml-3">
                        <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ app()->getLocale() === 'es' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">ES</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">EN</a>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('download.cv') }}" class="hidden sm:inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-semibold transition-colors shadow-md shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ __('portfolio.nav.download') }}
                    </a>
                    {{-- Mobile hamburger --}}
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                        <svg x-show="!mobileMenu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t border-slate-200 bg-white/95 backdrop-blur-md px-4 py-3 space-y-1" x-cloak>
            @foreach([
                ['href' => '#experience', 'label' => __('portfolio.nav.experience')],
                ['href' => '#skills',     'label' => __('portfolio.nav.skills')],
                ['href' => '#projects',   'label' => __('portfolio.nav.projects')],
                ['href' => '#education',  'label' => __('portfolio.nav.education')],
                ['href' => '#about',      'label' => __('portfolio.nav.contact')],
            ] as $link)
            <a href="{{ $link['href'] }}" @click="mobileMenu = false" class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 rounded-lg transition-colors">{{ $link['label'] }}</a>
            @endforeach
            <div class="flex items-center gap-3 px-4 pt-2 pb-1 border-t border-slate-100 mt-2">
                <div class="flex bg-slate-100 p-1 rounded-lg">
                    <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ app()->getLocale() === 'es' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400' }}">ES</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400' }}">EN</a>
                </div>
                <a href="{{ route('download.cv') }}" class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-blue-700 transition-colors">{{ __('portfolio.nav.download') }}</a>
            </div>
        </div>
    </nav>

    {{-- ─── HERO ───────────────────────────────────────────────────────────────── --}}
    <section id="about" class="pt-28 pb-24 px-4 overflow-hidden hero-grid">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-5 gap-12 items-center">

                {{-- Left copy --}}
                <div class="lg:col-span-3 space-y-7">
                    <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-1.5 rounded-full text-sm font-semibold">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        {{ __('portfolio.hero.status') }}
                    </div>

                    <div>
                        <p class="text-blue-600 font-semibold text-lg mb-2 tracking-wide">Anderson Martínez Restrepo</p>
                        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-[1.1]">
                            {{ __('portfolio.hero.title') }}<br>
                            <span class="gradient-text">{{ __('portfolio.hero.subtitle') }}</span>
                        </h1>
                    </div>

                    <p class="text-lg text-slate-600 leading-relaxed max-w-xl">
                        {{ __('portfolio.hero.description') }}
                    </p>

                    {{-- Contact info --}}
                    <div class="flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Sabaneta, Antioquia, Colombia
                        </span>
                        <a href="mailto:andersonmares81@gmail.com" class="flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            andersonmares81@gmail.com
                        </a>
                        <a href="tel:+573168265737" class="flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +57 316 826 5737
                        </a>
                    </div>

                    {{-- CTA buttons --}}
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('download.cv') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold text-sm transition-colors shadow-lg shadow-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ __('portfolio.nav.download') }}
                        </a>
                        <a href="https://linkedin.com/in/anderson-martinez-restrepo" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-blue-300 hover:bg-blue-50 text-slate-700 hover:text-blue-700 px-6 py-3 rounded-full font-semibold text-sm transition-all shadow-sm">
                            <svg class="w-4 h-4 text-[#0077B5]" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                        <a href="#experience" class="inline-flex items-center gap-2 text-slate-600 hover:text-blue-600 px-4 py-3 font-medium text-sm transition-colors">
                            Ver experiencia
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Right visual --}}
                <div class="lg:col-span-2 flex flex-col items-center gap-6">
                    <div class="relative w-full max-w-sm mx-auto">
                        <div class="aspect-square rounded-3xl bg-gradient-to-br from-blue-100 via-slate-100 to-emerald-100 border border-slate-200/80 shadow-2xl flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/AndersonMR.jpg') }}" alt="Anderson Martinez" width="400" height="400" class="w-full h-full object-cover">
                        </div>
                        {{-- Floating stat --}}
                        <div class="absolute -bottom-5 -left-4 bg-white border border-slate-200 rounded-2xl shadow-xl px-5 py-4 text-center">
                            <div class="text-3xl font-black text-blue-600">{{ __('portfolio.hero.stats_years') }}</div>
                            <div class="text-xs text-slate-500 font-medium leading-tight max-w-[110px]">{{ __('portfolio.hero.stats_label') }}</div>
                        </div>
                        {{-- Tech badge --}}
                        <div class="absolute -top-4 -right-4 bg-white border border-slate-200 rounded-2xl shadow-xl px-4 py-3 flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900 leading-none">AI + Full Stack</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Tech Lead</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ─── EXPERIENCE ─────────────────────────────────────────────────────────── --}}
    <section id="experience" class="py-24 bg-slate-50">
        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-14">
                <p class="text-blue-600 font-semibold text-sm uppercase tracking-widest mb-2">{{ __('portfolio.nav.experience') }}</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">{{ __('portfolio.sections.experience') }}</h2>
                <div class="w-16 h-1 bg-blue-600 rounded-full"></div>
            </div>

            {{-- Timeline --}}
            <div class="relative">
                {{-- Vertical line --}}
                <div class="absolute left-0 md:left-[220px] top-2 bottom-2 w-px bg-slate-200 hidden md:block"></div>

                <div class="space-y-10">
                    @foreach($experiences as $exp)
                    <div class="md:flex gap-0 group">
                        {{-- Date / company sidebar --}}
                        <div class="md:w-[220px] md:pr-8 mb-3 md:mb-0 md:text-right flex-shrink-0">
                            <div class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">
                                {{ $exp->start_label }} — {{ $exp->is_current ? __('portfolio.hero.present') : ($exp->end_label ?? __('portfolio.hero.present')) }}
                            </div>
                            <div class="font-bold text-slate-900 text-sm leading-snug">{{ $exp->company }}</div>
                            <div class="text-slate-400 text-xs mt-0.5 italic">{{ $exp->location }}</div>
                        </div>

                        {{-- Connector dot --}}
                        <div class="hidden md:flex flex-col items-center flex-shrink-0 px-0 relative" style="width:1px">
                            <div class="w-3 h-3 rounded-full border-2 border-blue-600 bg-white mt-1 flex-shrink-0 relative z-10 group-hover:bg-blue-600 transition-colors" style="margin-left:-6px"></div>
                        </div>

                        {{-- Content card --}}
                        <div class="md:pl-8 flex-1">
                            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm group-hover:border-blue-200 group-hover:shadow-md transition-all">
                                <div class="flex flex-wrap items-start gap-3 mb-4">
                                    <h3 class="font-bold text-slate-900 text-base leading-snug flex-1">{{ $exp->role }}</h3>
                                    @if($exp->is_current)
                                    <span class="flex-shrink-0 inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        {{ __('portfolio.hero.present') }}
                                    </span>
                                    @endif
                                </div>
                                <ul class="space-y-2.5">
                                    @foreach($exp->achievements as $achievement)
                                    @if(is_string($achievement))
                                    <li class="flex items-start gap-2.5 text-sm text-slate-600 leading-relaxed">
                                        <span class="mt-1.5 w-1.5 h-1.5 bg-blue-400 rounded-full flex-shrink-0"></span>
                                        {{ $achievement }}
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ─── SKILLS ─────────────────────────────────────────────────────────────── --}}
    <section id="skills" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">

            <div class="mb-14 text-center">
                <p class="text-blue-600 font-semibold text-sm uppercase tracking-widest mb-2">{{ __('portfolio.nav.skills') }}</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">{{ __('portfolio.sections.skills') }}</h2>
                <p class="text-slate-500 max-w-xl mx-auto text-base">17 años de experiencia acumulada en tecnologías clave del ecosistema moderno.</p>
            </div>

            @php
            $categoryColors = [
                0 => ['pill' => 'bg-blue-50 text-blue-700 border-blue-200',    'dot' => 'bg-blue-500',    'header' => 'text-blue-700'],
                1 => ['pill' => 'bg-violet-50 text-violet-700 border-violet-200', 'dot' => 'bg-violet-500', 'header' => 'text-violet-700'],
                2 => ['pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'header' => 'text-emerald-700'],
                3 => ['pill' => 'bg-amber-50 text-amber-700 border-amber-200',  'dot' => 'bg-amber-500',   'header' => 'text-amber-700'],
                4 => ['pill' => 'bg-rose-50 text-rose-700 border-rose-200',     'dot' => 'bg-rose-500',    'header' => 'text-rose-700'],
                5 => ['pill' => 'bg-cyan-50 text-cyan-700 border-cyan-200',     'dot' => 'bg-cyan-500',    'header' => 'text-cyan-700'],
                6 => ['pill' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'dot' => 'bg-indigo-500', 'header' => 'text-indigo-700'],
            ];
            $colorIndex = 0;
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($skills as $category => $skillList)
                @php $colors = $categoryColors[$colorIndex % 7]; $colorIndex++; @endphp
                <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-slate-300 hover:shadow-sm transition-all">
                    <h3 class="font-bold text-sm {{ $colors['header'] }} mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $colors['dot'] }}"></span>
                        {{ $category }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($skillList as $skill)
                        <span class="inline-block border text-xs font-medium px-2.5 py-1 rounded-lg {{ $colors['pill'] }}">
                            {{ $skill->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── PROJECTS ───────────────────────────────────────────────────────────── --}}
    <section id="projects" class="py-24 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4">

            <div class="mb-14">
                <p class="text-blue-400 font-semibold text-sm uppercase tracking-widest mb-2">{{ __('portfolio.nav.projects') }}</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ __('portfolio.sections.projects') }}</h2>
                <div class="w-16 h-1 bg-blue-500 rounded-full"></div>
            </div>

            @php
            $projectIcons = [
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>',
            ];
            $projectColors = [
                ['ring' => 'border-blue-500/40',   'icon_bg' => 'bg-blue-600/20',   'icon_text' => 'text-blue-400',   'hover_ring' => 'hover:border-blue-500/60'],
                ['ring' => 'border-violet-500/40', 'icon_bg' => 'bg-violet-600/20', 'icon_text' => 'text-violet-400', 'hover_ring' => 'hover:border-violet-500/60'],
                ['ring' => 'border-emerald-500/40','icon_bg' => 'bg-emerald-600/20','icon_text' => 'text-emerald-400','hover_ring' => 'hover:border-emerald-500/60'],
                ['ring' => 'border-amber-500/40',  'icon_bg' => 'bg-amber-600/20',  'icon_text' => 'text-amber-400',  'hover_ring' => 'hover:border-amber-500/60'],
            ];
            @endphp

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($projects as $index => $project)
                @php $pc = $projectColors[$index % 4]; @endphp
                <div class="group relative bg-slate-800/50 border {{ $pc['ring'] }} {{ $pc['hover_ring'] }} rounded-2xl p-7 hover:bg-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-black/30 flex flex-col">

                    {{-- Top row: icon + index --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-xl {{ $pc['icon_bg'] }} border border-white/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 {{ $pc['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $projectIcons[$index % 4] !!}
                            </svg>
                        </div>
                        <span class="text-xs font-black text-slate-600 tracking-widest">0{{ $index + 1 }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-3 leading-snug group-hover:text-blue-100 transition-colors">{{ $project->title }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-5 flex-1">{{ $project->description }}</p>

                    <div class="flex flex-wrap gap-2 mb-5">
                        @foreach($project->technologies as $tech)
                        <span class="bg-white/5 border border-white/10 text-slate-300 px-2.5 py-1 rounded-lg text-xs font-medium">{{ $tech }}</span>
                        @endforeach
                    </div>

                    @if($project->url)
                    <a href="{{ $project->url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 {{ $pc['icon_text'] }} hover:opacity-80 text-sm font-semibold transition-opacity mt-auto">
                        {{ __('portfolio.sections.view_details') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    @else
                    <div class="flex items-center gap-1.5 text-slate-600 text-xs font-medium mt-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Proyecto privado
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── REPOSITORIES ───────────────────────────────────────────────────────── --}}
    @if($repositories->count() > 0)
    <section id="repositories" class="py-24 bg-slate-800 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="mb-14">
                <p class="text-blue-400 font-semibold text-sm uppercase tracking-widest mb-2">GitHub</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ __('portfolio.sections.repositories') }}</h2>
                <div class="w-16 h-1 bg-blue-500 rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-5">
                @foreach($repositories as $repo)
                <a href="{{ $repo->url }}" target="_blank" rel="noopener" class="group bg-slate-700/50 border border-slate-600/60 p-6 rounded-2xl hover:border-blue-500/60 hover:bg-slate-700 transition-all hover:-translate-y-0.5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-slate-600/60 rounded-lg text-blue-400 group-hover:text-blue-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400">⭐ {{ $repo->stars }}</span>
                    </div>
                    <h3 class="text-sm font-bold mb-2 group-hover:text-blue-400 transition-colors">{{ $repo->name }}</h3>
                    <p class="text-slate-400 text-xs mb-4 line-clamp-2 leading-relaxed">{{ $repo->description }}</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($repo->technologies as $tech)
                        <span class="text-[10px] px-2 py-0.5 bg-slate-600/60 rounded text-slate-300 uppercase tracking-wider font-bold">{{ $tech }}</span>
                        @endforeach
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ─── EDUCATION ──────────────────────────────────────────────────────────── --}}
    <section id="education" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">

            <div class="mb-14">
                <p class="text-blue-600 font-semibold text-sm uppercase tracking-widest mb-2">{{ __('portfolio.nav.education') }}</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">{{ __('portfolio.sections.education') }}</h2>
                <div class="w-16 h-1 bg-blue-600 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($education as $edu)
                <div class="group bg-white border border-slate-200 rounded-2xl p-7 hover:border-blue-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-5 group-hover:bg-blue-100 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    @if($edu->year)
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">{{ $edu->year }}</div>
                    @endif
                    <h3 class="font-bold text-slate-900 text-base leading-snug mb-1.5">{{ $edu->degree }}</h3>
                    <p class="text-slate-500 text-sm">{{ $edu->institution }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── FOOTER ─────────────────────────────────────────────────────────────── --}}
    <footer class="bg-slate-900 text-slate-400 py-14">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">

                <div>
                    <div class="text-2xl font-black text-white tracking-tight mb-1">ANDERSON<span class="text-blue-500">MARTINEZ</span></div>
                    <p class="text-sm text-slate-500">Full Stack Developer & AI Solutions Architect</p>
                </div>

                {{-- Social links --}}
                <div class="flex items-center gap-4">
                    <a href="https://linkedin.com/in/anderson-martinez-restrepo" target="_blank" rel="noopener"
                       class="flex items-center gap-2 bg-slate-800 hover:bg-[#0077B5] border border-slate-700 hover:border-[#0077B5] text-slate-400 hover:text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        LinkedIn
                    </a>
                    <a href="mailto:andersonmares81@gmail.com"
                       class="flex items-center gap-2 bg-slate-800 hover:bg-blue-600 border border-slate-700 hover:border-blue-600 text-slate-400 hover:text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email
                    </a>
                    <a href="{{ route('download.cv') }}"
                       class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 border border-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        CV
                    </a>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-10 pt-8 text-center text-xs text-slate-600">
                &copy; {{ date('Y') }} Anderson Martínez Restrepo — {{ __('portfolio.footer.built_with') }}
            </div>
        </div>
    </footer>

</div>
