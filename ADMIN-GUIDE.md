# 🔐 Gestión de Evaluaciones y Aplicaciones

**Tu sistema está integrado en Laravel. Gestiona todo via Tinker (CLI).**

---

## 🚀 Acceso Rápido

### Abrir Tinker (CLI)
```bash
docker-compose exec app php artisan tinker
```

---

## 📊 Operaciones Comunes

### 1. **Ver todas las Evaluaciones**
```bash
App\Models\Evaluation::all();
```
**Salida:** Lista de 3 evaluaciones (Sur Global, Proxify, Blankfactor)

### 2. **Ver todas las Aplicaciones**
```bash
App\Models\Application::with('evaluation')->get();
```
**Salida:** 3 aplicaciones con sus evaluaciones vinculadas

### 3. **Vincular Evaluation ↔ Application** ← IMPORTANTE
```bash
# Opción A: Por ID
$app = App\Models\Application::find(1); // Sur Global application
$eval = App\Models\Evaluation::find(1); // Sur Global evaluation
$app->update(['evaluation_id' => $eval->id]);

# Opción B: Por nombre
$app = App\Models\Application::where('company', 'Sur Global')->first();
$eval = App\Models\Evaluation::where('company', 'Sur Global')->first();
$app->update(['evaluation_id' => $eval->id]);
```
**Resultado:** La aplicación accede a cover letter, summary, report

### 4. **Cambiar Status de una Aplicación**
```bash
$app = App\Models\Application::find(1);

# Cambiar a:
$app->update(['status' => 'Postulado']);      # Initial
$app->update(['status' => 'En Revisión']);    # Under review
$app->update(['status' => 'Entrevista']);     # Interview scheduled
$app->update(['status' => 'Oferta']);         # Offer received
$app->update(['status' => 'Rechazado']);      # Rejected
$app->update(['status' => 'Aceptado']);       # Accepted
```

### 5. **Ver documentos de una Evaluación**
```bash
$eval = App\Models\Evaluation::find(1);

# Ver todos
$eval->documents;

# Ver por tipo
$eval->coverLetter();    # Obtiene cover letter
$eval->summary();        # Obtiene summary
$eval->report();         # Obtiene full report

# Ver contenido completo
$eval->coverLetter()->content;
```

### 6. **Agregar notas a una Aplicación**
```bash
$app = App\Models\Application::find(1);
$app->update(['notes' => 'Nueva nota sobre esta postulación']);
```

### 7. **Crear nueva Aplicación**
```bash
App\Models\Application::create([
    'company' => 'Nueva Empresa',
    'position' => 'Laravel Developer',
    'application_date' => '2026-07-02',
    'status' => 'Postulado',
    'score' => '4.5/5',
    'link' => 'https://linkedin.com/jobs/...',
    'notes' => 'Tus notas iniciales',
    'evaluation_id' => null, // Se vincula después
]);
```

### 8. **Crear nueva Evaluación**
```bash
App\Models\Evaluation::create([
    'company' => 'Nueva Empresa',
    'position' => 'Senior Developer',
    'evaluation_date' => '2026-07-02',
    'score' => '4.0/5',
    'status' => 'evaluated',
    'jd_url' => 'https://linkedin.com/jobs/...',
]);
```

---

## 🔄 Flujo Típico de Gestión

### Scenario 1: Empresa nueva llega a tu bandeja
```bash
# Paso 1: Crea la evaluación
$eval = App\Models\Evaluation::create([
    'company' => 'TechCorp',
    'position' => 'Backend Engineer',
    'evaluation_date' => now()->toDateString(),
    'score' => '4.2/5',
]);

# Paso 2: Crea la aplicación
$app = App\Models\Application::create([
    'company' => 'TechCorp',
    'position' => 'Backend Engineer',
    'application_date' => now()->toDateString(),
    'status' => 'Postulado',
    'evaluation_id' => $eval->id,
]);

# Paso 3: Agrega notas
$app->update(['notes' => 'Good match, 100% remoto']);

# Paso 4: Verifica en web
# Abre: http://localhost:8118/applications
```

### Scenario 2: Avanza a entrevista
```bash
$app = App\Models\Application::where('company', 'TechCorp')->first();
$app->update(['status' => 'Entrevista']);
$app->update(['notes' => 'Interview scheduled for July 5th at 2pm']);

# ¡Aparece automáticamente en web!
```

---

## 🌐 Ver en la Web

Después de cambios en Tinker, abre:
```
http://localhost:8118/applications
```

**Verás:**
- ✅ Todas tus aplicaciones con status actualizado
- ✅ Scores mostrados como badges
- ✅ Botones 📄 (Cover) y 📊 (Summary) si hay documentos
- ✅ Todos los cambios se reflejan automáticamente

---

## ⚡ Atajos Útiles

### Obtener aplicación por empresa
```bash
$app = App\Models\Application::where('company', 'Sur Global')->first();
```

### Obtener todas las ofertas
```bash
App\Models\Application::where('status', 'Oferta')->get();
```

### Contar aplicaciones por estado
```bash
App\Models\Application::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->pluck('count', 'status');
```

### Score promedio
```bash
App\Models\Application::whereNotNull('score')
    ->selectRaw("AVG(CAST(REPLACE(score, '/5', '') AS DECIMAL(3,1))) as avg")
    ->value('avg');
```

---

## 🔗 Web Admin (Filament)

También existe un panel web Filament para otras secciones:
```
http://localhost:8118/admin
```

**Disponible:**
- 📁 Projects
- 🎯 Skills  
- 💼 Experience
- 🎓 Education
- 📚 Repositories

---

## 💾 Salir de Tinker

```bash
exit
```

---

**¡Listo!** Usa Tinker para gestionar todo. 🚀
