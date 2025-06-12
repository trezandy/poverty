import sys
import json
import logging
import traceback
import os
import math
import base64

# Setup logging dengan path absolut ke direktori temp
try:
    temp_dir = os.environ.get('TEMP', os.path.expanduser('~'))
    log_dir = os.path.join(temp_dir, 'svm_logs')
    os.makedirs(log_dir, exist_ok=True)
    log_file = os.path.join(log_dir, 'svm_process.log')
    
    logging.basicConfig(
        level=logging.DEBUG,
        format='%(asctime)s - %(levelname)s - %(message)s',
        handlers=[
            logging.FileHandler(log_file, encoding='utf-8'),
            logging.StreamHandler()  # Tambahkan output ke console juga
        ]
    )
    
    logging.info(f"File log dibuat di: {log_file}")
except Exception as e:
    print(json.dumps({
        'success': False,
        'message': f'Error setup logging: {str(e)}'
    }))
    sys.exit(1)

class SimpleSVM:
    def __init__(self):
        self.weights = None
        self.bias = None
        
    def fit(self, X, y):
        try:
            logging.info("Memulai training SVM sederhana")
            n_samples = len(X)
            n_features = len(X[0])
            
            # Inisialisasi weights dan bias
            self.weights = [0.0] * n_features
            self.bias = 0.0
            
            # Learning rate
            learning_rate = 0.01
            n_iterations = 1000
            
            # Training loop
            for iteration in range(n_iterations):
                for i in range(n_samples):
                    # Hitung prediksi
                    prediction = self.predict_single(X[i])
                    
                    # Update weights dan bias jika prediksi salah
                    if y[i] * prediction < 1:
                        for j in range(n_features):
                            self.weights[j] += learning_rate * (y[i] * X[i][j] - 2 * (1/n_iterations) * self.weights[j])
                        old_bias = self.bias
                        self.bias += learning_rate * y[i]
                        if iteration % 100 == 0:  # Log setiap 100 iterasi
                            logging.debug(f"Iterasi {iteration}, Data {i}: Bias berubah dari {old_bias:.4f} menjadi {self.bias:.4f}")
            
            logging.info(f"Training selesai. Nilai bias akhir: {self.bias:.4f}")
            
        except Exception as e:
            logging.error(f"Error dalam training SVM: {str(e)}")
            logging.error(f"Traceback: {traceback.format_exc()}")
            raise
    
    def predict_single(self, x):
        return sum(w * xi for w, xi in zip(self.weights, x)) + self.bias
    
    def predict(self, X):
        try:
            logging.info("Memulai prediksi")
            predictions = []
            for x in X:
                pred = self.predict_single(x)
                result = 'Tidak Miskin' if pred > 0 else 'Miskin'
                logging.debug(f"Nilai prediksi: {pred}, Hasil: {result}")
                predictions.append(result)
            logging.info(f"Prediksi selesai: {predictions}")
            return predictions
        except Exception as e:
            logging.error(f"Error dalam prediksi: {str(e)}")
            logging.error(f"Traceback: {traceback.format_exc()}")
            raise

def load_model_data() -> dict | None:
    """Loads the training data from model.pkl"""
    try:
        # Dapatkan path absolut ke direktori saat ini
        current_dir = os.path.dirname(os.path.abspath(__file__))
        model_file = os.path.join(current_dir, 'klasifikasi', 'model.pkl')
        
        logging.info(f"Mencoba membuka file model di: {model_file}")
        
        if os.path.exists(model_file):
            with open(model_file, 'r', encoding='utf-8') as f:
                try:
                    data = json.loads(f.read())
                    logging.info("Berhasil membaca dan decode file model")
                    return data
                except (json.JSONDecodeError, EOFError, TypeError) as e:
                    logging.error(f"Error decoding model file: {str(e)}")
                    logging.error(f"Traceback: {traceback.format_exc()}")
                    return None
        else:
            logging.warning(f"Model file tidak ditemukan di: {model_file}")
            return None
    except Exception as e:
        logging.error(f"Error dalam load_model_data: {str(e)}")
        logging.error(f"Traceback: {traceback.format_exc()}")
        return None

