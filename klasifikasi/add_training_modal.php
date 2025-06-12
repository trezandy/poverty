<?php
// Modal Tambah Data Training
?>
<div class="modal fade" id="addTrainingModal" tabindex="-1" aria-labelledby="addTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTrainingModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Data Training
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddTraining" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Kolom 1 -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Data Pribadi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="nik" name="nik" maxlength="16" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control" name="nama" onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || event.charCode === 32" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RT</label>
                                        <input type="text" class="form-control" name="rt" maxlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RW</label>
                                        <input type="text" class="form-control" name="rw" maxlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="laki-laki">Laki-laki</option>
                                            <option value="perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terakhir</label>
                                        <select class="form-select" name="pendidikan_terakhir" required>
                                            <option value="">Pilih Pendidikan</option>
                                            <option value="SD">SD</option>
                                            <option value="SLTP">SLTP</option>
                                            <option value="SLTA">SLTA</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <select class="form-select" name="pekerjaan" required>
                                            <option value="">Pilih Pekerjaan</option>
                                            <option value="Tani">Tani</option>
                                            <option value="Pedagang">Pedagang</option>
                                            <option value="Buruh">Buruh</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom 2 -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Kondisi Rumah</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tempat Tinggal</label>
                                        <select class="form-select" name="tempat_tinggal" required>
                                            <option value="">Pilih Tempat Tinggal</option>
                                            <option value="bebas sewa">Bebas Sewa</option>
                                            <option value="kontrakan">Kontrakan</option>
                                            <option value="milik sendiri">Milik Sendiri</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Dinding</label>
                                        <select class="form-select" name="dinding" required>
                                            <option value="">Pilih Dinding</option>
                                            <option value="anyaman bambu">Anyaman Bambu</option>
                                            <option value="kayu">Kayu</option>
                                            <option value="tembok">Tembok</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lantai</label>
                                        <select class="form-select" name="lantai" required>
                                            <option value="">Pilih Lantai</option>
                                            <option value="kayu">Kayu</option>
                                            <option value="semen/bata merah">Semen/Bata Merah</option>
                                            <option value="ubin">Ubin</option>
                                            <option value="keramik">Keramik</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Atap</label>
                                        <select class="form-select" name="atap" required>
                                            <option value="">Pilih Atap</option>
                                            <option value="anyaman bambu">Anyaman Bambu</option>
                                            <option value="seng">Seng</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sumber Air Minum</label>
                                        <select class="form-select" name="sumber_air_minum" required>
                                            <option value="">Pilih Sumber Air Minum</option>
                                            <option value="Sumur Bor/Pompa">Sumur Bor/Pompa</option>
                                            <option value="Sumur Terlindung">Sumur Terlindung</option>
                                            <option value="Air Isi Ulang">Air Isi Ulang</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sumber Air</label>
                                        <select class="form-select" name="sumber_air" required>
                                            <option value="">Pilih Sumber Air</option>
                                            <option value="Air Sungai">Air Sungai</option>
                                            <option value="PAM">PAM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom 3 -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Fasilitas & Keterangan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Sumber Penerangan</label>
                                        <select class="form-select" name="sumber_penerangan" required>
                                            <option value="">Pilih Sumber Penerangan</option>
                                            <option value="Bukan Listrik (Tanpa Meteran)">Bukan Listrik (Tanpa Meteran)</option>
                                            <option value="Listrik PLN (Tanpa Meteran)">Listrik PLN (Tanpa Meteran)</option>
                                            <option value="Listrik Non PLN (450 Watt)">Listrik Non PLN (450 Watt)</option>
                                            <option value="Listrik PLN (450 Watt)">Listrik PLN (450 Watt)</option>
                                            <option value="Listrik PLN (900 Watt)">Listrik PLN (900 Watt)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar Masak</label>
                                        <select class="form-select" name="bahan_bakar_masak" required>
                                            <option value="">Pilih Bahan Bakar Masak</option>
                                            <option value="Gas 3Kg">Gas 3Kg</option>
                                            <option value="Listrik">Listrik</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tempat BAB</label>
                                        <select class="form-select" name="tempat_bab" required>
                                            <option value="">Pilih Tempat BAB</option>
                                            <option value="Umum">Umum</option>
                                            <option value="Sendiri">Sendiri</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kloset</label>
                                        <select class="form-select" name="jenis_kloset" required>
                                            <option value="">Pilih Jenis Kloset</option>
                                            <option value="Tidak Pakai">Tidak Pakai</option>
                                            <option value="Cemplung/Cubluk">Cemplung/Cubluk</option>
                                            <option value="Leher Angsa">Leher Angsa</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Aset Bergerak</label>
                                        <select class="form-select" name="aset_bergerak" required>
                                            <option value="">Pilih Aset Bergerak</option>
                                            <option value="Tidak ada">Tidak ada</option>
                                            <option value="Ada">Ada</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <select class="form-select" name="keterangan" required>
                                            <option value="">Pilih Keterangan</option>
                                            <option value="Miskin">Miskin</option>
                                            <option value="Tidak Miskin">Tidak Miskin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 