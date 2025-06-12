<?php
require_once 'config/database.php';

// Fungsi helper untuk menghitung persentase
function calculatePercentage($data, $kategori = 'miskin') {
    if (empty($data)) return 0;
    
    $total = array_reduce($data, function($carry, $item) {
        return $carry + $item['jumlah'];
    }, 0);

    if ($total === 0) return 0;

    $kategoriData = array_values(array_filter($data, function($item) use ($kategori) {
        return strtolower($item['keterangan']) === strtolower($kategori);
    }));

    if (empty($kategoriData)) return 0;

    return ($kategoriData[0]['jumlah'] / $total) * 100;
}

// Mengambil semua statistik dalam satu query
try {
    $queries = [
        'total_training' => "SELECT COUNT(*) FROM data_training",
        'total_testing' => "SELECT COUNT(*) FROM data_testing",
        'statistik_training' => "SELECT keterangan, COUNT(*) as jumlah FROM data_training GROUP BY keterangan",
        'statistik_testing' => "SELECT keterangan, COUNT(*) as jumlah FROM data_testing GROUP BY keterangan",
    ];

    $results = [];
    foreach ($queries as $key => $query) {
        $stmt = $pdo->query($query);
        if (strpos($key, 'total_') === 0) {
            $results[$key] = $stmt->fetchColumn();
        } else {
            $results[$key] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    extract($results);

} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    // Set default values if query fails
    $statistik_training = [];
    $statistik_testing = [];
    $total_training = 0;
    $total_testing = 0;
    $akurasi = 0; // Keep akurasi variable initialized to 0 for safety, although not displayed
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Klasifikasi SVM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background: #f8f9fa;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .main-content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-title {
            color: var(--secondary);
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-size: 1.5rem;
        }

        .stat-icon.training {
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary);
        }

        .stat-icon.testing {
            background: rgba(25, 135, 84, 0.1);
            color: var(--success);
        }

        .stat-icon.accuracy {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .stat-description {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .chart-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            overflow: hidden;
        }

        .chart-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chart-header i {
            color: var(--primary);
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .chart-body {
            padding: 1.5rem;
            min-height: 300px;
        }

        .chart-footer {
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .legend-color.miskin {
            background: var(--danger);
        }

        .legend-color.tidak-miskin {
            background: var(--success);
        }

        .legend-text {
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .progress {
            height: 0.5rem;
            border-radius: 1rem;
        }

        .progress-bar {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid">
            <h2 class="mb-4">Dashboard</h2>
            
            <div class="stats-grid">
                <!-- Total Data Training -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Total Data Training</div>
                        <div class="stat-icon training">
                            <i class="fas fa-database"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_training); ?></div>
                    <div class="stat-description">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Miskin</span>
                            <span><?php echo number_format(calculatePercentage($statistik_training, 'miskin'), 1); ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width: <?php echo calculatePercentage($statistik_training, 'miskin'); ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span>Tidak Miskin</span>
                            <span><?php echo number_format(calculatePercentage($statistik_training, 'tidak miskin'), 1); ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?php echo calculatePercentage($statistik_training, 'tidak miskin'); ?>%"></div>
                        </div>
                    </div>
                </div>

                <!-- Total Data Testing -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Total Data Testing</div>
                        <div class="stat-icon testing">
                            <i class="fas fa-vial"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_testing); ?></div>
                    <div class="stat-description">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Miskin</span>
                            <span><?php echo number_format(calculatePercentage($statistik_testing, 'miskin'), 1); ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width: <?php echo calculatePercentage($statistik_testing, 'miskin'); ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span>Tidak Miskin</span>
                            <span><?php echo number_format(calculatePercentage($statistik_testing, 'tidak miskin'), 1); ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?php echo calculatePercentage($statistik_testing, 'tidak miskin'); ?>%"></div>
                        </div>
                    </div>
                </div>

                <!-- Akurasi Model -->
                </div>

            <div class="chart-grid">
                <!-- Distribusi Data Training -->
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="fas fa-chart-pie"></i>
                        <h5 class="chart-title">Distribusi Data Training</h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="trainingChart"></canvas>
                    </div>
                    <div class="chart-footer">
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-color miskin"></div>
                                <span class="legend-text">Miskin</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color tidak-miskin"></div>
                                <span class="legend-text">Tidak Miskin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribusi Data Testing -->
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="fas fa-chart-pie"></i>
                        <h5 class="chart-title">Distribusi Data Testing</h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="testingChart"></canvas>
                    </div>
                    <div class="chart-footer">
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-color miskin"></div>
                                <span class="legend-text">Miskin</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color tidak-miskin"></div>
                                <span class="legend-text">Tidak Miskin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk chart
        const trainingData = <?php echo json_encode($statistik_training); ?>;
        const testingData = <?php echo json_encode($statistik_testing); ?>;

        // Fungsi untuk mendapatkan jumlah berdasarkan keterangan
        function getCount(data, keterangan) {
            const item = data.find(item => item.keterangan.toLowerCase() === keterangan.toLowerCase());
            return item ? item.jumlah : 0;
        }

        // Chart Data Training
        new Chart(document.getElementById('trainingChart'), {
            type: 'pie',
            data: {
                labels: ['Miskin', 'Tidak Miskin'],
                datasets: [{
                    data: [
                        getCount(trainingData, 'miskin'),
                        getCount(trainingData, 'tidak miskin')
                    ],
                    backgroundColor: ['#dc3545', '#198754']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Chart Data Testing
        new Chart(document.getElementById('testingChart'), {
            type: 'pie',
            data: {
                labels: ['Miskin', 'Tidak Miskin'],
                datasets: [{
                    data: [
                        getCount(testingData, 'miskin'),
                        getCount(testingData, 'tidak miskin')
                    ],
                    backgroundColor: ['#dc3545', '#198754']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html> 