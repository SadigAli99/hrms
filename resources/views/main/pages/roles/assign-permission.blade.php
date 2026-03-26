@extends('main.layouts.master')

@section('title', 'İcazə mənimsət')

@section('content')
    <div class="page-block space-y-6">

        <div class="grid grid-cols-4 gap-4">
            <div class="card col-span-1">
                <div class="vacancy-section-header">
                    <div>
                        <div class="text-base font-display font-700 text-white">Roles</div>
                        <div class="text-xs text-slate-500">Select role to edit its permission set.</div>
                    </div>
                </div>
                <div class="space-y-2">
                    @foreach ($roles as $item)
                        @if ($item->id == $role->id)
                            <button type="button"
                                class="block w-full text-left p-3 rounded-xl border border-brand-500/30 bg-brand-500/10 text-white">
                                {{ $item->name }}
                            </button>
                        @else
                            <a href="{{ route('role.get-permission', $item->id) }}"
                                class="block w-full text-left p-3 rounded-xl border border-white/5 bg-surface-700 text-slate-300 transition-colors hover:border-brand-500/20 hover:bg-surface-600 hover:text-white">
                                {{ $item->name }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="card col-span-3">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <div class="text-base font-display font-700 text-white">Permission Matrix</div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-ghost text-xs" type="button" data-check-all-target="#rolePermissionForm">Grant
                            all</button>
                        <button class="btn-ghost text-xs" type="button" data-clear-all-target="#rolePermissionForm">Clear
                            all</button>
                    </div>
                </div>

                <form method="post" action="{{ route('role.assign-permission', $role->id) }}" id="rolePermissionForm">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            @foreach ($permissionGroups as $group => $permissions)
                                <div class="text-xs uppercase tracking-wider text-slate-500 mb-3">{{ $group }}</div>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach ($permissions as $permission)
                                        <label class="analysis-option"><input type="checkbox" name="permissions[]"
                                                value="{{ $permission['id'] }}"
                                                {{ in_array($permission['id'], $rolePermissions) ? 'checked' : '' }}>
                                            <div><strong>{{ $permission['name'] }}</strong></div>
                                        </label>
                                    @endforeach

                                </div>
                            @endforeach


                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-5">
                        <a href="{{ route('role.index') }}" class="btn-ghost" type="button">Geri</a>
                        <button class="btn-primary" type="submit">Yadda saxla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/crud-actions.js') }}"></script>
@endpush
