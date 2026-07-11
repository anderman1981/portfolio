# CV Chatbot → Slack Bridge

Puente entre el chatbot del CV y Slack `#cv`, vía n8n. El chatbot **solo envía**; n8n rutea; Slack es tu bandeja de operador.

## Flujo

```
Chatbot CV (visitante)
   │ POST payload  →  CV_BRIDGE_WEBHOOK (n8n)
   ▼
n8n Webhook  →  formatea  →  Slack #cv  (SLACK_WEBHOOK_CV)
   ▲                              │
   │                        respondes en un HILO
   └── POST /api/chat-reply ◄── n8n (Slack trigger)
          │
   el chatbot hace polling (cada 6s) y muestra tu respuesta
```

## Lo que ya está construido (lado CV / Laravel)

| Pieza | Ubicación |
|-------|-----------|
| Servicio que envía el payload | `app/Services/ChatBridgeService.php` |
| Hook en el chatbot | `app/Livewire/AIChat.php` (`ask()`) |
| Endpoint de vuelta | `POST /api/chat-reply` → `ChatReplyController` |
| Polling de respuestas | `pollReplies()` + `wire:poll.6s` en el blade |
| Config | `config/services.php` → `cv_bridge` |

### Payload que envía el chatbot
```json
{
  "source": "home_chatbot",
  "channel": "cv",
  "intent": "cv | review",
  "conversation_id": "<session id>",
  "user_name": "...",
  "user_email": "...",
  "message": "...",
  "page_url": "...",
  "timestamp": "ISO-8601"
}
```

## Configuración (`.env`)

```env
CV_BRIDGE_WEBHOOK=http://localhost:12340/webhook/cv-chat   # URL del webhook n8n
CV_BRIDGE_TOKEN=<un-secreto-compartido>                    # auth del reply-intake
SLACK_CHANNEL_CV=cv
```

En **n8n** define dos variables de entorno:
```
SLACK_WEBHOOK_CV = https://hooks.slack.com/services/XXX/YYY/ZZZ   (tu Incoming Webhook de #cv)
CV_REPLY_URL     = http://host.docker.internal:8118/api/chat-reply
CV_BRIDGE_TOKEN  = <el mismo secreto del .env de Laravel>
```

## Puesta en marcha

### 1. Crear el Slack Incoming Webhook
`api.slack.com/apps` → tu app → **Incoming Webhooks** → activar → **Add New Webhook** → elige `#cv` → copia la URL.

### 2. Importar el workflow de n8n
En n8n (`localhost:12340`): **Import from File** → `docs/n8n-cv-bridge.json`.
- El nodo *Post to Slack* usa `{{ $env.SLACK_WEBHOOK_CV }}`.
- Activa el workflow. La URL del webhook queda en `http://localhost:12340/webhook/cv-chat` (o `/webhook-test/...` mientras pruebas).

### 3. Poner el token y la URL en `.env`
Genera un secreto y ponlo en `CV_BRIDGE_TOKEN` (Laravel) y en n8n. Ajusta `CV_BRIDGE_WEBHOOK` a la URL real del webhook de n8n. Luego:
```bash
docker-compose exec app php artisan config:clear
```

### 4. Vuelta (Slack → visitante)
Añade en el workflow un **Slack Trigger** (evento: mensaje en `#cv`, en hilos):
- Extrae el `conversation_id` del mensaje padre del hilo (viene en el texto: `conversation_id: ...`).
- Nodo **HTTP Request** → `POST {{ $env.CV_REPLY_URL }}` con header `X-Bridge-Token: {{ $env.CV_BRIDGE_TOKEN }}` y body:
  ```json
  { "conversation_id": "<extraído>", "message": "<tu respuesta>" }
  ```
- El chatbot lo muestra en ~6s (badge verde "Anderson").

> El Slack Trigger requiere conectar tu app de Slack en n8n (OAuth). Es el único paso que necesita permisos de Slack; el resto funciona solo con el Incoming Webhook.

## Notas
- **Local-first:** todo se configura desde `.env`, sin IDs de canal hardcodeados.
- **Nunca rompe el chat:** si el bridge falla, el chatbot sigue respondiendo (el envío es fire-and-forget).
- **Clasificación:** mensajes sobre CV/empleo/entrevista → `cv`; el resto → `review` (igual va a Slack para revisión manual).
- El chatbot mantiene su respuesta automática de IA (Gemini) **y** te avisa en Slack para seguimiento humano — bridge, no silo.
