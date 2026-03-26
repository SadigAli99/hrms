@extends('layouts.master')

@section('title', 'Departament əlavə et')

@section('content')
    <div class="page-block">
        <form id="vacancyForm" class="space-y-6" method="post" action="{{ route('department.store') }}">
            @csrf
            @if (session('error_message'))
                <div class="card border border-red-500/20 bg-red-500/10">
                    <div class="text-sm font-semibold text-red-300">{{ session('error_message') }}</div>
                </div>
            @endif

            <div class="card">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label" for="name">Departament</label>
                        <input class="input @error('name') border-red-500/40 focus:border-red-500 @enderror" id="name"
                            name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="status">Departament rəhbəri</label>
                        <select class="input @error('manager_user_id') border-red-500/40 focus:border-red-500 @enderror"
                            id="manager_user_id" name="manager_user_id">
                            <option value="">Departament rəhbəri</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('manager_user_id') === $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('manager_user_id')
                            <div class="mt-2 text-xs font-medium text-red-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="status">Bağlı olduğu şöbə</label>
                        <select class="input @error('parent_id') border-red-500/40 focus:border-red-500 @enderror"
                            id="parent_id" name="parent_id">
                            <option value="">Bağlı olduğu şöbə</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('parent_id') === $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
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
                <a class="btn-ghost" href="{{ route('department.index') }}">Geri</a>
                <button class="btn-primary" type="submit">Yadda saxla</button>
            </div>
        </form>
    </div>
@endsection
