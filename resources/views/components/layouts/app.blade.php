<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.analytics')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Anderson Martínez | AI Solutions Architect & Tech Lead' }}</title>
    <meta name="description" content="Tech Lead & Full Stack Developer con 17+ años. Arquitecturas backend, automatización con IA, integración de sistemas y liderazgo técnico.">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Anderson Martínez | AI Solutions Architect & Tech Lead">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/AndersonMR.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('portfolio.index') }}" class="flex items-center space-x-2">
                <span class="text-2xl font-bold text-blue-600">AM</span>
                <span class="text-gray-900 font-semibold">Portfolio</span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('portfolio.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                    Home
                </a>
                <a href="{{ route('applications.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                    Applications
                </a>
                <a href="{{ route('download.cv') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    📥 Download CV
                </a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400">
            <p>&copy; 2026 Anderson Martínez Restrepo. All rights reserved.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