def preprocess_data(data: dict) -> tuple[list[list[int]], list[int], list[list[int]]]:
    try:
        logging.info("Memulai preprocessing data")
        
        # Konversi data training
        X_train = []
        y_train = []
        
        logging.debug(f"Jumlah data training: {len(data['training_data'])}")
        
        # Mapping untuk semua atribut kategorikal
        pendidikan_map = {
            'SD': 0,
            'SLTP': 1,
            'SLTA': 2
        }
        pekerjaan_map = {
            'Buruh': 0,
            'Tani': 1,
            'Pedagang': 2
        }
        tempat_tinggal_map = {
            'bebas sewa': 0,
            'kontrakan': 1,
            'milik sendiri': 2
        }
        dinding_map = {
            'anyaman bambu': 0,
            'kayu': 1,
            'tembok': 2
        }
        lantai_map = {
            'kayu': 0,
            'semen/bata merah': 1,
            'ubin': 2,
            'keramik': 3
        }
        atap_map = {
            'anyaman bambu': 0,
            'seng': 1
        }
        sumber_air_minum_map = {
            'Sumur Bor/Pompa': 0,
            'Sumur Terlindung': 1,
            'Air Isi Ulang': 2
        }
        sumber_air_map = {
            'Air Sungai': 0,
            'PAM': 1
        }
        sumber_penerangan_map = {
            'Bukan Listrik (Tanpa Meteran)': 0,
            'Listrik PLN (Tanpa Meteran)': 1,
            'Listrik Non PLN (450 Watt)': 2,
            'Listrik PLN (450 Watt)': 3,
            'Listrik PLN (900 Watt)': 4
        }
        bahan_bakar_masak_map = {
            'Gas 3Kg': 0,
            'Listrik': 1
        }
        tempat_bab_map = {
            'Umum': 0,
            'Sendiri': 1
        }
        jenis_kloset_map = {
            'Tidak Pakai': 0,
            'Cemplung/Cubluk': 1,
            'Leher Angsa': 2
        }
        aset_bergerak_map = {
            'Tidak ada': 0,
            'Ada': 1
        }
        keterangan_map = {
            'Miskin': -1,
            'Tidak Miskin': 1
        }

        for idx, item in enumerate(data['training_data']):
            try:
                # logging.debug(f"Memproses data training ke-{idx + 1}: {item}")
                
                features = [
                    pendidikan_map.get(item['pendidikan_terakhir'], 0),
                    pekerjaan_map.get(item['pekerjaan'], 0),
                    tempat_tinggal_map.get(item['tempat_tinggal'], 0),
                    dinding_map.get(item['dinding'], 0),
                    lantai_map.get(item['lantai'], 0),
                    atap_map.get(item['atap'], 0),
                    sumber_air_minum_map.get(item['sumber_air_minum'], 0),
                    sumber_air_map.get(item['sumber_air'], 0),
                    sumber_penerangan_map.get(item['sumber_penerangan'], 0),
                    bahan_bakar_masak_map.get(item['bahan_bakar_masak'], 0),
                    tempat_bab_map.get(item['tempat_bab'], 0),
                    jenis_kloset_map.get(item['jenis_kloset'], 0),
                    aset_bergerak_map.get(item['aset_bergerak'], 0)
                ]
                
                X_train.append(features)
                y_train.append(keterangan_map.get(item['keterangan'], -1))
                
                # logging.debug(f"Data training ke-{idx + 1} berhasil dikonversi: features={features}, label={y_train[-1]}")
            except Exception as e:
                logging.error(f"Error pada data training ke-{idx + 1}: {str(e)}")
                raise
        
        # Konversi data testing dengan skala yang sama
        # logging.debug(f"Memproses data testing: {data['test_data']}")
        try:
            test_features = [
                pendidikan_map.get(data['test_data']['pendidikan_terakhir'], 0),
                pekerjaan_map.get(data['test_data']['pekerjaan'], 0),
                tempat_tinggal_map.get(data['test_data']['tempat_tinggal'], 0),
                dinding_map.get(data['test_data']['dinding'], 0),
                lantai_map.get(data['test_data']['lantai'], 0),
                atap_map.get(data['test_data']['atap'], 0),
                sumber_air_minum_map.get(data['test_data']['sumber_air_minum'], 0),
                sumber_air_map.get(data['test_data']['sumber_air'], 0),
                sumber_penerangan_map.get(data['test_data']['sumber_penerangan'], 0),
                bahan_bakar_masak_map.get(data['test_data']['bahan_bakar_masak'], 0),
                tempat_bab_map.get(data['test_data']['tempat_bab'], 0),
                jenis_kloset_map.get(data['test_data']['jenis_kloset'], 0),
                aset_bergerak_map.get(data['test_data']['aset_bergerak'], 0)
            ]
            logging.debug(f"Data testing berhasil dikonversi: {test_features}")
        except Exception as e:
            logging.error(f"Error pada data testing: {str(e)}")
            raise
        
        logging.info(f"Preprocessing selesai. Jumlah data training: {len(X_train)}")
        
        return X_train, y_train, [test_features]
    except Exception as e:
        logging.error(f"Error dalam preprocess_data: {str(e)}")
        logging.error(f"Traceback: {traceback.format_exc()}")
        raise

