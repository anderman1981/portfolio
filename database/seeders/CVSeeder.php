<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class CVSeeder extends Seeder
{
    public function run(): void
    {
        Experience::truncate();
        Skill::truncate();
        Education::truncate();
        Project::truncate();

        // ─── EXPERIENCIAS ─────────────────────────────────────────────────────

        Experience::create([
            'company'    => 'USA GLASS LLC',
            'role'       => ['es' => 'Desarrollador Full Stack & Especialista en Integraciones', 'en' => 'Full Stack Developer & Integration Specialist'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => 'Enero 2026',
            'end_date'   => null,
            'is_current' => true,
            'achievements' => [
                'es' => [
                    'Integración de Plataformas: Diseñé y construí un flujo de integraciones unificadas para conectar GoHighLevel (GHL), Slack, servicios de email, Nextiva y QuickBooks, automatizando los flujos operativos y de facturación.',
                    'Reorganización Web: Reestructuré y consolidé un ecosistema compuesto por 15 sitios web corporativos bajo estándares modernos de rendimiento, optimización y seguridad informática.',
                ],
                'en' => [
                    'Platform Integration: Designed and built a unified integration flow connecting GoHighLevel (GHL), Slack, email services, Nextiva and QuickBooks, automating operational and billing workflows.',
                    'Web Reorganization: Restructured and consolidated an ecosystem of 15 corporate websites under modern performance, optimization and cybersecurity standards.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'ALCOCK AND ASSOCIATES',
            'role'       => ['es' => 'Senior Full Stack Developer & IT Lead', 'en' => 'Senior Full Stack Developer & IT Lead'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => 'Diciembre 2024',
            'end_date'   => 'Enero 2026',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Gestión de TI: Dirigí la estrategia de tecnología interna como desarrollador principal de la firma, coordinando a una persona dedicada al soporte de TI.',
                    'Reportes y Datos con IA: Utilicé herramientas de IA para optimizar la generación y extracción estructurada de reportes desde el sistema legal interno, formateando los datos para su consumo automatizado a través de APIs.',
                    'Automatización de Backups: Diseñé e implementé sistemas de respaldo automatizados para migrar de forma segura información desde sistemas legacy hacia servicios internos de la compañía.',
                    'Integración de Sistemas Críticos: Vinculé la infraestructura de telefonía con el software legal Abacus Law utilizando APIs REST para centralizar el registro de datos operacionales.',
                    'Optimización de Procesos: Construí aplicaciones web personalizadas y scripts de automatización (Zapier, Make) que redujeron significativamente la carga operativa manual del equipo.',
                ],
                'en' => [
                    'IT Management: Led internal technology strategy as the firm\'s lead developer, coordinating one dedicated IT support staff member.',
                    'AI-Powered Reports & Data: Used AI tools to optimize structured report generation and extraction from the internal legal system, formatting data for automated API consumption.',
                    'Backup Automation: Designed and implemented automated backup systems to securely migrate data from legacy systems to internal company services.',
                    'Critical Systems Integration: Connected telephony infrastructure with Abacus Law legal software using REST APIs to centralize operational data logging.',
                    'Process Optimization: Built custom web apps and automation scripts (Zapier, Make) that significantly reduced the team\'s manual operational workload.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'INGACOV',
            'role'       => ['es' => 'Desarrollador Full Stack (Proyecto Freelance)', 'en' => 'Full Stack Developer (Freelance Project)'],
            'location'   => ['es' => 'Colombia', 'en' => 'Colombia'],
            'start_date' => 'Enero 2025',
            'end_date'   => 'Agosto 2025',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Desarrollo a Medida: Diseñé y desarrollé en Laravel un sistema de gestión integral (ERP) orientado a controlar inventarios, compras, logística y reabastecimiento para máquinas de mezcla (blending) operando en hospitales y universidades.',
                ],
                'en' => [
                    'Custom Development: Designed and built a comprehensive management ERP in Laravel to control inventories, purchases, logistics and restocking for blending machines operating in hospitals and universities.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'KIPCLIN | HIBRYD',
            'role'       => ['es' => 'PHP Developer', 'en' => 'PHP Developer'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => 'Marzo 2024',
            'end_date'   => 'Octubre 2024',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Optimización de Funcionalidades: Optimicé módulos y funciones en plataformas basadas en Yii2/PHP, mejorando el rendimiento técnico y la usabilidad de las aplicaciones.',
                    'Resolución de Errores: Colaboré activamente con el equipo de QA para identificar fallos de rendimiento y realizar refactorizaciones de código antes del despliegue en producción.',
                ],
                'en' => [
                    'Feature Optimization: Optimized modules and functions on Yii2/PHP-based platforms, improving technical performance and application usability.',
                    'Bug Resolution: Actively collaborated with the QA team to identify performance failures and refactor code before production deployment.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'BUILT - TECH FIRST',
            'role'       => ['es' => 'PHP Developer', 'en' => 'PHP Developer'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => 'Marzo 2022',
            'end_date'   => 'Enero 2024',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Mantenimiento y Desarrollo: Realicé mantenimiento correctivo y desarrollo de nuevas características funcionales sobre sistemas basados en Laravel/PHP de acuerdo con las reglas de negocio del cliente.',
                ],
                'en' => [
                    'Maintenance & Development: Performed corrective maintenance and developed new functional features on Laravel/PHP-based systems according to client business rules.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'CTI CONSULTING',
            'role'       => ['es' => 'PHP Developer', 'en' => 'PHP Developer'],
            'location'   => ['es' => 'Colombia', 'en' => 'Colombia'],
            'start_date' => 'Enero 2022',
            'end_date'   => 'Febrero 2022',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Depuración de Sistemas: Realicé depuración intensiva (bug fixing) en aplicaciones heredadas (legacy) y redacté documentación técnica detallada para el departamento de sistemas.',
                ],
                'en' => [
                    'System Debugging: Performed intensive bug fixing on legacy applications and wrote detailed technical documentation for the systems department.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'INCOLMOTOS YAMAHA S.A.',
            'role'       => ['es' => 'Analista de Sistemas / Desarrollador Senior', 'en' => 'Systems Analyst / Senior Developer'],
            'location'   => ['es' => 'Sabaneta, Antioquia, Colombia', 'en' => 'Sabaneta, Antioquia, Colombia'],
            'start_date' => 'Octubre 2000',
            'end_date'   => 'Octubre 2022',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Trayectoria: 22 años en la organización, dedicando los últimos 13 años exclusivamente a funciones técnicas de desarrollo de software y administración de sistemas de TI.',
                    'Gestión de LMS: Implementación y administración a nivel nacional de la plataforma Moodle para los planes de capacitación virtual de la compañía.',
                    'Desarrollo Corporativo: Diseño de la Intranet corporativa (Joomla) y desarrollo de módulos a medida para recursos humanos y control de producción.',
                    'Integración de Datos: Unificación de bases de datos relacionales heterogéneas para optimizar el flujo de información de la línea de manufactura.',
                ],
                'en' => [
                    'Career: 22-year tenure, dedicating the last 13 years exclusively to software development and IT systems administration.',
                    'LMS Management: National implementation and administration of the Moodle platform for the company\'s virtual training programs.',
                    'Corporate Development: Design of the corporate Intranet (Joomla) and development of custom modules for human resources and production control.',
                    'Data Integration: Unification of heterogeneous relational databases to optimize information flow on the manufacturing line.',
                ],
            ],
        ]);

        // ─── SKILLS ───────────────────────────────────────────────────────────

        $skills = [
            // Backend
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Node.js',                           'name_en' => 'Node.js',                           'p' => 85],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Python',                            'name_en' => 'Python',                            'p' => 80],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'PHP / Laravel',                     'name_en' => 'PHP / Laravel',                     'p' => 95],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Yii2',                              'name_en' => 'Yii2',                              'p' => 80],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'C# / .NET',                         'name_en' => 'C# / .NET',                         'p' => 70],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Go',                                'name_en' => 'Go',                                'p' => 60],
            // Frontend
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'Angular / AngularJS',               'name_en' => 'Angular / AngularJS',               'p' => 85],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'JavaScript / jQuery',               'name_en' => 'JavaScript / jQuery',               'p' => 90],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'HTML5 / CSS3 / Bootstrap',          'name_en' => 'HTML5 / CSS3 / Bootstrap',          'p' => 90],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'React',                             'name_en' => 'React',                             'p' => 70],
            // Bases de Datos
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'MySQL / SQL Server',                'name_en' => 'MySQL / SQL Server',                'p' => 90],
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'NoSQL (conceptos)',                 'name_en' => 'NoSQL (concepts)',                  'p' => 65],
            // Arquitectura y APIs
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'APIs REST',                         'name_en' => 'REST APIs',                         'p' => 95],
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'GraphQL',                           'name_en' => 'GraphQL',                           'p' => 75],
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'Microservicios',                    'name_en' => 'Microservices',                     'p' => 80],
            // Liderazgo
            ['cat_es' => 'Liderazgo & Gestión de TI', 'cat_en' => 'Leadership & IT Management', 'name_es' => 'Dirección de Estrategia Tecnológica','name_en' => 'Tech Strategy Direction',           'p' => 90],
            ['cat_es' => 'Liderazgo & Gestión de TI', 'cat_en' => 'Leadership & IT Management', 'name_es' => 'Coordinación de Soporte TI',        'name_en' => 'IT Support Coordination',           'p' => 90],
            ['cat_es' => 'Liderazgo & Gestión de TI', 'cat_en' => 'Leadership & IT Management', 'name_es' => 'Administración de Sistemas',        'name_en' => 'Systems Administration',            'p' => 85],
            // DevOps
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Git',                               'name_en' => 'Git',                               'p' => 90],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Docker',                            'name_en' => 'Docker',                            'p' => 80],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Metodologías Ágiles / Scrum',       'name_en' => 'Agile / Scrum',                     'p' => 85],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'CI/CD',                             'name_en' => 'CI/CD',                             'p' => 75],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Automatización QA',                 'name_en' => 'QA Automation',                     'p' => 75],
            // IA y Automatización
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'Gemini API / OpenAI',               'name_en' => 'Gemini API / OpenAI',               'p' => 90],
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'NLP / Visión Computacional',        'name_en' => 'NLP / Computer Vision',             'p' => 80],
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'Zapier / Make.com',                 'name_en' => 'Zapier / Make.com',                 'p' => 85],
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'Bots de Slack',                     'name_en' => 'Slack Bots',                        'p' => 85],
            // Skills adicionales sincronizadas con proyectos reales
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'TypeScript',                        'name_en' => 'TypeScript',                        'p' => 85],
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'PostgreSQL',                        'name_en' => 'PostgreSQL',                        'p' => 80],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Web Scraping / Cheerio',            'name_en' => 'Web Scraping / Cheerio',            'p' => 80],
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'Integración de APIs Externas',      'name_en' => 'External API Integration',          'p' => 90],
        ];

        foreach ($skills as $s) {
            Skill::create([
                'name'        => ['es' => $s['name_es'], 'en' => $s['name_en']],
                'category'    => ['es' => $s['cat_es'],  'en' => $s['cat_en']],
                'proficiency' => $s['p'],
            ]);
        }

        // ─── EDUCACIÓN ────────────────────────────────────────────────────────

        Education::create([
            'degree'      => ['es' => 'Ingeniería Informática', 'en' => 'Computer Engineering'],
            'institution' => ['es' => 'UNISABANETA, Sabaneta, Colombia', 'en' => 'UNISABANETA, Sabaneta, Colombia'],
            'year'        => 'Graduado (2018)',
        ]);

        Education::create([
            'degree'      => ['es' => 'Tecnólogo en Sistemas', 'en' => 'Systems Technologist'],
            'institution' => ['es' => 'Compuedu, Envigado, Colombia', 'en' => 'Compuedu, Envigado, Colombia'],
            'year'        => '2004',
        ]);

        Education::create([
            'degree'      => ['es' => 'Certificación: Web Development Full Stack', 'en' => 'Certification: Full Stack Web Development'],
            'institution' => ['es' => 'LinkedIn Learning', 'en' => 'LinkedIn Learning'],
            'year'        => '',
        ]);

        // ─── PROYECTOS ────────────────────────────────────────────────────────

        Project::create([
            'title'        => ['es' => 'ERP de Gestión Integral (INGACOV)', 'en' => 'Comprehensive Management ERP (INGACOV)'],
            'description'  => [
                'es' => 'Sistema ERP desarrollado en Laravel para controlar inventarios, compras, logística y reabastecimiento de máquinas de mezcla (blending) operando en hospitales y universidades.',
                'en' => 'ERP system built in Laravel to manage inventories, purchases, logistics and restocking for blending machines operating in hospitals and universities.',
            ],
            'technologies' => ['Laravel', 'PHP', 'MySQL', 'REST API'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => ['es' => 'ATS con Inteligencia Artificial', 'en' => 'AI-Powered ATS'],
            'description'  => [
                'es' => 'Sistema de reclutamiento automatizado que extrae datos de hojas de vida y evalúa candidatos mediante análisis de sentimiento y nivel de inglés desde grabaciones de video.',
                'en' => 'Automated recruitment system that scrapes resume data and evaluates candidates via sentiment analysis and English proficiency from video recordings.',
            ],
            'technologies' => ['Python', 'NLP', 'OpenAI', 'Node.js', 'REST API'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => ['es' => 'Plataforma de Integraciones Corporativas (USA Glass LLC)', 'en' => 'Corporate Integration Platform (USA Glass LLC)'],
            'description'  => [
                'es' => 'Flujo de integraciones unificadas que conecta GoHighLevel (GHL), Slack, email, Nextiva y QuickBooks para automatizar flujos operativos y de facturación en 15 sitios web corporativos.',
                'en' => 'Unified integration flow connecting GoHighLevel (GHL), Slack, email, Nextiva and QuickBooks to automate operational and billing workflows across 15 corporate websites.',
            ],
            'technologies' => ['GoHighLevel', 'Slack API', 'QuickBooks API', 'Make.com', 'Zapier'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => ['es' => 'Integración Telefonía – Abacus Law (Alcock & Associates)', 'en' => 'Telephony – Abacus Law Integration (Alcock & Associates)'],
            'description'  => [
                'es' => 'Integración de infraestructura de telefonía con el software legal Abacus Law via APIs REST para centralizar el registro de datos operacionales, más sistema de backups automatizados para migración segura desde sistemas legacy.',
                'en' => 'Integration of telephony infrastructure with Abacus Law legal software via REST APIs to centralize operational data logging, plus automated backup system for secure migration from legacy systems.',
            ],
            'technologies' => ['REST API', 'PHP', 'Zapier', 'Make.com', 'Abacus Law'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => ['es' => 'Sistema de Monitoreo Rama Judicial', 'en' => 'Judicial Branch Monitoring System'],
            'description'  => [
                'es' => 'Plataforma dockerizada que vigila y realiza scraping automatizado del portal de la Rama Judicial colombiana, con procesamiento de datos asistido por IA y generación de reportes estructurados en hojas de cálculo.',
                'en' => 'Dockerized platform that monitors and automatically scrapes the Colombian Judicial Branch portal, with AI-assisted data processing and structured spreadsheet report generation.',
            ],
            'technologies' => ['Node.js', 'TypeScript', 'PostgreSQL', 'Docker', 'Gemini AI', 'Cheerio', 'SheetJS'],
            'url'          => null,
        ]);
    }
}
