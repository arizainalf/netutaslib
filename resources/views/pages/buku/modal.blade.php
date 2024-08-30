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
                    <div class="form-group">
                        <label for="image" class="form-label">Gambar </label>
                        <input type="file" name="image" id="image" class="dropify" data-height="200">
                        <small class="text-danger" id="errorimage"></small>
                    </div>
                    <div class="form-group">
                        <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul" name="judul">
                        <small class="invalid-feedback" id="errorjudul"></small>
                    </div>
                    <div class="form-group">
                        <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="penulis" name="penulis">
                        <small class="invalid-feedback" id="errorpenulis"></small>
                    </div>
                    <div class="form-group">
                        <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit">
                        <small class="invalid-feedback" id="errorpenerbit"></small>
                    </div>
                    <div class="form-group">
                        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tahun" name="tahun">
                        <small class="invalid-feedback" id="errortahun"></small>
                    </div>
                    <div class="form-group">
                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stok" name="stok">
                        <small class="invalid-feedback" id="errorstok"></small>
                    </div>
                    <div class="form-group">
                        <label for="category_id" class="form-label">Kategori<span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control">
                        </select>
                        <small class="invalid-feedback" id="errorcategory_id"></small>
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
