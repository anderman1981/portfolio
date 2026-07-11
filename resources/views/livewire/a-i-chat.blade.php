<div class="fixed bottom-6 right-6 z-50" x-data="{ open: false }">

    {{-- Trigger button --}}
    <button @click="open = !open"
            class="relative group bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-2xl shadow-xl shadow-blue-500/30 transition-all duration-200 hover:scale-105 flex items-center justify-center">
        {{-- Unread dot --}}
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full" x-show="!open"></span>
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Chat window --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="absolute bottom-18 right-0 w-[22rem] sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden flex flex-col h-[520px]"
         style="bottom: 72px;"
         x-cloak>

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4 text-white flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-tight">{{ __('portfolio.chat.title') }}</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                        <p class="text-xs text-blue-100">Asistente de Anderson</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Persona selector (choose the conversation style) --}}
        @if($leadCaptured)
        <div class="flex items-center gap-1.5 px-3 py-2 bg-slate-50 border-b border-slate-200/80 flex-shrink-0">
            <span class="text-[10px] text-slate-400 font-medium mr-0.5">Estilo:</span>
            @foreach($this->personaOptions() as $key => $opt)
            <button wire:click="setPersona('{{ $key }}')" title="{{ $opt['desc'] }}"
                    class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold transition
                    {{ $persona === $key ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-500 border border-slate-200 hover:border-blue-300' }}">
                <span>{{ $opt['emoji'] }}</span>{{ $opt['label'] }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Messages (polls for operator replies coming back from Slack) --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-slate-50/50" id="chat-messages" @if($leadCaptured) wire:poll.6s="pollReplies" @endif>
            @unless($leadCaptured)
            <div class="flex justify-start items-end gap-2">
                <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 mb-0.5">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <div class="max-w-[78%] px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed bg-white text-slate-700 border border-slate-200/80 shadow-sm rounded-bl-sm">
                    ¡Hola! 👋 Soy el asistente de Anderson. Antes de empezar, déjame tus datos para poder ayudarte mejor.
                </div>
            </div>
            @endunless
            @foreach($messages as $msg)
            @php $isOperator = ($msg['source'] ?? 'app') === 'operator'; @endphp
            <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} items-end gap-2">
                @if($msg['role'] !== 'user')
                <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 mb-0.5 {{ $isOperator ? 'bg-emerald-100' : 'bg-blue-100' }}">
                    @if($isOperator)
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @else
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    @endif
                </div>
                @endif
                <div class="max-w-[78%] px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed
                    {{ $msg['role'] === 'user'
                        ? 'bg-blue-600 text-white rounded-br-sm'
                        : ($isOperator
                            ? 'bg-emerald-50 text-slate-700 border border-emerald-200 shadow-sm rounded-bl-sm'
                            : 'bg-white text-slate-700 border border-slate-200/80 shadow-sm rounded-bl-sm') }}">
                    @if($isOperator)<span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wide mb-0.5">Anderson</span>@endif
                    {{ $msg['content'] }}
                </div>
            </div>
            @endforeach

            @if($is_loading)
            <div class="flex justify-start items-end gap-2">
                <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl rounded-bl-sm px-4 py-3 flex gap-1.5">
                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce [animation-delay:0ms]"></span>
                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce [animation-delay:150ms]"></span>
                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-bounce [animation-delay:300ms]"></span>
                </div>
            </div>
            @endif
        </div>

        {{-- Lead capture form (name + email; extra fields for corporate emails) --}}
        @unless($leadCaptured)
        <form wire:submit.prevent="startChat" class="px-4 py-3 border-t border-slate-200/80 bg-white flex flex-col gap-2 flex-shrink-0 max-h-[60%] overflow-y-auto">
            <input type="text" wire:model="name" placeholder="Tu nombre"
                   class="w-full bg-slate-100 border-0 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
            @error('name') <span class="text-xs text-red-500 px-1">{{ $message }}</span> @enderror
            <input type="email" wire:model.blur="email" placeholder="Tu email"
                   class="w-full bg-slate-100 border-0 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
            @error('email') <span class="text-xs text-red-500 px-1">{{ $message }}</span> @enderror

            {{-- Corporate / recruiter path: reveal extra fields --}}
            @if($this->isCorporate)
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-2.5 flex flex-col gap-2">
                <p class="text-[11px] font-semibold text-blue-700">🏢 Detectamos un correo corporativo — cuéntanos un poco más</p>
                <input type="text" wire:model="company" placeholder="Empresa"
                       class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500">
                @error('company') <span class="text-xs text-red-500 px-1">{{ $message }}</span> @enderror
                <select wire:model="intent"
                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500">
                    <option value="">¿Qué buscas?</option>
                    <option value="Vacante / posición">Tengo una vacante</option>
                    <option value="Proyecto / servicio">Un proyecto o servicio</option>
                    <option value="Validar experiencia">Validar experiencia de Anderson</option>
                    <option value="Otro">Otro</option>
                </select>
                @error('intent') <span class="text-xs text-red-500 px-1">{{ $message }}</span> @enderror
            </div>
            @endif

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors">
                Iniciar chat
            </button>
            <p class="text-[10px] text-slate-400 text-center">Tus datos solo se usan para responderte.</p>
        </form>
        @else
        {{-- Attachment upload progress --}}
        <div wire:loading wire:target="attachment" class="px-4 py-1.5 text-[11px] text-blue-600 bg-blue-50 border-t border-blue-100">Subiendo documento…</div>
        @error('attachment') <div class="px-4 py-1.5 text-[11px] text-red-500 bg-red-50 border-t border-red-100">{{ $message }}</div> @enderror

        {{-- Input --}}
        <form wire:submit.prevent="ask" class="px-4 py-3 border-t border-slate-200/80 bg-white flex gap-2 flex-shrink-0 items-center">
            {{-- Attach document / image --}}
            <label class="cursor-pointer text-slate-400 hover:text-blue-600 transition-colors flex-shrink-0" title="Adjuntar documento o imagen">
                <input type="file" wire:model="attachment" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp,.txt,.xlsx,.csv">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            </label>
            <input type="text"
                   wire:model="question"
                   placeholder="{{ __('portfolio.chat.placeholder') }}"
                   autocomplete="off"
                   {{ $is_loading ? 'disabled' : '' }}
                   class="flex-1 bg-slate-100 border-0 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors disabled:opacity-60">
            <button type="submit"
                    {{ $is_loading ? 'disabled' : '' }}
                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white w-10 h-10 rounded-xl flex items-center justify-center transition-colors flex-shrink-0">
                <svg class="w-4 h-4 rotate-90" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
            </button>
        </form>
        @endunless
    </div>
</div>
