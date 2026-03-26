@extends('layouts.master')

@section('title', 'İstifadəçi əlavə et')

@section('content')
    <div class="page-block">
        <form id="vacancyForm" class="space-y-6" method="post" action="{{ route('user.store') }}">
            @csrf
            @if (session('error_message'))
                <div class="card border border-red-500/20 bg-red-500/10">
                    <div class="text-sm font-semibold text-red-300">{{ session('error_message') }}</div>
                </div>
            @endif

            <div class="card">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label" for="name">İstifadəçi</label>
                        <input class="input @error('name') border-red-500/40 focus:border-red-500 @enderror" id="name"
                            name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="email">Email</label>
                        <input class="input @error('email') border-red-500/40 focus:border-red-500 @enderror" id="email"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="status">Rol</label>
                        <select class="input @error('role_id') border-red-500/40 focus:border-red-500 @enderror"
                            id="role_id" name="role_id">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') === $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="password">Parol</label>
                        <div class="relative">
                            <input type="password"
                                class="input pr-12 @error('password') border-red-500/40 focus:border-red-500 @enderror"
                                id="password" name="password">
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 transition-colors hover:text-white"
                                type="button" data-password-toggle data-target="password" aria-label="Show password">
                                <svg data-password-icon="hidden" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg data-password-icon="visible" class="hidden w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.234-3.592M6.223 6.223A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.132 5.411M15 12a3 3 0 00-4.243-2.829M9.88 9.88A3 3 0 0014.12 14.12M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="status">Status</label>
                        <select class="input @error('status') border-red-500/40 focus:border-red-500 @enderror"
                            id="status" name="status">
                            <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Aktiv
                            </option>
                            <option value="0" {{ (string) old('status') === '0' ? 'selected' : '' }}>Deaktiv</option>
                        </select>
                        @error('status')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a class="btn-ghost" href="{{ route('user.index') }}">Geri</a>
                <button class="btn-primary" type="submit">Yadda saxla</button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/password-toggle.js') }}"></script>
@endpush
