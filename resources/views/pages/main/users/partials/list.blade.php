<table class="tbl">
    <thead>
        <tr>
            <th>#</th>
            <th>İstifadəçi</th>
            <th>Rol</th>
            <th>Departament</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr data-list-row data-crud-item data-search="{{ $user->name }}" data-delete-entity="{{ $user->name }}"
                data-delete-action="{{ route('user.destroy', $user->id) }}">
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar bg-gradient-to-br from-brand-600 to-brand-400 text-white text-xs">
                            {{ $user->short_name }}</div>
                        <div>
                            <div class="font-600 text-white text-sm">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-blue">{{ $user->roles->first()?->name ?? ' - ' }}</span>
                </td>
                <td class="text-slate-300">{{ $user->department?->name ?? ' - ' }}</td>
                <td>
                    @if ($user->status)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-red">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="table-actions">
                        <a class="icon-action-btn" href="{{ route('user.edit', $user->id) }}" title="Edit"
                            aria-label="Edit">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536M9 11l6.768-6.768a2.5 2.5 0 113.536 3.536L12.536 14.536A4 4 0 0110.707 15.95L7 17l1.05-3.707A4 4 0 019 11z" />
                            </svg>
                        </a>
                        <button class="icon-action-btn danger" type="button" data-crud-delete-trigger title="Delete"
                            aria-label="Delete">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-5 0a1 1 0 00-.894.553L7 7h10l-1.106-2.447A1 1 0 0015 4m-5 0h5" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">
                    <div class="py-8">
                        <div class="text-sm font-semibold text-white">Məlumat tapılmadı</div>
                        <div class="mt-2 text-xs text-slate-500">Filter və ya axtarış nəticəsinə uyğun rol yoxdur.</div>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($users instanceof \Illuminate\Contracts\Pagination\Paginator && $users->hasPages())
    <div class="card">
        {{ $users->links('vendor.pagination.hrms') }}
    </div>
@endif
