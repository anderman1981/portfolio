<x-filament-panels::page>
    <div style="display:flex; gap:16px; min-height:65vh; flex-wrap:wrap;">

        {{-- Conversations list --}}
        <div style="flex:1 1 300px; max-width:360px; max-height:72vh; overflow-y:auto; display:flex; flex-direction:column; gap:8px;">
            @forelse($this->conversations as $c)
                @php $active = $selectedSession === $c['session_id']; @endphp
                <button wire:click="selectConversation('{{ $c['session_id'] }}')"
                        style="text-align:left; padding:12px; border-radius:10px; cursor:pointer; border:1px solid {{ $active ? '#f59e0b' : '#e5e7eb' }}; background:{{ $active ? '#fffbeb' : '#fff' }};">
                    <div style="display:flex; justify-content:space-between; gap:8px; align-items:center;">
                        <span style="font-weight:600; font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $c['name'] }}</span>
                        <span style="font-size:10px; color:#9ca3af; flex-shrink:0;">{{ \Carbon\Carbon::parse($c['last_at'])->diffForHumans(short: true) }}</span>
                    </div>
                    <div style="font-size:12px; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $c['email'] }}</div>
                    <div style="font-size:12px; color:#9ca3af; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:4px;">{{ $c['preview'] }}</div>
                    <div style="font-size:10px; color:#9ca3af; margin-top:4px;">{{ $c['total'] }} mensajes</div>
                </button>
            @empty
                <p style="font-size:13px; color:#9ca3af; padding:16px; text-align:center;">Aún no hay conversaciones con mensajes.</p>
            @endforelse
        </div>

        {{-- Selected conversation --}}
        <div style="flex:2 1 420px; display:flex; flex-direction:column; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; min-height:65vh;">
            @if($selectedSession)
                <div style="padding:12px 16px; border-bottom:1px solid #e5e7eb; background:#f9fafb;">
                    <div style="font-weight:600; font-size:13px; color:#111827;">{{ $this->lead['name'] }}</div>
                    <div style="font-size:12px; color:#6b7280;">{{ $this->lead['email'] }}</div>
                </div>

                <div style="flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:12px; background:#fafafa; max-height:52vh;" wire:poll.8s>
                    @foreach($this->messages as $m)
                        @php $isUser = $m->role === 'user'; $isOperator = ($m->source ?? '') === 'operator'; @endphp
                        <div style="display:flex; justify-content:{{ $isUser ? 'flex-start' : 'flex-end' }};">
                            <div style="max-width:75%; padding:8px 12px; border-radius:14px; font-size:13px; line-height:1.5;
                                {{ $isUser
                                    ? 'background:#fff; border:1px solid #e5e7eb; color:#111827;'
                                    : ($isOperator ? 'background:#10b981; color:#fff;' : 'background:#f59e0b; color:#fff;') }}">
                                @if($isOperator)<span style="display:block; font-size:10px; font-weight:700; opacity:.85; margin-bottom:2px;">Tú (operador)</span>@endif
                                @if(!$isUser && !$isOperator)<span style="display:block; font-size:10px; font-weight:700; opacity:.85; margin-bottom:2px;">Asistente IA</span>@endif
                                {{ $m->content }}
                                <span style="display:block; font-size:9px; opacity:.6; margin-top:2px;">{{ $m->created_at->format('d/m H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form wire:submit.prevent="sendReply" style="padding:12px; border-top:1px solid #e5e7eb; display:flex; gap:8px;">
                    <input type="text" wire:model="reply" placeholder="Responder como operador..."
                           style="flex:1; border:1px solid #d1d5db; border-radius:8px; font-size:13px; padding:8px 12px;">
                    <x-filament::button type="submit" size="sm">Enviar</x-filament::button>
                </form>
            @else
                <div style="flex:1; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:13px; padding:32px; text-align:center;">
                    Selecciona una conversación de la izquierda para ver el historial y responder.
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
