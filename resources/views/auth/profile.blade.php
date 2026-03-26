@extends('main.layouts.master')

@section('title', 'Profil')

@section('content')
    <div class="page-block">
        <form class="space-y-6" method="post" action="{{ route('update.profile') }}" enctype="multipart/form-data">
            @csrf

            @if (session('success_message'))
                <div class="card border border-green-500/20 bg-green-500/10">
                    <div class="text-sm font-semibold text-green-300">{{ session('success_message') }}</div>
                </div>
            @endif

            @if (session('error_message'))
                <div class="card border border-red-500/20 bg-red-500/10">
                    <div class="text-sm font-semibold text-red-300">{{ session('error_message') }}</div>
                </div>
            @endif

            <div class="card">
                <div class="vacancy-section-header">
                    <div>
                        <div class="text-base font-display font-700 text-white">Profil məlumatları</div>
                        <div class="text-xs text-slate-500">Ad, email, profil şəkli və şifrəni yenilə.</div>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="label" for="image">Profil şəkli</label>
                        <div class="rounded-2xl border border-dashed border-brand-500/30 bg-surface-700/60 p-4">
                            <input id="image" class="hidden" name="image" type="file" accept="image/*"
                                data-profile-image-input>
                            <label
                                class="flex cursor-pointer items-center gap-4 rounded-xl border border-white/5 bg-surface-700 p-4 transition-colors hover:border-brand-500/30"
                                for="image">
                                <span
                                    class="relative flex h-[76px] w-[76px] items-center justify-center overflow-hidden rounded-2xl border border-brand-500/20 bg-brand-500/10 text-xs font-semibold text-white">
                                    <img src="{{ asset(auth()->user()->image) }}" alt="Profile preview"
                                        class="{{ empty(auth()->user()->image) ? 'hidden' : '' }} h-full w-full object-cover"
                                        data-profile-image-preview>
                                    <span data-profile-image-placeholder
                                        class="{{ empty(auth()->user()->image) ? '' : 'hidden' }}">Upload</span>
                                    <button
                                        class="{{ empty(auth()->user()->image) ? 'hidden' : 'inline-flex' }} absolute -right-2 -top-2 h-8 w-8 items-center justify-center rounded-full border border-danger/30 bg-danger/30 text-white transition-colors hover:bg-danger/50"
                                        type="button" data-profile-image-delete-trigger title="Şəkli sil">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-5 0a1 1 0 00-.894.553L7 7h10l-1.106-2.447A1 1 0 0015 4m-5 0h5" />
                                        </svg>
                                    </button>
                                </span>
                                <span class="flex flex-col gap-1">
                                    <strong class="text-sm text-white">Şəkil seç</strong>
                                    <span class="text-xs text-slate-500">JPG, PNG və ya WEBP yükləyə bilərsən.</span>
                                </span>
                            </label>
                            <div class="mt-3 text-xs text-slate-500" data-profile-image-name>No file selected</div>
                        </div>
                    </div>

                    <div>
                        <label class="label" for="name">Ad</label>
                        <input class="input" id="name" name="name" type="text"
                            value="{{ old('name', auth()->user()?->name) }}">
                    </div>

                    <div>
                        <label class="label" for="email">Email</label>
                        <input class="input" id="email" name="email" type="email"
                            value="{{ old('email', auth()->user()?->email) }}">
                    </div>

                    <div>
                        <label class="label" for="new_password">Yeni şifrə</label>
                        <div class="relative">
                            <input class="input pr-12" id="new_password" name="new_password" type="password"
                                placeholder="Yeni şifrə daxil et">
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 transition-colors hover:text-white"
                                type="button" data-password-toggle data-target="new_password" aria-label="Show password">
                                <svg data-password-icon="hidden" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg data-password-icon="visible" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.234-3.592M6.223 6.223A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.132 5.411M15 12a3 3 0 00-4.243-2.829M9.88 9.88A3 3 0 0014.12 14.12M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="label" for="repeat_password">Yeni şifrə təkrarı</label>
                        <div class="relative">
                            <input class="input pr-12" id="repeat_password" name="repeat_password" type="password"
                                placeholder="Yeni şifrəni təkrar et">
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 transition-colors hover:text-white"
                                type="button" data-password-toggle data-target="repeat_password"
                                aria-label="Show password">
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
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a class="btn-ghost" href="{{ route('dashboard') }}">Geri</a>
                <button class="btn-primary" type="submit">Yadda saxla</button>
            </div>
        </form>
    </div>

    <div class="modal-overlay" data-profile-image-delete-modal>
        <div class="modal">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-display font-700 text-xl text-white">Şəkli sil</h2>
                    <div class="text-xs text-slate-500 mt-1">Profil şəklini silmək istədiyindən əminsən?</div>
                </div>
                <button class="text-slate-500 hover:text-white transition-colors" type="button"
                    data-profile-image-delete-close>X</button>
            </div>

            <div class="space-y-4">

                <div class="flex justify-end gap-3">
                    <button class="btn-ghost" type="button" data-profile-image-delete-close>Ləğv et</button>
                    <button class="btn-danger btn-with-icon" type="button" data-profile-image-delete-confirm
                        data-delete-url="{{ route('delete.image') }}" data-delete-method="DELETE">
                        <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-5 0a1 1 0 00-.894.553L7 7h10l-1.106-2.447A1 1 0 0015 4m-5 0h5" />
                        </svg>
                        <span>Şəkli sil</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/auth-profile.js') }}"></script>
    <script src="{{ asset('assets/js/password-toggle.js') }}"></script>
@endpush
