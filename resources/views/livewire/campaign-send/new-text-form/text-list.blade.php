@php
    $count = $this->getAllTableRecordsCount();
@endphp
<div>
    <div>
        <div class="px-5 py-4 rounded-lg border border-slate-200" x-data="{ open: false }">
            <button
                    class="flex items-center justify-between w-full group mb-1"
                    @click.prevent="open = !open"
                    :aria-expanded="open"
            >
                <div class="text-sm text-slate-800 font-medium">Total texts: {{$count}}</div>
                <svg class="w-8 h-8 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3" :class="{ 'rotate-180': open }" viewBox="0 0 32 32">
                    <path d="M16 20l-5.4-5.4 1.4-1.4 4 4 4-4 1.4 1.4z" />
                </svg>
            </button>
            <div class="text-sm mt-6" x-show="open" x-cloak>
                {{$this->table}}
            </div>
        </div>
    </div>

</div>