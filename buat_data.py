import random
import time

# --- PENGATURAN JUMLAH DATA ---
JUMLAH_TRAINING_MISKIN = 200
JUMLAH_TRAINING_TIDAK_MISKIN = 200
JUMLAH_TESTING_MISKIN = 50
JUMLAH_TESTING_TIDAK_MISKIN = 50
NAMA_FILE_TRAINING = "dummy_training.sql"
NAMA_FILE_TESTING = "dummy_testing.sql"
# ---------------------------------

# Daftar pilihan untuk setiap kolom
list_jk = ['laki-laki', 'perempuan']
list_pendidikan = ['SD', 'SLTP', 'SLTA']
list_pekerjaan = ['Tani', 'Pedagang', 'Buruh']
list_aset = ['Ada', 'Tidak ada']
list_tempat_tinggal = ['lainnya', 'bebas sewa', 'milik sendiri', 'kontrakan']
list_dinding = ['anyaman bambu', 'kayu', 'tembok']
list_lantai = ['kayu', 'semen/bata merah', 'ubin', 'keramik']
list_atap = ['seng', 'anyaman bambu', 'anyaman']
list_air_minum = ['Sumur Bor/Pompa', 'Sumur Terlindung', 'Air Isi Ulang']
list_sumber_air = ['Air Sungai', 'PAM']
list_penerangan = ['Bukan Listrik (Tanpa Meteran)','Listrik PLN (Tanpa Meteran)','Listrik Non PLN (450 Watt)','Listrik PLN (450 Watt)','Listrik PLN (900 Watt)']
list_bahan_bakar = ['Gas 3Kg', 'Listrik']
list_bab = ['Umum', 'Sendiri']
list_kloset = ['Tidak Pakai', 'Cemplung/Cubluk', 'Leher Angsa']

# Bobot probabilitas untuk setiap profil
profil_miskin = {
    "pendidikan_terakhir": (['SD', 'SLTP', 'SLTA'], [0.6, 0.3, 0.1]),
    "pekerjaan": (['Buruh', 'Tani', 'Pedagang'], [0.5, 0.4, 0.1]),
    "aset_bergerak": (['Tidak ada', 'Ada'], [0.8, 0.2]),
    "tempat_tinggal": (['milik sendiri', 'bebas sewa', 'kontrakan', 'lainnya'], [0.5, 0.2, 0.2, 0.1]),
    "dinding": (['anyaman bambu', 'kayu', 'tembok'], [0.6, 0.3, 0.1]),
    "lantai": (['kayu', 'semen/bata merah', 'ubin', 'keramik'], [0.5, 0.4, 0.05, 0.05]),
    "atap": (['anyaman bambu', 'seng', 'anyaman'], [0.5, 0.4, 0.1]),
    "sumber_penerangan": (['Bukan Listrik (Tanpa Meteran)', 'Listrik PLN (450 Watt)'], [0.6, 0.4]),
    "tempat_bab": (['Umum', 'Sendiri'], [0.7, 0.3]),
    "jenis_kloset": (['Tidak Pakai', 'Cemplung/Cubluk', 'Leher Angsa'], [0.6, 0.3, 0.1]),
}

profil_tidak_miskin = {
    "pendidikan_terakhir": (['SLTA', 'SLTP', 'SD'], [0.7, 0.2, 0.1]),
    "pekerjaan": (['Pedagang', 'Tani', 'Buruh'], [0.6, 0.3, 0.1]),
    "aset_bergerak": (['Ada', 'Tidak ada'], [0.9, 0.1]),
    "tempat_tinggal": (['milik sendiri', 'kontrakan', 'bebas sewa', 'lainnya'], [0.8, 0.1, 0.05, 0.05]),
    "dinding": (['tembok', 'kayu', 'anyaman bambu'], [0.8, 0.15, 0.05]),
    "lantai": (['keramik', 'ubin', 'semen/bata merah', 'kayu'], [0.6, 0.3, 0.1, 0.0]),
    "atap": (['seng', 'anyaman', 'anyaman bambu'], [0.9, 0.05, 0.05]),
    "sumber_penerangan": (['Listrik PLN (900 Watt)', 'Listrik PLN (450 Watt)'], [0.7, 0.3]),
    "tempat_bab": (['Sendiri', 'Umum'], [0.9, 0.1]),
    "jenis_kloset": (['Leher Angsa', 'Cemplung/Cubluk', 'Tidak Pakai'], [0.8, 0.15, 0.05]),
}

def generate_nik():
    """Membuat NIK dummy 16 digit yang unik berdasarkan timestamp"""
    return str(int(time.time() * 10000)) + str(random.randint(100, 999))

