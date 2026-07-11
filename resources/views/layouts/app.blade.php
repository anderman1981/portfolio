<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.analytics')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Title & Meta --}}
    <title>Anderson Martínez | AI Solutions Architect & Tech Lead</title>
    <meta name="description" content="Tech Lead & Full Stack Developer con 17+ años. Arquitecturas backend, automatización con IA, integración de sistemas y liderazgo técnico. Disponible para nuevos desafíos.">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() == url('/') ? url('/') : url()->current() }}">

    {{-- Open Graph / Social --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Anderson Martínez | AI Solutions Architect & Tech Lead">
    <meta property="og:description" content="Tech Lead & Full Stack Developer con 17+ años. Arquitecturas backend, automatización con IA, integración de sistemas y liderazgo técnico.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/AndersonMR.jpg') }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Anderson Martínez | AI Solutions Architect & Tech Lead">
    <meta name="twitter:description" content="Tech Lead & Full Stack Developer con 17+ años. Arquitecturas backend, automatización con IA y liderazgo técnico.">
    <meta name="twitter:image" content="{{ asset('images/AndersonMR.jpg') }}">

    {{-- JSON-LD Schema.org --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Person",
        "name": "Anderson Martínez Restrepo",
        "givenName": "Anderson",
        "familyName": "Martínez Restrepo",
        "image": "{{ asset('images/AndersonMR.jpg') }}",
        "jobTitle": "AI Solutions Architect & Tech Lead",
        "email": "andersonmares81@gmail.com",
        "telephone": "+573168265737",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Sabaneta",
            "addressRegion": "Antioquia",
            "addressCountry": "CO"
        },
        "url": "{{ url('/') }}",
        "sameAs": [
            "https://linkedin.com/in/anderson-martinez-restrepo"
        ],
        "knowsAbout": [
            "Backend Architecture", "Node.js", "Python", "PHP", "Laravel",
            "Artificial Intelligence", "AI Automation", "Gemini API", "OpenAI",
            "System Integration", "GoHighLevel", "DevOps", "Cloud Architecture",
            "Technical Leadership", "Full Stack Development"
        ],
        "worksFor": [],
        "hasOccupation": {
            "@type": "Occupation",
            "name": "AI Solutions Architect & Tech Lead",
            "skills": "Backend Architecture, AI Automation, System Integration, Technical Leadership, Full Stack Development"
        }
    }
    </script>

    {{-- WebSite schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Anderson Martínez — Portfolio",
        "url": "{{ url('/') }}",
        "description": "Portfolio y CV interactivo de Anderson Martínez Restrepo. AI Solutions Architect & Tech Lead.",
        "author": {
            "@type": "Person",
            "name": "Anderson Martínez Restrepo"
        }
    }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-blue-100 selection:text-blue-900">
    
    {{ $slot }}

    @livewire('a-i-chat')

    @livewireScripts
</body>
</html>
