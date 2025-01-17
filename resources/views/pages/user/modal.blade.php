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
                        <label for="image" class="form-label">Foto </label>
                        <input type="file" name="image" id="image" class="dropify" data-height="200">
                        <small class="invalid-feedback" id="errorimage"></small>
                    </div>
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama">
                        <small class="invalid-feedback" id="errornama"></small>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email">
                        <small class="invalid-feedback" id="erroremail"></small>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control" name="password">
                            <div class="input-group-append">
                                <a class="btn bg-white d-flex justify-content-center align-items-center border"
                                    onclick="togglePasswordVisibility('#password', '#toggle-password'); event.preventDefault();">
                                    <i id="toggle-password" class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <small class="text-danger" id="errorpassword"></small>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            <div class="input-group-append">
                                <a class="btn bg-white d-flex justify-content-center align-items-center border"
                                    onclick="togglePasswordVisibility('#password_confirmation', '#toggle-password-confirmation'); event.preventDefault();">
                                    <i id="toggle-password-confirmation" class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <small class="invalid-feedback" id="errorpassword_confirmation"></small>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-control">
                            <option value=""> -- Pilih Role --</option>
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <small class="invalid-feedback" id="errorrole"></small>
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
