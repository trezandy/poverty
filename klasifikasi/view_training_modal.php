<?php
// Modal View Training
// Kode PHP untuk mengambil detail data ($detail) harus sudah tersedia sebelum include file ini.
// Di klasifikasi.php, ini dilakukan di dalam loop while ($row = $stmt->fetch()).
?>
<div class="modal fade" id="viewTrainingModal<?php echo $row['nik']; ?>" tabindex="-1" aria-labelledby="viewTrainingModalLabel<?php echo $row['nik']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewTrainingModalLabel<?php echo $row['nik']; ?>"><i class="fas fa-eye"></i> Detail Data Training</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Pastikan variabel $detail sudah diambil sebelum file ini di-include
                // $detail = ... fetch data ...
                // Jika $detail belum ada (misal karena load data AJAX), Anda perlu mengambilnya di sini.
                // Namun, karena di klasifikasi.php data sudah diambil per baris, kita asumsikan $detail tersedia.
                if (!isset($detail) || $detail === null) {
                    // Fallback: Jika detail belum diambil, ambil data berdasarkan NIK dari $row
                    // Ini mungkin terjadi jika modal dipicu oleh JS tanpa pre-fetching data.
                    // Namun, dalam konteks loop di klasifikasi.php, $detail seharusnya sudah ada.
                    // Kode ini hanya sebagai jaga-jaga.
                    if (isset($pdo) && isset($row['nik'])) {
                        $fallback_detail_query = "SELECT * FROM data_training WHERE nik = ?";
                        $fallback_detail_stmt = $pdo->prepare($fallback_detail_query);
                        $fallback_detail_stmt->execute([$row['nik']]);
                        $detail = $fallback_detail_stmt->fetch();
                    }
                }
                ?>
                <?php if (isset($detail) && $detail !== false) { // Pastikan $detail berhasil diambil ?>
                <div class="row g-3">
                    <!-- Kolom Data Pribadi -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user"></i> Data Pribadi</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>NIK:</strong> <?php echo htmlspecialchars($detail['nik']); ?></p>
                                <p><strong>Nama:</strong> <?php echo htmlspecialchars(ucwords(strtolower($detail['nama']))); ?></p>
                                <p><strong>RT:</strong> <?php echo htmlspecialchars($detail['rt']); ?></p>
                                <p><strong>RW:</strong> <?php echo htmlspecialchars($detail['rw']); ?></p>
                                <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars(ucwords($detail['jenis_kelamin'])); ?></p>
                                <p><strong>Pendidikan Terakhir:</strong> <?php echo htmlspecialchars($detail['pendidikan_terakhir']); ?></p>
                                <p><strong>Pekerjaan:</strong> <?php echo htmlspecialchars($detail['pekerjaan']); ?></p>
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
                                <p><strong>Tempat Tinggal:</strong> <?php echo htmlspecialchars(ucwords($detail['tempat_tinggal'])); ?></p>
                                <p><strong>Dinding:</strong> <?php echo htmlspecialchars(ucwords($detail['dinding'])); ?></p>
                                <p><strong>Lantai:</strong> <?php echo htmlspecialchars(ucwords($detail['lantai'])); ?></p>
                                <p><strong>Atap:</strong> <?php echo htmlspecialchars(ucwords($detail['atap'])); ?></p>
                                <p><strong>Sumber Air Minum:</strong> <?php echo htmlspecialchars($detail['sumber_air_minum']); ?></p>
                                <p><strong>Sumber Air:</strong> <?php echo htmlspecialchars($detail['sumber_air']); ?></p>
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
                                <p><strong>Sumber Penerangan:</strong> <?php echo htmlspecialchars($detail['sumber_penerangan']); ?></p>
                                <p><strong>Bahan Bakar Masak:</strong> <?php echo htmlspecialchars($detail['bahan_bakar_masak']); ?></p>
                                <p><strong>Tempat BAB:</strong> <?php echo htmlspecialchars($detail['tempat_bab']); ?></p>
                                <p><strong>Jenis Kloset:</strong> <?php echo htmlspecialchars($detail['jenis_kloset']); ?></p>
                                <p><strong>Aset Bergerak:</strong> <?php echo htmlspecialchars($detail['aset_bergerak']); ?></p>
                                <p><strong>Keterangan:</strong>
                                    <span class="badge <?php echo ($detail['keterangan'] == 'Miskin') ? 'bg-danger' : 'bg-success'; ?>">
                                        <?php echo htmlspecialchars($detail['keterangan']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else { // Tampilkan pesan jika data tidak ditemukan ?>
                    <div class="alert alert-warning" role="alert">
                        Detail data tidak ditemukan untuk NIK ini.
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</button>
            </div>
        </div>
    </div>
</div> 