# CV / Career Command Center — CLAUDE.md

Sistema unificado de CV, búsqueda de empleo y postulaciones. Combina una **app Laravel/Filament** (datos, scraping, tracking) con un **framework de Claude Code** (evaluación de fit, redacción de CV/cover, interview prep).

## Arquitectura

```
┌─────────────────── App Laravel (puerto 8118) ───────────────────┐
│  • CV público (portfolio)                                        │
│  • Admin Filament (privado): Experiences, Skills, Projects…      │
│  • Job Feed: jobs:scan → job_listings (5 APIs públicas)          │
│  • Job Portals: 34 portales con credenciales encriptadas         │
│  • Scheduler: jobs:scan auto 6 AM y 6 PM                         │
└──────────────────────────┬───────────────────────────────────────┘
                           │ jobs:export
                           ▼
              job_scraper/seen_jobs.json
                           │
┌──────────────────────────▼─── Claude Code workflow ──────────────┐
│  /rank            → triage de seen_jobs.json en shortlist         │
│  /apply <url>     → eval de fit + CV + cover letter (LaTeX)       │
│  /upskill         → gaps de skills + plan de aprendizaje          │
│  /expand          → expandir competencias desde documentos        │
│  Perfil real en .claude/skills/job-application-assistant/         │
└───────────────────────────────────────────────────────────────────┘
```

## Flujo de trabajo

1. **Buscar** — `php artisan jobs:scan` (o botón "Scan Now" en admin, o automático 6AM/6PM).
   Llena `job_listings` y exporta a `job_scraper/seen_jobs.json`.
2. **Triage** — `/rank` puntúa todas las ofertas nuevas contra el perfil y devuelve un shortlist.
3. **Postular** — `/apply <url>` hace evaluación profunda de fit, y si aprueba, genera CV + cover letter adaptados (pipeline drafter-reviewer).
4. **Registrar** — marca "Applied" en el admin, o se registra en `job_search_tracker.csv`.
5. **Mejorar** — `/upskill` compara ofertas vs. perfil e identifica qué aprender.

## Componentes clave

| Componente | Ubicación |
|-----------|-----------|
| Perfil del candidato (datos reales) | `.claude/skills/job-application-assistant/01-candidate-profile.md` |
| Framework de evaluación de fit | `.claude/skills/job-application-assistant/04-job-evaluation.md` |
| Plantillas CV / cover | `.claude/skills/job-application-assistant/05-cv-templates.md`, `06-cover-letter-templates.md` |
| Comandos slash | `.claude/commands/` |
| Scanner multi-fuente | `app/Console/Commands/ScanJobs.php` (`jobs:scan`) |
| Puente al workflow | `app/Console/Commands/ExportJobsForRank.php` (`jobs:export`) |
| Herramienta de salarios | `job-tools/salary_lookup.py` |

## Notas

- Los CLIs de portales daneses del repo original (Jobindex, Jobnet…) **no** se integraron: no aplican al mercado Colombia/remoto. El scanner `jobs:scan` (Remotive, RemoteOK, WeWorkRemotely, Jobicy, Himalayas) los reemplaza.
- El perfil ya está poblado con los datos reales del CV — `/apply` funciona sin correr `/setup`.
- La app corre en Docker (`docker-compose exec app php artisan …`).
