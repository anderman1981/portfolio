<?php

namespace Database\Seeders;

use App\Models\JobPortal;
use Illuminate\Database\Seeder;

class JobPortalsSeeder extends Seeder
{
    public function run(): void
    {
        $portals = [
            // Featured Portals
            ['name' => 'LinkedIn', 'description' => 'Red profesional global con millones de empleos', 'url' => 'https://linkedin.com/jobs', 'category' => 'General', 'specialty' => 'Empleo general, networking', 'icon_color' => 'blue', 'featured' => true, 'sort_order' => 1],
            ['name' => 'Indeed', 'description' => 'Buscador masivo y generalista de empleo', 'url' => 'https://indeed.com', 'category' => 'General', 'specialty' => 'Empleo general en todo el mundo', 'icon_color' => 'blue', 'featured' => true, 'sort_order' => 2],
            ['name' => 'Glassdoor', 'description' => 'Empleo con reseñas de empresas y salarios', 'url' => 'https://glassdoor.com', 'category' => 'General', 'specialty' => 'Transparencia salarial y reviews de empresas', 'icon_color' => 'green', 'featured' => true, 'sort_order' => 3],

            // Remote Jobs
            ['name' => 'FlexJobs', 'description' => 'Trabajos remotos y flexibles verificados', 'url' => 'https://flexjobs.com', 'category' => 'Remote', 'specialty' => 'Empleo remoto y flexible', 'icon_color' => 'purple', 'featured' => true, 'sort_order' => 4],
            ['name' => 'We Work Remotely', 'description' => 'Ofertas 100% remotas, principalmente tech', 'url' => 'https://weworkremotely.com', 'category' => 'Remote', 'specialty' => 'Empleo remoto, especialmente tech', 'icon_color' => 'purple', 'featured' => true, 'sort_order' => 5],
            ['name' => 'Remote.com', 'description' => 'Gestión de contratación internacional y nóminas remotas', 'url' => 'https://remote.com', 'category' => 'Remote', 'specialty' => 'Nóminas remotas internacionales', 'icon_color' => 'purple', 'sort_order' => 6],
            ['name' => 'Remotive', 'description' => 'Job board de trabajos remotos de calidad en digital/tech', 'url' => 'https://remotive.io', 'category' => 'Remote', 'specialty' => 'Empleos remotos digitales curados', 'icon_color' => 'purple', 'sort_order' => 7],
            ['name' => 'Virtual Vocations', 'description' => 'Ofertas remotas revisadas manualmente', 'url' => 'https://virtualvocations.com', 'category' => 'Remote', 'specialty' => 'Empleos remotos verificados', 'icon_color' => 'purple', 'sort_order' => 8],
            ['name' => 'Working Nomads', 'description' => 'Curación de trabajos remotos para nómadas digitales', 'url' => 'https://workingnomads.com', 'category' => 'Remote', 'specialty' => 'Empleos para nómadas digitales', 'icon_color' => 'purple', 'sort_order' => 9],
            ['name' => 'Remote OK', 'description' => 'Tablero muy popular de empleos 100% remotos en startups', 'url' => 'https://remoteok.io', 'category' => 'Remote', 'specialty' => 'Startups remotas', 'icon_color' => 'purple', 'sort_order' => 10],
            ['name' => 'Skip the Drive', 'description' => 'Tablón de empleos remotos', 'url' => 'https://skipthedrive.com', 'category' => 'Remote', 'specialty' => 'Empleo remoto variado', 'icon_color' => 'purple', 'sort_order' => 11],
            ['name' => 'Just Remote', 'description' => 'Listados de trabajos remotos con filtros por zona horaria', 'url' => 'https://justremote.com', 'category' => 'Remote', 'specialty' => 'Empleos remotos por zona horaria', 'icon_color' => 'purple', 'sort_order' => 12],
            ['name' => 'Remote Work Hub', 'description' => 'Comunidad y recursos para carreras 100% remotas', 'url' => 'https://remoteworkhub.com', 'category' => 'Remote', 'specialty' => 'Comunidad remota y recursos', 'icon_color' => 'purple', 'sort_order' => 13],

            // Freelance
            ['name' => 'Upwork', 'description' => 'Marketplace general de proyectos freelance', 'url' => 'https://upwork.com', 'category' => 'Freelance', 'specialty' => 'Proyectos freelance variados', 'icon_color' => 'green', 'featured' => true, 'sort_order' => 14],
            ['name' => 'Freelancer.com', 'description' => 'Plataforma de proyectos freelance por puja', 'url' => 'https://freelancer.com', 'category' => 'Freelance', 'specialty' => 'Proyectos por puja', 'icon_color' => 'green', 'sort_order' => 15],
            ['name' => 'Fiverr', 'description' => 'Microservicios freelance empaquetados como "gigs"', 'url' => 'https://fiverr.com', 'category' => 'Freelance', 'specialty' => 'Microtrabajos y gigs', 'icon_color' => 'green', 'sort_order' => 16],
            ['name' => 'Guru', 'description' => 'Freelance con herramientas para gestionar proyectos y pagos', 'url' => 'https://guru.com', 'category' => 'Freelance', 'specialty' => 'Gestión de proyectos freelance', 'icon_color' => 'green', 'sort_order' => 17],
            ['name' => 'Toptal', 'description' => 'Red de freelancers tech muy filtrada de alto nivel', 'url' => 'https://toptal.com', 'category' => 'Freelance', 'specialty' => 'Freelancers tech de élite', 'icon_color' => 'green', 'sort_order' => 18],
            ['name' => 'HubstaffTalent', 'description' => 'Directorio gratuito de freelancers remotos sin comisiones', 'url' => 'https://hubstafftalent.com', 'category' => 'Freelance', 'specialty' => 'Freelancers remotos sin comisión', 'icon_color' => 'green', 'sort_order' => 19],
            ['name' => 'OnlineJobs.ph', 'description' => 'Talento remoto especializado en Filipinas', 'url' => 'https://onlinejobs.ph', 'category' => 'Freelance', 'specialty' => 'Talento remoto desde Filipinas', 'icon_color' => 'green', 'sort_order' => 20],

            // Tech & Startups
            ['name' => 'Angel.co', 'description' => 'Empleo en startups, muchas con opciones y remoto', 'url' => 'https://angel.co', 'category' => 'Tech', 'specialty' => 'Startups con equity', 'icon_color' => 'red', 'featured' => true, 'sort_order' => 21],
            ['name' => 'Hired', 'description' => 'Talento tech donde las empresas aplican a ti', 'url' => 'https://hired.com', 'category' => 'Tech', 'specialty' => 'Reclutadores aplican a ti', 'icon_color' => 'red', 'sort_order' => 22],

            // Design & Creative
            ['name' => 'Designhill', 'description' => 'Encargos y concursos de diseño gráfico', 'url' => 'https://designhill.com', 'category' => 'Creative', 'specialty' => 'Diseño gráfico y concursos', 'icon_color' => 'pink', 'sort_order' => 23],
            ['name' => 'Behance', 'description' => 'Portfolios creativos con ofertas de diseño y trabajos creativos', 'url' => 'https://behance.net', 'category' => 'Creative', 'specialty' => 'Empleos creativos y portfolios', 'icon_color' => 'pink', 'sort_order' => 24],

            // Content & Writing
            ['name' => 'ProBlogger Jobs', 'description' => 'Tablero clásico para trabajos de blogging y contenido escrito', 'url' => 'https://problogger.com/jobs', 'category' => 'Writing', 'specialty' => 'Blogging y contenido escrito', 'icon_color' => 'yellow', 'sort_order' => 25],
            ['name' => 'Freelance Writing Gigs', 'description' => 'Ofertas freelance de redacción y blogging', 'url' => 'https://freelancewritinggigs.com', 'category' => 'Writing', 'specialty' => 'Redacción freelance', 'icon_color' => 'yellow', 'sort_order' => 26],
            ['name' => 'Content Writing Jobs', 'description' => 'Empleo remoto para content writers y copywriters', 'url' => 'https://contentwritingjobs.com', 'category' => 'Writing', 'specialty' => 'Content writing remoto', 'icon_color' => 'yellow', 'sort_order' => 27],

            // Specialized & Aggregators
            ['name' => 'Simply Hired', 'description' => 'Agregador que recopila ofertas de muchas webs', 'url' => 'https://simplyhired.com', 'category' => 'General', 'specialty' => 'Agregador de múltiples fuentes', 'icon_color' => 'gray', 'sort_order' => 28],
            ['name' => 'Jooble', 'description' => 'Metabuscador internacional de ofertas de muchas fuentes', 'url' => 'https://jooble.org', 'category' => 'General', 'specialty' => 'Búsqueda internacional', 'icon_color' => 'gray', 'sort_order' => 29],
            ['name' => 'Talent.com', 'description' => 'Buscador global de empleo con rangos salariales estimados', 'url' => 'https://talent.com', 'category' => 'General', 'specialty' => 'Rangos salariales globales', 'icon_color' => 'gray', 'sort_order' => 30],
            ['name' => 'The Muse', 'description' => 'Empleo con foco en cultura de empresa y contenidos de carrera', 'url' => 'https://themuse.com', 'category' => 'General', 'specialty' => 'Cultura empresarial y desarrollo', 'icon_color' => 'blue', 'sort_order' => 31],
            ['name' => 'TaskRabbit', 'description' => 'Encargos y tareas presenciales locales (no remoto)', 'url' => 'https://taskrabbit.com', 'category' => 'Services', 'specialty' => 'Tareas presenciales locales', 'icon_color' => 'orange', 'sort_order' => 32],
            ['name' => 'Zirtual', 'description' => 'Servicio de asistentes virtuales por suscripción', 'url' => 'https://zirtual.com', 'category' => 'Services', 'specialty' => 'Asistentes virtuales', 'icon_color' => 'orange', 'sort_order' => 33],
            ['name' => 'Kickresume', 'description' => 'Optimiza tu CV con IA para pasar filtros de selección', 'url' => 'https://kickresume.com', 'category' => 'Tools', 'specialty' => 'Optimización de CV con IA', 'icon_color' => 'indigo', 'featured' => true, 'sort_order' => 0],
        ];

        foreach ($portals as $portal) {
            JobPortal::create($portal);
        }
    }
}
