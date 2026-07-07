<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Single professional font family throughout */
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #333; 
            line-height: 1.4; 
            font-size: 11px; 
            margin: 0; 
            padding: 20px;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .name { font-size: 22px; font-weight: bold; margin-bottom: 4px; color: #1a202c; text-transform: uppercase; }
        .title { font-size: 14px; color: #4a5568; margin-bottom: 8px; font-weight: 500; }
        .contact { color: #718096; font-size: 10px; }
        
        .section-title { 
            font-size: 13px; 
            font-weight: bold; 
            border-bottom: 2px solid #2d3748; 
            padding-bottom: 3px; 
            margin-top: 15px; 
            margin-bottom: 10px; 
            text-transform: uppercase; 
            color: #2d3748; 
        }
        
        .job { margin-bottom: 12px; page-break-inside: avoid; }
        .job-header { font-weight: bold; font-size: 12px; color: #1a202c; }
        .job-meta { font-style: italic; color: #718096; font-size: 10px; margin-bottom: 4px; }
        .achievements { margin-left: 15px; padding: 0; list-style-type: disc; }
        .achievements li { margin-bottom: 3px; }
        
        .skill-category { font-weight: bold; font-size: 11px; color: #2d3748; }
        .skills { margin-bottom: 5px; font-size: 11px; }
        
        .education-item { margin-bottom: 5px; font-size: 11px; }
        
        /* Limit spacing to help with length */
        p { margin: 0 0 10px 0; text-align: justify; }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">ANDERSON MARTINEZ RESTREPO</div>
        <div class="title">AI Solutions Architect & Tech Lead</div>
        <div class="contact">Sabaneta, Colombia | +57 316 826 5737 | andersonmares81@gmail.com</div>
    </div>

    <div class="section-title">Perfil Profesional</div>
    <p>Líder Técnico y Desarrollador Full Stack con 17 años de experiencia en arquitectura de software y automatización. Especialista en diseñar ecosistemas que integran lógica de negocio avanzada en Backend (Laravel, Node.js, Python) con interfaces interactivas. Experto en optimizar procesos corporativos mediante APIs REST e Inteligencia Artificial, liderando equipos técnicos para asegurar la estabilidad operativa y la escalabilidad de plataformas críticas.</p>

    <div class="section-title">Experiencia Profesional</div>
    @foreach($experiences as $exp)
        <div class="job">
            <div class="job-header">{{ $exp->role }} | {{ $exp->company }}</div>
            <div class="job-meta">{{ $exp->start_date }} – {{ $exp->end_date }} | {{ $exp->location }}</div>
            <ul class="achievements">
                @foreach($exp->achievements as $achievement)
                    <li>{{ $achievement }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <div class="section-title">Habilidades Técnicas</div>
    @foreach($skills as $category => $skillList)
        <div class="skills">
            <span class="skill-category">{{ $category }}:</span> 
            {{ $skillList->pluck('name')->implode(', ') }}
        </div>
    @endforeach

    <div class="section-title">Educación</div>
    @foreach($education as $ed)
        <div class="education-item">
            <strong>{{ $ed->degree }}</strong> | {{ $ed->institution }} ({{ $ed->year }})
        </div>
    @endforeach
</body>
</html>
