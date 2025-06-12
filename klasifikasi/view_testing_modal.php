<?php
// Modal View Testing
// Kode PHP untuk mengambil detail data ($detail_testing) harus sudah tersedia sebelum include file ini.
// Di klasifikasi.php, ini dilakukan di dalam loop while ($row = $stmt->fetch()).
?>
<div class="modal fade" id="viewTestingModal<?php echo $row['nik']; ?>" tabindex="-1" aria-labelledby="viewTestingModalLabel<?php echo $row['nik']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewTestingModalLabel<?php echo $row['nik']; ?>"><i class="fas fa-eye"></i> Detail Data Testing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Pastikan variabel $detail_testing sudah diambil sebelum file ini di-include
                // Jika $detail_testing belum ada (misal karena load data AJAX), Anda perlu mengambilnya di sini.
                // Namun, dalam konteks loop di klasifikasi.php, $detail_testing seharusnya sudah ada.
                // Kode ini hanya sebagai jaga-jaga.
                if (!isset($detail_testing) || $detail_testing === null) {
                    if (isset($pdo) && isset($row['nik'])) {
                        $fallback_detail_query = "SELECT * FROM data_testing WHERE nik = ?";
                        $fallback_detail_stmt = $pdo->prepare($fallback_detail_query);
                        $fallback_detail_stmt->execute([$row['nik']]);
                        $detail_testing = $fallback_detail_stmt->fetch();
                    }
                }
                ?>
                <?php if (isset($detail_testing) && $detail_testing !== false) { // Pastikan $detail_testing berhasil diambil ?>
                <div class="row g-3">
                    <!-- Kolom Data Pribadi -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user"></i> Data Pribadi</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>NIK:</strong> <?php echo htmlspecialchars($detail_testing['nik']); ?></p>
                                <p><strong>Nama:</strong> <?php echo htmlspecialchars(ucwords(strtolower($detail_testing['nama']))); ?></p>
                                <p><strong>RT:</strong> <?php echo htmlspecialchars($detail_testing['rt']); ?></p>
                                <p><strong>RW:</strong> <?php echo htmlspecialchars($detail_testing['rw']); ?></p>
                                <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars(ucwords($detail_testing['jenis_kelamin'])); ?></p>
                                <p><strong>Pendidikan Terakhir:</strong> <?php echo htmlspecialchars($detail_testing['pendidikan_terakhir']); ?></p>
                                <p><strong>Pekerjaan:</strong> <?php echo htmlspecialchars($detail_testing['pekerjaan']); ?></p>
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
                                <p><strong>Tempat Tinggal:</strong> <?php echo htmlspecialchars(ucwords($detail_testing['tempat_tinggal'])); ?></p>
                                <p><strong>Dinding:</strong> <?php echo htmlspecialchars(ucwords($detail_testing['dinding'])); ?></p>
                                <p><strong>Lantai:</strong> <?php echo htmlspecialchars(ucwords($detail_testing['lantai'])); ?></p>
                                <p><strong>Atap:</strong> <?php echo htmlspecialchars(ucwords($detail_testing['atap'])); ?></p>
                                <p><strong>Sumber Air Minum:</strong> <?php echo htmlspecialchars($detail_testing['sumber_air_minum']); ?></p>
                                <p><strong>Sumber Air:</strong> <?php echo htmlspecialchars($detail_testing['sumber_air']); ?></p>
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
                                <p><strong>Sumber Penerangan:</strong> <?php echo htmlspecialchars($detail_testing['sumber_penerangan']); ?></p>
                                <p><strong>Bahan Bakar Masak:</strong> <?php echo htmlspecialchars($detail_testing['bahan_bakar_masak']); ?></p>
                                <p><strong>Tempat BAB:</strong> <?php echo htmlspecialchars($detail_testing['tempat_bab']); ?></p>
                                <p><strong>Jenis Kloset:</strong> <?php echo htmlspecialchars($detail_testing['jenis_kloset']); ?></p>
                                <p><strong>Aset Bergerak:</strong> <?php echo htmlspecialchars($detail_testing['aset_bergerak']); ?></p>
                                <p><strong>Keterangan:</strong>
                                    <span class="badge <?php echo ($detail_testing['keterangan'] == 'Miskin') ? 'bg-danger' : 'bg-success'; ?>">
                                        <?php echo htmlspecialchars($detail_testing['keterangan']); ?>
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