def generate_nama():
    """Membuat nama dummy"""
    suku_kata1 = ["Adi", "Budi", "Cahyo", "Dedi", "Eko", "Fajar", "Gita", "Hadi", "Indra", "Joko"]
    suku_kata2 = ["Prasetyo", "Wibowo", "Nugroho", "Susanto", "Wijaya", "Kusumo", "Lestari", "Setiawan"]
    return f"{random.choice(suku_kata1)} {random.choice(suku_kata2)}"

def create_record(keterangan):
    """Membuat satu baris data berdasarkan profil keterangan"""
    profil = profil_miskin if keterangan == "Miskin" else profil_tidak_miskin
    
    record = {
        'nik': generate_nik(),
        'nama': generate_nama(),
        'jenis_kelamin': random.choice(list_jk),
        'pendidikan_terakhir': random.choices(*profil['pendidikan_terakhir'])[0],
        'pekerjaan': random.choices(*profil['pekerjaan'])[0],
        'aset_bergerak': random.choices(*profil['aset_bergerak'])[0],
        'rt': str(random.randint(1, 10)),
        'rw': str(random.randint(1, 5)),
        'tempat_tinggal': random.choices(*profil['tempat_tinggal'])[0],
        'dinding': random.choices(*profil['dinding'])[0],
        'lantai': random.choices(*profil['lantai'])[0],
        'atap': random.choices(*profil['atap'])[0],
        'sumber_air_minum': random.choice(list_air_minum),
        'sumber_air': random.choice(list_sumber_air),
        'sumber_penerangan': random.choices(*profil['sumber_penerangan'])[0],
        'bahan_bakar_masak': random.choice(list_bahan_bakar),
        'tempat_bab': random.choices(*profil['tempat_bab'])[0],
        'jenis_kloset': random.choices(*profil['jenis_kloset'])[0],
        'keterangan': keterangan
    }
    return record

def generate_sql(table_name, records):
    """Menghasilkan statement SQL INSERT dari daftar record"""
    if not records:
        return ""
    
    sql = f"INSERT INTO `{table_name}` (`nik`, `nama`, `jenis_kelamin`, `pendidikan_terakhir`, `pekerjaan`, `aset_bergerak`, `rt`, `rw`, `tempat_tinggal`, `dinding`, `lantai`, `atap`, `sumber_air_minum`, `sumber_air`, `sumber_penerangan`, `bahan_bakar_masak`, `tempat_bab`, `jenis_kloset`, `keterangan`) VALUES\n"
    
    value_strings = []
    for record in records:
        # ===== PERUBAHAN DI BARIS INI =====
        # Menggunakan tiga kutip ganda (f""") untuk menghindari konflik
        values = ", ".join([f"""'{str(v).replace("'", "''")}'""" for v in record.values()])
        value_strings.append(f"({values})")
    
    sql += ",\n".join(value_strings) + ";\n"
    return sql

# Membuat data
training_records = [create_record("Miskin") for _ in range(JUMLAH_TRAINING_MISKIN)] + \
                   [create_record("Tidak Miskin") for _ in range(JUMLAH_TRAINING_TIDAK_MISKIN)]
                   
testing_records = [create_record("Miskin") for _ in range(JUMLAH_TESTING_MISKIN)] + \
                  [create_record("Tidak Miskin") for _ in range(JUMLAH_TESTING_TIDAK_MISKIN)]

random.shuffle(training_records)
random.shuffle(testing_records)

try:
    # 1. Menulis file untuk data training
    sql_training = generate_sql('data_training', training_records)
    with open(NAMA_FILE_TRAINING, 'w', encoding='utf-8') as f:
        f.write(f"-- SQL Dump untuk data_training\n")
        f.write(f"-- Dihasilkan pada: {time.strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        f.write(sql_training)
    print(f"\n[✓] Berhasil! File SQL untuk training telah disimpan sebagai: {NAMA_FILE_TRAINING}")

    # 2. Menulis file untuk data testing
    sql_testing = generate_sql('data_testing', testing_records)
    with open(NAMA_FILE_TESTING, 'w', encoding='utf-8') as f:
        f.write(f"-- SQL Dump untuk data_testing\n")
        f.write(f"-- Dihasilkan pada: {time.strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        f.write(sql_testing)
    print(f"[✓] Berhasil! File SQL untuk testing telah disimpan sebagai: {NAMA_FILE_TESTING}")

except IOError as e:
    print(f"\n[X] Error: Gagal menyimpan file. {e}")