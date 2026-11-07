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
            'company'    => 'Cliente Confidencial · EEUU',
            'role'       => ['es' => 'Desarrollador Full Stack & Especialista en Integraciones', 'en' => 'Full Stack Developer & Integration Specialist'],
            'location'   => ['es' => 'Remoto · EEUU', 'en' => 'Remote · USA'],
            'start_date' => '2026-01-01',
            'end_date'   => null,
            'is_current' => true,
            'achievements' => [
                'es' => [
                    'Eliminé el registro manual de pagos en 15 sitios web diseñando una capa de integración que conecta GoHighLevel, Slack, Nextiva, email y QuickBooks: cada transacción genera su factura, notifica al equipo y actualiza el sistema telefónico automáticamente.',
                    'Consolidé un ecosistema de 15 sitios corporativos bajo estándares modernos de rendimiento, SEO y seguridad, reduciendo la carga operativa del equipo de TI.',
                ],
                'en' => [
                    'Eliminated manual payment logging across 15 websites by architecting an integration layer connecting GoHighLevel, Slack, Nextiva, email, and QuickBooks — each transaction now auto-generates its invoice, alerts the team, and updates the phone system.',
                    'Consolidated a 15-site corporate ecosystem under unified performance, SEO, and security standards, reducing IT team operational overhead.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'GSD CONSULTING',
            'role'       => ['es' => 'Consultor Técnico de TI & Desarrollador de IA', 'en' => 'IT Technical Consultant & AI Developer'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => '2025-09-01',
            'end_date'   => '2026-02-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Construí un ATS con IA que extrae datos de hojas de vida y evalúa candidatos mediante análisis de sentimiento y detección de nivel de inglés a partir de video, eliminando semanas de preselección manual.',
                    'Desarrollé un bot interactivo en Slack que guía paso a paso la inducción de nuevos empleados, estandarizando el onboarding sin intervención humana.',
                    'Integré Time Doctor con Slack para automatizar reportes de asistencia y unifiqué el monitoreo de equipos remotos en un solo panel web.',
                    'Lideré la estrategia técnica como desarrollador principal, coordinando al equipo de soporte de TI.',
                ],
                'en' => [
                    'Built an AI-powered ATS that scrapes CV data and scores candidates through sentiment analysis and English-level detection from video, eliminating weeks of manual pre-screening.',
                    'Developed an interactive Slack bot that guides new hires through onboarding step by step, standardizing the process without human intervention.',
                    'Integrated Time Doctor with Slack to automate attendance reports and unified remote-team monitoring in a single web dashboard.',
                    'Led technical strategy as principal developer, coordinating IT support staff.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'ALCOCK AND ASSOCIATES',
            'role'       => ['es' => 'Senior Full Stack Developer & IT Lead', 'en' => 'Senior Full Stack Developer & IT Lead'],
            'location'   => ['es' => 'Remoto · EEUU', 'en' => 'Remote · USA'],
            'start_date' => '2024-12-01',
            'end_date'   => '2026-01-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Lideré toda la estrategia tecnológica de la firma como único desarrollador, desde la hoja de ruta de sistemas hasta la coordinación del equipo de soporte de TI.',
                    'Reduje de horas a minutos la generación de reportes legales aplicando IA para extraer y estructurar datos del sistema Abacus Law, listos para consumo automatizado vía API.',
                    'Integré la infraestructura telefónica con Abacus Law mediante APIs REST, centralizando el registro de datos operativos y eliminando duplicación manual entre sistemas.',
                    'Garanticé migración cero-pérdida de datos críticos diseñando un sistema de respaldos automatizados desde sistemas legacy cerrados hacia servicios internos.',
                    'Automaticé flujos críticos del equipo legal con apps a medida y scripts Zapier/Make, reduciendo significativamente la carga operativa manual semanal.',
                ],
                'en' => [
                    'Led the firm\'s entire technology strategy as its sole developer, owning the systems roadmap and coordinating IT support staff.',
                    'Reduced legal report generation from hours to minutes by applying AI to extract and structure data from Abacus Law, ready for automated API consumption.',
                    'Integrated telephony infrastructure with Abacus Law via REST APIs, centralizing operational data and eliminating manual duplication between systems.',
                    'Guaranteed zero-loss data migration by engineering an automated backup system that moved critical records from closed legacy systems to internal services.',
                    'Automated critical legal-team workflows with custom apps and Zapier/Make scripts, significantly reducing weekly manual workload.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'INGACOV',
            'role'       => ['es' => 'Desarrollador Full Stack (Proyecto Freelance)', 'en' => 'Full Stack Developer (Freelance Project)'],
            'location'   => ['es' => 'Colombia', 'en' => 'Colombia'],
            'start_date' => '2025-01-01',
            'end_date'   => '2025-08-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Diseñé y construí como único desarrollador el ERP completo de la empresa en Laravel: inventario, compras, logística y reabastecimiento de máquinas de mezcla operando en hospitales y universidades.',
                    'Entregué el sistema completo — desde el modelo de datos hasta el despliegue en producción — que hoy gestiona toda la operación de la compañía.',
                ],
                'en' => [
                    'Designed and built the company\'s full ERP solo in Laravel: inventory, purchasing, logistics, and restocking for blending machines deployed in hospitals and universities.',
                    'Delivered the complete system — from data model to production deployment — which now runs the company\'s entire operation.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'KIPCLIN | HIBRYD',
            'role'       => ['es' => 'PHP Developer', 'en' => 'PHP Developer'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => '2024-03-01',
            'end_date'   => '2024-10-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Optimicé módulos críticos en plataformas Yii2/PHP, mejorando de forma medible el rendimiento y la usabilidad de las aplicaciones.',
                    'Colaboré con QA para identificar cuellos de botella de rendimiento y refactorizar código antes de los despliegues a producción.',
                ],
                'en' => [
                    'Optimized critical modules on Yii2/PHP platforms, delivering measurable improvements in performance and application usability.',
                    'Partnered with QA to surface performance bottlenecks and refactor code before production releases.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'BUILT - TECH FIRST',
            'role'       => ['es' => 'PHP Developer', 'en' => 'PHP Developer'],
            'location'   => ['es' => 'Remoto', 'en' => 'Remote'],
            'start_date' => '2022-03-01',
            'end_date'   => '2024-01-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Entregué mantenimiento correctivo y nuevas funcionalidades en sistemas Laravel/PHP para múltiples clientes, cumpliendo con sus reglas de negocio específicas.',
                    'Mantuve sistemas en producción estables y confiables mientras extendía funcionalidad en paralelo en varias bases de código de clientes.',
                ],
                'en' => [
                    'Delivered corrective maintenance and new features on Laravel/PHP systems for multiple clients, each aligned to their specific business rules.',
                    'Kept production systems stable and reliable while extending functionality across concurrent client codebases.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'CTI CONSULTING',
            'role'       => ['es' => 'PHP Developer', 'en' => 'PHP Developer'],
            'location'   => ['es' => 'Colombia', 'en' => 'Colombia'],
            'start_date' => '2022-01-01',
            'end_date'   => '2022-02-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    'Realicé depuración intensiva en aplicaciones legacy y documenté la lógica de negocio no documentada para el departamento de sistemas.',
                    'Estabilicé bases de código heredadas, entregando una base limpia y documentada al equipo para continuar el desarrollo.',
                ],
                'en' => [
                    'Performed intensive bug fixing on legacy applications and documented undocumented business logic for the systems department.',
                    'Stabilized inherited codebases, handing the team a clean, documented foundation to build on.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'INCOLMOTOS YAMAHA S.A.',
            'role'       => ['es' => 'Analista de Sistemas / Desarrollador Senior', 'en' => 'Systems Analyst / Senior Developer'],
            'location'   => ['es' => 'Sabaneta, Antioquia, Colombia', 'en' => 'Sabaneta, Antioquia, Colombia'],
            'start_date' => '2000-10-01',
            'end_date'   => '2022-10-01',
            'is_current' => false,
            'achievements' => [
                'es' => [
                    '22 años en la organización — los últimos 13 dedicados exclusivamente a desarrollo de software y administración de sistemas de TI.',
                    'Implementé y administré la plataforma Moodle LMS a nivel nacional para capacitar virtualmente a toda la red de concesionarios Yamaha: cientos de empleados en múltiples ciudades, sin desplazamientos.',
                    'Diseñé la intranet corporativa en Joomla y desarrollé módulos a medida para recursos humanos y control de producción, digitalizando procesos internos clave.',
                    'Unifiqué bases de datos relacionales heterogéneas para optimizar el flujo de información en la línea de manufactura, eliminando inconsistencias entre sistemas.',
                ],
                'en' => [
                    '22-year tenure — the last 13 focused exclusively on software development and IT systems administration.',
                    'Deployed and administered Moodle LMS nationwide to train the entire Yamaha dealer network virtually: hundreds of employees across multiple cities, no travel required.',
                    'Designed the corporate intranet in Joomla and built custom modules for HR and production control, digitizing key internal processes.',
                    'Unified heterogeneous relational databases to streamline data flow on the manufacturing line, eliminating cross-system inconsistencies.',
                ],
            ],
        ]);

        Experience::create([
            'company'    => 'WEB TECHNOLOGY COLOMBIA',
            'role'       => ['es' => 'Consultoría y Soporte Web (Independiente)', 'en' => 'Web Consulting & Support (Independent)'],
            'location'   => ['es' => 'Colombia', 'en' => 'Colombia'],
            'start_date' => '2014-12-01',
            'end_date'   => null,
            'is_current' => true,
            'achievements' => [
                'es' => [
                    'Aliado tecnológico permanente que provee desarrollo web, mantenimiento e integración de sistemas para las operaciones digitales de la compañía a lo largo de más de una década.',
                    'Mantengo sitios, integraciones e infraestructura funcionando de forma confiable, evolucionándolos conforme cambian las necesidades del negocio.',
                    'Asesoro en decisiones técnicas e implemento mejoras de forma continua, operando como CTO externo de confianza.',
                ],
                'en' => [
                    'Permanent technology partner providing web development, maintenance, and systems integration for the company\'s digital operations across more than a decade.',
                    'Keep websites, integrations, and infrastructure running reliably, evolving them as business needs change.',
                    'Advise on technical decisions and implement improvements continuously, operating as a trusted external CTO.',
                ],
            ],
        ]);

        // ─── SKILLS ───────────────────────────────────────────────────────────

        $skills = [
            // Backend
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'PHP / Laravel',                     'name_en' => 'PHP / Laravel',                     'p' => 95],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Node.js',                           'name_en' => 'Node.js',                           'p' => 85],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Python',                            'name_en' => 'Python',                            'p' => 80],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Yii2',                              'name_en' => 'Yii2',                              'p' => 80],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'C# / .NET',                         'name_en' => 'C# / .NET',                         'p' => 70],
            ['cat_es' => 'Backend',                    'cat_en' => 'Backend',                    'name_es' => 'Go',                                'name_en' => 'Go',                                'p' => 60],
            // Frontend
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'JavaScript / jQuery',               'name_en' => 'JavaScript / jQuery',               'p' => 90],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'TypeScript',                        'name_en' => 'TypeScript',                        'p' => 85],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'Angular / AngularJS',               'name_en' => 'Angular / AngularJS',               'p' => 85],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'React / Next.js',                   'name_en' => 'React / Next.js',                   'p' => 75],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'Livewire / Alpine.js',              'name_en' => 'Livewire / Alpine.js',              'p' => 85],
            ['cat_es' => 'Frontend',                   'cat_en' => 'Frontend',                   'name_es' => 'HTML5 / CSS3 / Tailwind',           'name_en' => 'HTML5 / CSS3 / Tailwind',           'p' => 90],
            // Bases de Datos
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'MySQL / SQL Server',                'name_en' => 'MySQL / SQL Server',                'p' => 90],
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'PostgreSQL',                        'name_en' => 'PostgreSQL',                        'p' => 80],
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'SQLite',                            'name_en' => 'SQLite',                            'p' => 80],
            ['cat_es' => 'Bases de Datos',             'cat_en' => 'Databases',                  'name_es' => 'BigQuery',                          'name_en' => 'BigQuery',                          'p' => 65],
            // Arquitectura y APIs
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'APIs REST',                         'name_en' => 'REST APIs',                         'p' => 95],
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'GraphQL',                           'name_en' => 'GraphQL',                           'p' => 75],
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'Microservicios',                    'name_en' => 'Microservices',                     'p' => 80],
            ['cat_es' => 'Arquitectura & APIs',        'cat_en' => 'Architecture & APIs',        'name_es' => 'Webhooks & Event-driven',           'name_en' => 'Webhooks & Event-driven',           'p' => 90],
            // Integraciones
            ['cat_es' => 'Integraciones',              'cat_en' => 'Integrations',               'name_es' => 'Zapier / Make.com',                 'name_en' => 'Zapier / Make.com',                 'p' => 90],
            ['cat_es' => 'Integraciones',              'cat_en' => 'Integrations',               'name_es' => 'GoHighLevel',                       'name_en' => 'GoHighLevel',                       'p' => 85],
            ['cat_es' => 'Integraciones',              'cat_en' => 'Integrations',               'name_es' => 'QuickBooks API',                    'name_en' => 'QuickBooks API',                    'p' => 80],
            ['cat_es' => 'Integraciones',              'cat_en' => 'Integrations',               'name_es' => 'Slack API',                         'name_en' => 'Slack API',                         'p' => 85],
            // IA y Automatización
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'Gemini API',                        'name_en' => 'Gemini API',                        'p' => 90],
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'OpenAI / Claude API',               'name_en' => 'OpenAI / Claude API',               'p' => 85],
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'NLP / Visión Computacional',        'name_en' => 'NLP / Computer Vision',             'p' => 80],
            ['cat_es' => 'IA & Automatización',        'cat_en' => 'AI & Automation',            'name_es' => 'Agentes IA / Multi-agente',         'name_en' => 'AI Agents / Multi-agent',           'p' => 85],
            // Liderazgo
            ['cat_es' => 'Liderazgo & Gestión de TI', 'cat_en' => 'Leadership & IT Management', 'name_es' => 'Dirección de Estrategia Tecnológica','name_en' => 'Tech Strategy Direction',           'p' => 90],
            ['cat_es' => 'Liderazgo & Gestión de TI', 'cat_en' => 'Leadership & IT Management', 'name_es' => 'Coordinación de Equipos TI',        'name_en' => 'IT Team Coordination',              'p' => 90],
            ['cat_es' => 'Liderazgo & Gestión de TI', 'cat_en' => 'Leadership & IT Management', 'name_es' => 'Administración de Sistemas',        'name_en' => 'Systems Administration',            'p' => 85],
            // DevOps
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Docker / Docker Compose',           'name_en' => 'Docker / Docker Compose',           'p' => 85],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Git / GitHub',                      'name_en' => 'Git / GitHub',                      'p' => 90],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'CI/CD',                             'name_en' => 'CI/CD',                             'p' => 75],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Web Scraping / Cheerio',            'name_en' => 'Web Scraping / Cheerio',            'p' => 80],
            ['cat_es' => 'DevOps & Herramientas',      'cat_en' => 'DevOps & Tools',             'name_es' => 'Metodologías Ágiles / Scrum',       'name_en' => 'Agile / Scrum',                     'p' => 85],
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
            'institution' => ['es' => 'UNISABANETA — Sabaneta, Colombia', 'en' => 'UNISABANETA — Sabaneta, Colombia'],
            'year'        => '2018',
        ]);

        Education::create([
            'degree'      => ['es' => 'Tecnólogo en Sistemas', 'en' => 'Systems Technologist'],
            'institution' => ['es' => 'Compuedu — Envigado, Colombia', 'en' => 'Compuedu — Envigado, Colombia'],
            'year'        => '2004',
        ]);

        Education::create([
            'degree'      => ['es' => 'Certificación Full Stack Web Development', 'en' => 'Full Stack Web Development Certification'],
            'institution' => ['es' => 'LinkedIn Learning', 'en' => 'LinkedIn Learning'],
            'year'        => '',
        ]);

        // ─── PROYECTOS ────────────────────────────────────────────────────────

        Project::create([
            'title'        => [
                'es' => 'Plataforma de Integraciones Corporativas (Cliente · EEUU)',
                'en' => 'Corporate Integration Platform (Client · USA)',
            ],
            'description'  => [
                'es' => '15 sitios web, 5 plataformas conectadas, cero registro manual. Diseñé la capa de integración que unifica GoHighLevel, Slack, Nextiva, email y QuickBooks: cada pago genera su factura, notifica al equipo y actualiza el teléfono automáticamente.',
                'en' => '15 websites, 5 platforms connected, zero manual logging. Designed the integration layer unifying GoHighLevel, Slack, Nextiva, email, and QuickBooks: every payment auto-generates its invoice, alerts the team, and updates the phone system.',
            ],
            'technologies' => ['GoHighLevel', 'Slack API', 'QuickBooks API', 'Nextiva', 'Make.com', 'Zapier', 'Node.js'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => [
                'es' => 'ERP de Gestión Integral (INGACOV)',
                'en' => 'Comprehensive Management ERP (INGACOV)',
            ],
            'description'  => [
                'es' => 'ERP completo construido de cero como desarrollador único en Laravel: inventario, compras, logística y reabastecimiento para máquinas de mezcla en hospitales y universidades. Hoy gestiona toda la operación de la empresa.',
                'en' => 'Full ERP built from scratch solo in Laravel: inventory, purchasing, logistics, and restocking for blending machines in hospitals and universities. Today it runs the company\'s entire operation.',
            ],
            'technologies' => ['Laravel', 'PHP', 'MySQL', 'REST API', 'Filament'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => [
                'es' => 'ATS con Inteligencia Artificial (GSD Consulting)',
                'en' => 'AI-Powered ATS (GSD Consulting)',
            ],
            'description'  => [
                'es' => 'Sistema de reclutamiento que automatiza la preselección de candidatos: extrae datos de hojas de vida y evalúa nivel de inglés y perfil emocional mediante análisis de sentimiento sobre grabaciones de video.',
                'en' => 'Recruitment system automating candidate pre-screening: extracts CV data and evaluates English level and emotional profile via sentiment analysis on video recordings.',
            ],
            'technologies' => ['Python', 'NLP', 'OpenAI', 'Node.js', 'REST API'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => [
                'es' => 'Sistema de Vigilancia Rama Judicial (IA + Scraping)',
                'en' => 'Judicial Branch Monitoring System (AI + Scraping)',
            ],
            'description'  => [
                'es' => 'Plataforma dockerizada que vigila automáticamente el portal de providencias de la Rama Judicial colombiana: scraping Liferay/SharePoint, parsing de documentos con IA (Gemini), pre-filtro local normalizado para minimizar costos de tokens, y dashboard con aprobación manual y notificaciones SMTP.',
                'en' => 'Dockerized platform that automatically monitors the Colombian Judicial Branch case portal: Liferay/SharePoint scraping, AI-powered document parsing (Gemini), normalized local pre-filter to minimize token costs, and a manual-approval dashboard with SMTP notifications.',
            ],
            'technologies' => ['Node.js', 'TypeScript', 'PostgreSQL', 'Docker', 'Gemini AI', 'Cheerio', 'SheetJS', 'SharePoint', 'Liferay'],
            'url'          => null,
        ]);

        Project::create([
            'title'        => [
                'es' => 'Integración Telefonía – Abacus Law (Alcock & Associates)',
                'en' => 'Telephony – Abacus Law Integration (Alcock & Associates)',
            ],
            'description'  => [
                'es' => 'Integración de la infraestructura telefónica con el software legal Abacus Law vía REST APIs para centralizar registros operativos, más sistema de backups automatizados para migración zero-loss desde sistemas legacy cerrados.',
                'en' => 'Integration of telephony infrastructure with Abacus Law legal software via REST APIs to centralize operational records, plus automated backup system for zero-loss migration from closed legacy systems.',
            ],
            'technologies' => ['REST API', 'PHP', 'Zapier', 'Make.com', 'Abacus Law'],
            'url'          => null,
        ]);
    }
}
