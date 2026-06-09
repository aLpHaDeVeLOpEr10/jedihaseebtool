@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Page Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10"
         x-data="uuidTool()"
         x-init="generate()">

        {{-- Options Card --}}
        <div class="card p-6 mb-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

                {{-- Version --}}
                <div>
                    <label class="form-label">UUID Version</label>
                    <select x-model="version" class="form-input">
                        <option value="4">v4 — Random</option>
                        <option value="1">v1 — Timestamp</option>
                        <option value="7">v7 — Unix Epoch</option>
                    </select>
                </div>

                {{-- Count --}}
                <div>
                    <label class="form-label">How Many</label>
                    <input
                        type="number"
                        x-model.number="count"
                        min="1"
                        max="100"
                        class="form-input"
                        @keydown.enter="generate()"
                    >
                    <p class="form-help">Between 1 and 100</p>
                </div>

                {{-- Format --}}
                <div>
                    <label class="form-label">Case</label>
                    <select x-model="format" class="form-input">
                        <option value="lowercase">lowercase</option>
                        <option value="uppercase">UPPERCASE</option>
                    </select>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3">
                <button @click="generate()" class="btn btn-primary">
                    {{-- Refresh / generate icon --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Generate
                </button>

                <button
                    @click="copyAll()"
                    x-show="uuids.length > 0"
                    class="btn btn-secondary"
                >
                    <svg x-show="!copiedAll" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <svg x-show="copiedAll" class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="copiedAll ? 'Copied!' : 'Copy All'"></span>
                </button>

                <button
                    @click="clearAll()"
                    x-show="uuids.length > 0"
                    class="btn btn-secondary"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>

            <p x-show="error" x-text="error" class="form-error mt-3"></p>
        </div>

        {{-- UUID List --}}
        <div x-show="uuids.length > 0" x-transition>

            {{-- List Header --}}
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-600">
                    <span x-text="uuids.length"></span>
                    UUID<span x-show="uuids.length !== 1">s</span> generated
                </span>
                <div class="flex items-center gap-2">
                    <span class="badge badge-primary" x-text="'Version ' + version"></span>
                    <span class="badge badge-gray" x-text="format"></span>
                </div>
            </div>

            {{-- List Card --}}
            <div class="card overflow-hidden result-animate">
                <ul class="divide-y divide-gray-50">
                    <template x-for="(uuid, idx) in uuids" :key="idx">
                        <li class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group">

                            {{-- Row number --}}
                            <span
                                class="text-xs text-gray-300 w-5 text-right flex-shrink-0 font-mono"
                                x-text="idx + 1"
                            ></span>

                            {{-- UUID text --}}
                            <code
                                class="flex-1 font-mono text-sm text-gray-800 break-all select-all"
                                x-text="uuid"
                            ></code>

                            {{-- Per-row copy button (appears on hover) --}}
                            <button
                                @click.stop="copyOne(idx)"
                                class="flex-shrink-0 text-gray-300 hover:text-brand-600 transition-all opacity-0 group-hover:opacity-100 focus:opacity-100"
                                :aria-label="'Copy UUID ' + (idx + 1)"
                                title="Copy"
                            >
                                <svg x-show="copiedIdx !== idx" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <svg x-show="copiedIdx === idx" class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        {{-- Empty State --}}
        <div x-show="uuids.length === 0" class="card p-10 text-center text-gray-400">
            <div class="text-4xl mb-3">🆔</div>
            <p class="text-sm">Click <strong class="text-gray-500">Generate</strong> to create UUIDs instantly.</p>
        </div>

        {{-- Version Info Card --}}
        <div class="card p-6 mt-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">UUID Versions</h3>
            <div class="space-y-3 text-sm">

                <div class="flex items-start gap-3 p-3 rounded-xl"
                     :class="version === '4' ? 'bg-brand-50 border border-brand-100' : ''">
                    <span class="badge badge-primary flex-shrink-0 mt-0.5">v4</span>
                    <div>
                        <p class="font-medium text-gray-700">Random</p>
                        <p class="text-gray-500 text-xs mt-0.5">
                            122 random bits. The most widely used version — suitable for almost all use cases.
                            Uses <code class="text-brand-600">crypto.randomUUID()</code> where available.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-xl"
                     :class="version === '1' ? 'bg-brand-50 border border-brand-100' : ''">
                    <span class="badge badge-gray flex-shrink-0 mt-0.5">v1</span>
                    <div>
                        <p class="font-medium text-gray-700">Timestamp-based</p>
                        <p class="text-gray-500 text-xs mt-0.5">
                            Encodes the current time at 100 ns resolution since 15 Oct 1582.
                            The node is randomised (browsers cannot read MAC addresses).
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-xl"
                     :class="version === '7' ? 'bg-brand-50 border border-brand-100' : ''">
                    <span class="badge badge-success flex-shrink-0 mt-0.5">v7</span>
                    <div>
                        <p class="font-medium text-gray-700">Unix Epoch (RFC 9562)</p>
                        <p class="text-gray-500 text-xs mt-0.5">
                            48-bit millisecond Unix timestamp + 74 random bits. Monotonically increasing —
                            ideal for database primary keys and time-ordered sorting.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl">{{ $related->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $related->short_description }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- /tool body --}}
</div>
@endsection

@push('scripts')
<script>
/*
 * UUID Generator — v1, v4, v7.
 * Pure client-side. Uses the Web Crypto API for cryptographic randomness.
 * Nothing is ever sent to any server.
 */

/* ── UUID v4: 122 random bits ────────────────────────────────────────── */
function uuidV4() {
    /* Use the native API when available (all modern browsers) */
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    /* Fallback: build from random bytes */
    const b = crypto.getRandomValues(new Uint8Array(16));
    b[6] = (b[6] & 0x0f) | 0x40;   // version 4
    b[8] = (b[8] & 0x3f) | 0x80;   // variant 10xx
    return bytesToUuid(b);
}

/* ── UUID v1: 60-bit Gregorian timestamp + randomised node ───────────── */
function uuidV1() {
    /*
     * UUID epoch offset: 100-nanosecond intervals between
     * 15 October 1582 00:00 and 1 January 1970 00:00 (Unix epoch).
     */
    const OFFSET = 122192928000000000n;
    const now    = BigInt(Date.now()) * 10000n + OFFSET;   // 100 ns intervals

    const timeLow      = Number(now & 0xFFFF_FFFFn);
    const timeMid      = Number((now >> 32n) & 0xFFFFn);
    const timeHiAndVer = Number((now >> 48n) & 0x0FFFn) | 0x1000;   // version = 1

    /* 14-bit clock sequence with RFC 4122 variant bits (10xxxxxxxxxxxxxx) */
    const clkSeq = (crypto.getRandomValues(new Uint8Array(2))[0] << 8
                  | crypto.getRandomValues(new Uint8Array(1))[0])
                  & 0x3fff | 0x8000;

    /* 48-bit node — randomised (browsers cannot access the MAC address) */
    const node = crypto.getRandomValues(new Uint8Array(6));
    node[0] |= 0x01;   // multicast bit marks this as a locally generated node

    const h = n => n.toString(16);
    const pad = (n, len) => h(n).padStart(len, '0');
    const nodeHex = Array.from(node).map(b => pad(b, 2)).join('');

    return [
        pad(timeLow, 8),
        pad(timeMid, 4),
        pad(timeHiAndVer, 4),
        pad(clkSeq, 4),
        nodeHex
    ].join('-');
}

/* ── UUID v7: 48-bit Unix ms timestamp + 74 random bits (RFC 9562) ─── */
function uuidV7() {
    const b  = crypto.getRandomValues(new Uint8Array(16));
    const ms = BigInt(Date.now());

    /* Bytes 0–5: millisecond timestamp (big-endian) */
    b[0] = Number((ms >> 40n) & 0xFFn);
    b[1] = Number((ms >> 32n) & 0xFFn);
    b[2] = Number((ms >> 24n) & 0xFFn);
    b[3] = Number((ms >> 16n) & 0xFFn);
    b[4] = Number((ms >>  8n) & 0xFFn);
    b[5] = Number( ms         & 0xFFn);

    b[6] = (b[6] & 0x0f) | 0x70;   // version 7 in high nibble of byte 6
    b[8] = (b[8] & 0x3f) | 0x80;   // variant 10xx in byte 8

    return bytesToUuid(b);
}

/* Convert a 16-byte Uint8Array to a standard UUID string */
function bytesToUuid(b) {
    const h = Array.from(b).map(x => x.toString(16).padStart(2, '0'));
    return [
        h.slice(0,  4).join(''),
        h.slice(4,  6).join(''),
        h.slice(6,  8).join(''),
        h.slice(8,  10).join(''),
        h.slice(10, 16).join('')
    ].join('-');
}

/* ── Alpine.js component ─────────────────────────────────────────────── */
function uuidTool() {
    return {
        version:   '4',
        count:     1,
        format:    'lowercase',
        uuids:     [],
        error:     '',
        copiedAll: false,
        copiedIdx: -1,
        _timer:    null,

        generate() {
            const n = parseInt(this.count, 10);
            if (isNaN(n) || n < 1 || n > 100) {
                this.error = 'Count must be between 1 and 100.';
                return;
            }
            this.error = '';
            this.copiedAll = false;
            this.copiedIdx = -1;

            const generators = { '1': uuidV1, '4': uuidV4, '7': uuidV7 };
            const gen = generators[this.version] ?? uuidV4;

            this.uuids = Array.from({ length: n }, () => {
                const id = gen();
                return this.format === 'uppercase' ? id.toUpperCase() : id.toLowerCase();
            });
        },

        async copyAll() {
            if (!this.uuids.length) return;
            await this._writeClipboard(this.uuids.join('\n'));
            this.copiedAll = true;
            this._resetTimer(() => { this.copiedAll = false; });
        },

        async copyOne(idx) {
            await this._writeClipboard(this.uuids[idx]);
            this.copiedIdx = idx;
            this._resetTimer(() => { this.copiedIdx = -1; });
        },

        async _writeClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
            } catch {
                /* execCommand fallback for browsers that block clipboard access */
                const el = Object.assign(document.createElement('textarea'), {
                    value: text,
                    style: 'position:fixed;opacity:0;pointer-events:none'
                });
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
        },

        _resetTimer(fn) {
            clearTimeout(this._timer);
            this._timer = setTimeout(fn, 2000);
        },

        clearAll() {
            this.uuids     = [];
            this.error     = '';
            this.copiedAll = false;
            this.copiedIdx = -1;
        }
    };
}
</script>
@endpush
