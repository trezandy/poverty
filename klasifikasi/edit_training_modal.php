<?php
$detail_query = "SELECT * FROM data_training WHERE nik = ?";
$detail_stmt = $pdo->prepare($detail_query);
$detail_stmt->execute([$row['nik']]);
$detail = $detail_stmt->fetch();
?>
<div class="modal fade" id="editTrainingModal<?php echo $row['nik']; ?>" tabindex="-1" aria-labelledby="editTrainingModalLabel<?php echo $row['nik']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editTrainingModalLabel<?php echo $row['nik']; ?>"><i class="fas fa-edit"></i> Edit Data Training</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditTraining<?php echo $row['nik']; ?>" class="needs-validation" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="nik_lama" value="<?php echo $detail['nik']; ?>">
                    <div class="row g-3">
                        <!-- Kolom Data Pribadi -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user"></i> Data Pribadi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">NIK</label>
                                        <input type="text" class="form-control" name="nik" value="<?php echo $detail['nik']; ?>" required maxlength="16">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control" name="nama" value="<?php echo $detail['nama']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RT</label>
                                        <input type="text" class="form-control" name="rt" value="<?php echo $detail['rt']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RW</label>
                                        <input type="text" class="form-control" name="rw" value="<?php echo $detail['rw']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="jenis_kelamin" required>
                                            <option value="laki-laki" <?php echo ($detail['jenis_kelamin'] == 'laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                            <option value="perempuan" <?php echo ($detail['jenis_kelamin'] == 'perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terakhir</label>
                                        <select class="form-select" name="pendidikan_terakhir" required>
                                            <option value="SD" <?php echo ($detail['pendidikan_terakhir'] == 'SD') ? 'selected' : ''; ?>>SD</option>
                                            <option value="SLTP" <?php echo ($detail['pendidikan_terakhir'] == 'SLTP') ? 'selected' : ''; ?>>SLTP</option>
                                            <option value="SLTA" <?php echo ($detail['pendidikan_terakhir'] == 'SLTA') ? 'selected' : ''; ?>>SLTA</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <select class="form-select" name="pekerjaan" required>
                                            <option value="Tani" <?php echo ($detail['pekerjaan'] == 'Tani') ? 'selected' : ''; ?>>Tani</option>
                                            <option value="Pedagang" <?php echo ($detail['pekerjaan'] == 'Pedagang') ? 'selected' : ''; ?>>Pedagang</option>
                                            <option value="Buruh" <?php echo ($detail['pekerjaan'] == 'Buruh') ? 'selected' : ''; ?>>Buruh</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kondisi Rumah -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-home"></i> Kondisi Rumah</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tempat Tinggal</label>
                                        <select class="form-select" name="tempat_tinggal" required>
                                            <option value="bebas sewa" <?php echo ($detail['tempat_tinggal'] == 'bebas sewa') ? 'selected' : ''; ?>>Bebas Sewa</option>
                                            <option value="kontrakan" <?php echo ($detail['tempat_tinggal'] == 'kontrakan') ? 'selected' : ''; ?>>Kontrakan</option>
                                            <option value="milik sendiri" <?php echo ($detail['tempat_tinggal'] == 'milik sendiri') ? 'selected' : ''; ?>>Milik Sendiri</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Dinding</label>
                                        <select class="form-select" name="dinding" required>
                                            <option value="anyaman bambu" <?php echo ($detail['dinding'] == 'anyaman bambu') ? 'selected' : ''; ?>>Anyaman Bambu</option>
                                            <option value="kayu" <?php echo ($detail['dinding'] == 'kayu') ? 'selected' : ''; ?>>Kayu</option>
                                            <option value="tembok" <?php echo ($detail['dinding'] == 'tembok') ? 'selected' : ''; ?>>Tembok</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lantai</label>
                                        <select class="form-select" name="lantai" required>
                                            <option value="kayu" <?php echo ($detail['lantai'] == 'kayu') ? 'selected' : ''; ?>>Kayu</option>
                                            <option value="semen/bata merah" <?php echo ($detail['lantai'] == 'semen/bata merah') ? 'selected' : ''; ?>>Semen/Bata Merah</option>
                                            <option value="ubin" <?php echo ($detail['lantai'] == 'ubin') ? 'selected' : ''; ?>>Ubin</option>
                                            <option value="keramik" <?php echo ($detail['lantai'] == 'keramik') ? 'selected' : ''; ?>>Keramik</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Atap</label>
                                        <select class="form-select" name="atap" required>
                                            <option value="anyaman bambu" <?php echo ($detail['atap'] == 'anyaman bambu') ? 'selected' : ''; ?>>Anyaman Bambu</option>
                                            <option value="seng" <?php echo ($detail['atap'] == 'seng') ? 'selected' : ''; ?>>Seng</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sumber Air Minum</label>
                                        <select class="form-select" name="sumber_air_minum" required>
                                            <option value="Sumur Bor/Pompa" <?php echo ($detail['sumber_air_minum'] == 'Sumur Bor/Pompa') ? 'selected' : ''; ?>>Sumur Bor/Pompa</option>
                                            <option value="Sumur Terlindung" <?php echo ($detail['sumber_air_minum'] == 'Sumur Terlindung') ? 'selected' : ''; ?>>Sumur Terlindung</option>
                                            <option value="Air Isi Ulang" <?php echo ($detail['sumber_air_minum'] == 'Air Isi Ulang') ? 'selected' : ''; ?>>Air Isi Ulang</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sumber Air</label>
                                        <select class="form-select" name="sumber_air" required>
                                            <option value="Air Sungai" <?php echo ($detail['sumber_air'] == 'Air Sungai') ? 'selected' : ''; ?>>Air Sungai</option>
                                            <option value="PAM" <?php echo ($detail['sumber_air'] == 'PAM') ? 'selected' : ''; ?>>PAM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Fasilitas & Keterangan -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-clipboard-list"></i> Fasilitas & Keterangan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Sumber Penerangan</label>
                                        <select class="form-select" name="sumber_penerangan" required>
                                            <option value="Bukan Listrik (Tanpa Meteran)" <?php echo ($detail['sumber_penerangan'] == 'Bukan Listrik (Tanpa Meteran)') ? 'selected' : ''; ?>>Bukan Listrik (Tanpa Meteran)</option>
                                            <option value="Listrik PLN (Tanpa Meteran)" <?php echo ($detail['sumber_penerangan'] == 'Listrik PLN (Tanpa Meteran)') ? 'selected' : ''; ?>>Listrik PLN (Tanpa Meteran)</option>
                                            <option value="Listrik Non PLN (450 Watt)" <?php echo ($detail['sumber_penerangan'] == 'Listrik Non PLN (450 Watt)') ? 'selected' : ''; ?>>Listrik Non PLN (450 Watt)</option>
                                            <option value="Listrik PLN (450 Watt)" <?php echo ($detail['sumber_penerangan'] == 'Listrik PLN (450 Watt)') ? 'selected' : ''; ?>>Listrik PLN (450 Watt)</option>
                                            <option value="Listrik PLN (900 Watt)" <?php echo ($detail['sumber_penerangan'] == 'Listrik PLN (900 Watt)') ? 'selected' : ''; ?>>Listrik PLN (900 Watt)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar Masak</label>
                                        <select class="form-select" name="bahan_bakar_masak" required>
                                            <option value="Gas 3Kg" <?php echo ($detail['bahan_bakar_masak'] == 'Gas 3Kg') ? 'selected' : ''; ?>>Gas 3Kg</option>
                                            <option value="Listrik" <?php echo ($detail['bahan_bakar_masak'] == 'Listrik') ? 'selected' : ''; ?>>Listrik</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tempat BAB</label>
                                        <select class="form-select" name="tempat_bab" required>
                                            <option value="Umum" <?php echo ($detail['tempat_bab'] == 'Umum') ? 'selected' : ''; ?>>Umum</option>
                                            <option value="Sendiri" <?php echo ($detail['tempat_bab'] == 'Sendiri') ? 'selected' : ''; ?>>Sendiri</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kloset</label>
                                        <select class="form-select" name="jenis_kloset" required>
                                            <option value="Tidak Pakai" <?php echo ($detail['jenis_kloset'] == 'Tidak Pakai') ? 'selected' : ''; ?>>Tidak Pakai</option>
                                            <option value="Cemplung/Cubluk" <?php echo ($detail['jenis_kloset'] == 'Cemplung/Cubluk') ? 'selected' : ''; ?>>Cemplung/Cubluk</option>
                                            <option value="Leher Angsa" <?php echo ($detail['jenis_kloset'] == 'Leher Angsa') ? 'selected' : ''; ?>>Leher Angsa</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Aset Bergerak</label>
                                        <select class="form-select" name="aset_bergerak" required>
                                            <option value="Tidak ada" <?php echo ($detail['aset_bergerak'] == 'Tidak ada') ? 'selected' : ''; ?>>Tidak ada</option>
                                            <option value="Ada" <?php echo ($detail['aset_bergerak'] == 'Ada') ? 'selected' : ''; ?>>Ada</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <select class="form-select" name="keterangan" required>
                                            <option value="Miskin" <?php echo ($detail['keterangan'] == 'Miskin') ? 'selected' : ''; ?>>Miskin</option>
                                            <option value="Tidak Miskin" <?php echo ($detail['keterangan'] == 'Tidak Miskin') ? 'selected' : ''; ?>>Tidak Miskin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle"></i> Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div> 