# 🔐 Admin Privado - CV + Job Applications

**Todo protegido. Solo visible para usuarios logueados en el admin panel.**

---

## 📍 Rutas Protegidas

### ❌ Público (antes)
- `http://localhost:8118/applications` — AHORA PRIVADO
- `http://localhost:8118/documents?evaluation=1&type=cover` — AHORA PRIVADO

### ✅ Privado (ahora, requiere auth)
```
http://localhost:8118/admin
  ├── Job Applications (NUEVO)
  ├── Experiences (editar CV)
  ├── Skills (editar skills)
  ├── Projects (editar proyectos)
  ├── Education (editar educación)
  └── Repositories (editar repos)
```

---

## 🚀 Cómo Usar

### 1. **Abre el Admin**
```
http://localhost:8118/admin
```

### 2. **Login (si es necesario)**
Usa credenciales de usuario admin (si no existen, créalas via Tinker)

### 3. **Gestiona tu CV**
En el panel verás todas las secciones:
- 📝 **Experiences** — Edita tu experiencia laboral
- 🎯 **Skills** — Gestiona tus habilidades
- 📁 **Projects** — Añade/edita tus proyectos
- 🎓 **Education** — Educación
- 📚 **Repositories** — Links a tus repos GitHub

### 4. **Gestiona Aplicaciones** (NUEVO)
- **Job Applications** — Sección nueva para gestionar postulaciones
  - Ver todas las aplicaciones
  - Cambiar status (Postulado → Entrevista → Oferta)
  - Vincular con evaluaciones
  - Agregar notas y links

---

## 🔐 Seguridad

**`/applications` y `/documents` ahora requieren autenticación.**

Si intentas acceder sin login:
```
GET http://localhost:8118/applications
→ 302 Redirect to /login (o donde esté tu login)
```

Solo usuarios autenticados en el admin pueden ver:
- ✅ Todas las aplicaciones
- ✅ Status de cada postulación
- ✅ Documentos (cover letter, summary, report)

---

## 📋 Campos Editables en Admin

### Job Applications
- ✏️ Company (nombre empresa)
- ✏️ Position (puesto)
- ✏️ Status (dropdown: Postulado, En Revisión, Entrevista, Oferta, etc)
- ✏️ Application Date (cuándo postulaste)
- ✏️ Score (puntuación)
- ✏️ Link (URL de la oferta)
- ✏️ Notes (tus notas privadas)
- 🔗 Linked Evaluation (vincula con evaluation)

### Experiences, Skills, Projects, Education
(Como siempre, edita directamente en sus secciones)

---

## 🔗 Cómo Vincular Evaluación con Aplicación (en Admin)

### Via Admin Web
1. Abre `/admin`
2. Ve a **Job Applications**
3. Edita una aplicación (ej: Sur Global)
4. En "Linked Evaluation" → Selecciona la evaluation correspondiente
5. Guarda

### Resultado
- ✅ Aplicación accede a documentos de la evaluación
- ✅ Hereda score automáticamente
- ✅ Puede mostrar cover letter, summary, report

---

## 🌐 Desde la Web Pública

**Ahora los usuarios públicos NO ven las postulaciones.**

Solo si están logueados en el admin pueden ir a:
```
http://localhost:8118/applications (privado, requiere auth)
```

Si tu CV no es un blog con múltiples usuarios, puedes:

**Opción A: Mantener privado**
- Solo tú lo ves en `/applications`
- Es tu tracking personal

**Opción B: Crear una página pública**
- Crear una ruta pública `/career` que muestre un resumen bonito
- Sin editar funcionalidades

---

## 🛡️ Autenticación en Filament

Si no puedes acceder al admin, crea un usuario:

```bash
docker-compose exec app php artisan tinker

# Crear usuario admin
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('tu-password'),
]);

exit
```

Luego login con esas credenciales.

---

## 📊 Flujo Completo

```
1. Abre /admin
2. Login con tu cuenta
3. Gestiona CV (Experiences, Skills, etc)
4. Gestiona Aplicaciones (Job Applications)
   - Ver todas
   - Cambiar status
   - Vincular evaluaciones
   - Agregar notas
5. Puedes ver /applications (privado) para ver tu tracker
6. Cierra sesión cuando termines
```

---

## 🚀 Resumen

✅ **Admin panel** → `http://localhost:8118/admin` (privado)
✅ **Job Tracker** → `http://localhost:8118/applications` (privado, solo auth)
✅ **Documents** → `http://localhost:8118/documents` (privado, solo auth)
✅ **CV público** → `http://localhost:8118/` (sigue siendo público)

**¡Listo para usar!** 🔒
