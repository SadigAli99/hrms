<div class="modal-overlay" data-crud-delete-modal>
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white">Diqqət</h2>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-crud-delete-close>X</button>
        </div>

        <form class="space-y-4" data-crud-delete-form method="post" action="">
            @csrf
            @method('DELETE')

            <div class="backend-hook page-note">
                Bu məlumatın silinməsindən əminsiniz mi? <strong data-crud-delete-entity>this user</strong>.
                <span data-crud-delete-message></span>
            </div>

            <div class="flex justify-end gap-3">
                <button class="btn-ghost" type="button" data-crud-delete-close>Xeyr</button>
                <button class="btn-danger btn-with-icon" type="submit">
                    <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-5 0a1 1 0 00-.894.553L7 7h10l-1.106-2.447A1 1 0 0015 4m-5 0h5" />
                    </svg>
                    <span>Bəli</span>
                </button>
            </div>
        </form>
    </div>
</div>