def main():
    try:
        logging.info("Memulai proses SVM")
        # Load model data
        model_data = load_model_data()
        if model_data is None:
            print(json.dumps({
                'success': False,
                'message': 'Model belum dibuat. Silakan buat model terlebih dahulu.'
            }))
            sys.exit(1)

        # Baca input dari argumen baris perintah
        if len(sys.argv) < 2:
            error_msg = 'Error: Tidak ada input data'
            logging.error(error_msg)
            print(json.dumps({'success': False, 'message': error_msg}))
            sys.exit(1)
        
        # Input data testing dalam format base64 dari argumen pertama
        input_base64 = sys.argv[1]
        logging.debug(f"Menerima input base64: {input_base64}")
        
        try:
            # Decode base64 ke string JSON
            input_json_string = base64.b64decode(input_base64).decode('utf-8')
            logging.debug(f"JSON setelah decode base64: {input_json_string}")
            
            # Parse JSON
            test_data = json.loads(input_json_string)
            logging.info("Berhasil decode input JSON")
            logging.debug(f"Data yang diterima: {test_data}")
        except base64.binascii.Error as e:
            error_msg = f'Error decoding base64: {str(e)}'
            logging.error(error_msg)
            print(json.dumps({'success': False, 'message': error_msg}))
            sys.exit(1)
        except json.JSONDecodeError as e:
            error_msg = f'Input JSON tidak valid: {str(e)}\nInput: {repr(input_json_string)}'
            logging.error(error_msg)
            print(json.dumps({'success': False, 'message': error_msg}))
            sys.exit(1)

        # Gabungkan data training dan data testing untuk preprocessing
        full_data = {
            'training_data': model_data['data'],
            'test_data': test_data
        }

        X_train, y_train, X_test = preprocess_data(full_data)
        
        if not X_train or not X_test:
            error_msg = 'Gagal memproses data'
            logging.error(error_msg)
            print(json.dumps({'success': False, 'message': error_msg}))
            sys.exit(1)

        # Latih model SVM sederhana
        svm_model = SimpleSVM()
        svm_model.fit(X_train, y_train)
        
        # Prediksi data testing
        predictions = svm_model.predict(X_test)
        
        # Ambil hasil prediksi pertama (karena hanya ada satu data testing)
        hasil_klasifikasi = predictions[0]
        
        # Output hasil klasifikasi dalam format JSON
        result = {
            'success': True,
            'keterangan': hasil_klasifikasi
        }
        logging.info(f"Proses SVM selesai. Hasil klasifikasi: {hasil_klasifikasi}")
        print(json.dumps(result))

    except Exception as e:
        error_msg = f'Terjadi kesalahan: {str(e)}'
        logging.error(error_msg)
        logging.error(f"Traceback: {traceback.format_exc()}")
        print(json.dumps({'success': False, 'message': error_msg}))
        sys.exit(1)

if __name__ == "__main__":
    main()