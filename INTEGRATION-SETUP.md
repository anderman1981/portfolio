# 🚀 Integration Setup - All in One CV Project

Todo ahora está integrado en una **sola app Laravel**. Career-Ops + JobTracker + Portfolio en un solo lugar.

---

## ✅ Lo que se creó

### Modelos Eloquent
- `Evaluation` — Sistema de evaluación (Career-Ops)
- `Application` — Tracking de postulaciones (JobTracker)
- `Document` — Cover letters, summaries, reportes

### Migraciones
- `create_evaluations_table` — Evaluaciones A-G
- `create_applications_table` — Aplicaciones (linked a Evaluation)
- `create_documents_table` — Documentos (cover, summary, report)

### Componentes Livewire
- `JobApplications` — Widget principal con tabla y filtros
- `DocumentViewer` — Visor de documentos (cover/summary/report)

### Rutas
- `GET /` — Portfolio principal (existente)
- `GET /applications` — Tracker de aplicaciones (NEW)
- `GET /documents/{evaluation}/{type}` — Visor de documentos (NEW)

---

## 🛠️ Pasos de Instalación

### 1. **Ejecutar migraciones**

```bash
cd /Users/andersonmartinezrestrepo/DEV-PROJECTS/CV/portfolio

# Crear tablas
php artisan migrate

# Si necesitas rollback
# php artisan migrate:rollback
```

### 2. **Importar datos desde career-ops**

```bash
# Comando que importa evaluaciones y documentos
php artisan career-ops:import

# Verifica que se importaron:
# ✓ Created evaluation: Sur Global → Laravel Developer
# ◆ Updated evaluation: Proxify
# ✅ Import complete!
```

### 3. **Verificar que funciona**

Abre `http://localhost:8118/applications` en el navegador

Deberías ver:
- ✅ Tabla con todas tus aplicaciones
- ✅ Filtros por empresa/estado
- ✅ Botones 📄 (Cover) y 📊 (Summary)
- ✅ Al hacer clic, se abre el documento

### 4. **Ver documentos**

Haz clic en el botón 📄 o 📊 de cualquier aplicación

Verás:
- ✅ Documento renderizado con estilos
- ✅ Botón de impresión (Cmd+P)
- ✅ Links funcionales

---

## 📊 Estructura de Datos

```
Evaluation
├── id: 1
├── company: "Sur Global"
├── position: "Laravel Developer"
├── score: "4.8/5"
├── status: "evaluated"
├── evaluation_date: "2026-07-02"
├── requirements: {...}  # JSON parseable
├── match_analysis: {...}  # Block B
├── interview_prep: {...}  # Block F (STAR stories)
│
└── Application (hasMany)
    ├── id: 1
    ├── status: "En Revisión"
    ├── link: "https://linkedin.com/jobs/..."
    ├── notes: "Match excepcional..."
    │
    └── Documents (hasMany via Evaluation)
        ├── type: "cover"
        ├── content: "# Cover Letter markdown..."
        │
        └── type: "summary"
            └── content: "# Executive Summary..."
```

---

## 🔄 Flujo de Trabajo Completo

### A. Evalúa en Career-Ops
```bash
cd /Users/andersonmartinezrestrepo/DEV-PROJECTS/CV/career-ops
claude
# /career-ops https://linkedin.com/jobs/...
```

### B. Importa a Portfolio
```bash
cd portfolio
php artisan career-ops:import
```

### C. Mira en la web
Abre `http://localhost:8118/applications`

---

## 📝 Queries Útiles (Tinker)

```bash
php artisan tinker

# Ver todas las evaluaciones
Evaluation::all();

# Ver aplicaciones por estado
Application::where('status', 'Oferta')->get();

# Obtener cover letter
Evaluation::find(1)->coverLetter();

# Contar por estado
Application::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
```

---

## 🎨 Personalizar Componentes

### Cambiar estilos (Tailwind)
Archivo: `resources/views/livewire/job-applications.blade.php`

### Agregar filtros nuevos
En `app/Livewire/JobApplications.php`, agrega propiedades públicas y actualiza el query

### Cambiar orden de columnas
Edita la tabla en `job-applications.blade.php`

---

## ⚠️ Troubleshooting

### No se importan documentos
1. Verifica que `career-ops/output/` existen los archivos
2. Verifica que los paths en `data/applications.md` son correctos
3. Ejecuta `php artisan career-ops:import` de nuevo

### Componente no aparece
1. Verifica que `npm run build` se ejecutó (Tailwind)
2. Limpia cache: `php artisan view:clear`
3. Verifica que las rutas estén en `routes/web.php`

### Documentos sin estilos
1. Ejecuta `npm run build` para compilar Tailwind
2. Limpia cache del navegador (Cmd+Shift+R)

---

## 🚀 Próximos Pasos

### Phase 2: APIs (opcional)
Si quieres APIs REST públicas:
- `GET /api/applications`
- `GET /api/documents/:id/:type`
- `POST /api/sync` — Sincronización automática

### Phase 3: Dashboard
Agregar gráficos de estadísticas:
- Score promedio
- Tendencia de estados
- Timeline de eventos

### Phase 4: Deploy a Hostinger
1. Copiar código a servidor
2. Ejecutar migraciones
3. Importar datos
4. ¡Listo!

---

**¡Integración completa!** Una sola app, todo en un lugar. 🎯
