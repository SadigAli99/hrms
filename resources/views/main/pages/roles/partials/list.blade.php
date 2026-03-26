<table class="tbl">
    <thead>
        <tr>
            <th>#</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($roles as $role)
            <tr data-list-row data-crud-item data-search="{{ $role->name }}" data-delete-entity="{{ $role->name }}"
                data-delete-action="{{ route('role.destroy', $role->id) }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    @if ($role->status)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-red">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="table-actions">
                        <a class="icon-action-btn" href="{{ route('role.get-permission', $role->id) }}"
                            title="Assign permissions" aria-label="Assign permissions">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586A1 1 0 0113.293 3.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                        <a class="icon-action-btn" href="{{ route('role.edit', $role->id) }}" title="Edit"
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

@if ($roles instanceof \Illuminate\Contracts\Pagination\Paginator && $roles->hasPages())
    <div class="card">
        {{ $roles->links('vendor.pagination.hrms') }}
    </div>
@endif
