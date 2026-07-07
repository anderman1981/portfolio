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
                        <p class="text-xs text-blue-100">Gemini 2.0 Flash</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-slate-50/50" id="chat-messages">
            @foreach($messages as $msg)
            <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} items-end gap-2">
                @if($msg['role'] !== 'user')
                <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 mb-0.5">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                @endif
                <div class="max-w-[78%] px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed
                    {{ $msg['role'] === 'user'
                        ? 'bg-blue-600 text-white rounded-br-sm'
                        : 'bg-white text-slate-700 border border-slate-200/80 shadow-sm rounded-bl-sm' }}">
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

        {{-- Input --}}
        <form wire:submit.prevent="ask" class="px-4 py-3 border-t border-slate-200/80 bg-white flex gap-2 flex-shrink-0">
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
    </div>
</div>
