<div class="modal fade" role="dialog" id="createModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="label-modal"></span> Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="saveData" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" id="id">
                    <input type="hidden" id="user_id" value="{{ auth()->user()->id }}">
                    <div class="form-group">
                        <label for="id_buku" class="form-label">Buku<span class="text-danger">*</span></label>
                        <select name="id_buku" id="id_buku" class="form-control">
                        </select>
                        <small class="invalid-feedback" id="errorid_buku"></small>
                    </div>
                    <div class="form-group">
                        <label for="id_siswa" class="form-label">Peminjam<span class="text-danger">*</span></label>
                        <select name="id_siswa" id="id_siswa" class="form-control">
                        </select>
                        <small class="invalid-feedback" id="errorid_siswa"></small>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai" class="form-label">Tanggal Pengembalian<span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        <small class="invalid-feedback" id="errortanggal_selesai"></small>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
