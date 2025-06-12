import sys
import json
import pandas as pd
from sklearn.svm import SVC
from sklearn.metrics import confusion_matrix
from sklearn.preprocessing import LabelEncoder
import joblib
import warnings

# Abaikan peringatan yang mungkin muncul
warnings.filterwarnings('ignore', category=UserWarning)

def calculate_metrics(tn, fp, fn, tp):
    """Menghitung metrik performa dan menangani pembagian dengan nol."""
    accuracy = (tp + tn) / (tp + tn + fp + fn) if (tp + tn + fp + fn) > 0 else 0
    precision = tp / (tp + fp) if (tp + fp) > 0 else 0
    recall = tp / (tp + fn) if (tp + fn) > 0 else 0
    f1_score = 2 * (precision * recall) / (precision + recall) if (precision + recall) > 0 else 0
    
    return {
        "accuracy": round(accuracy * 100, 2),
        "precision": round(precision * 100, 2),
        "recall": round(recall * 100, 2),
        "f1_score": round(f1_score * 100, 2)
    }

def process_data(df):
    """Melakukan pra-pemrosesan data: One-Hot Encoding untuk fitur kategorikal."""
    target_column = 'keterangan'
    features = df.drop(columns=[target_column, 'id', 'nik', 'nama', 'rt', 'rw'])
    features_encoded = pd.get_dummies(features, drop_first=False, dtype=int)
    
    le = LabelEncoder()
    target_encoded = le.fit_transform(df[target_column])
    
    positive_class_label = 'Miskin'
    # Mencari tahu apakah 'Miskin' akan di-encode sebagai 0 atau 1
    # Ini penting untuk membaca confusion matrix dengan benar
    positive_class_encoded_value = le.transform([positive_class_label])[0]
    
    return features_encoded, target_encoded, le.classes_, positive_class_encoded_value

try:
    # 1. Baca path file dari argumen command line
    train_file_path = sys.argv[1]
    test_file_path = sys.argv[2]

    # Baca data dari file JSON
    df_train = pd.read_json(train_file_path)
    df_test = pd.read_json(test_file_path)

    # 2. Pra-pemrosesan data
    X_train, y_train, class_names, positive_class_val = process_data(df_train)
    X_test, y_test, _, _ = process_data(df_test)
    
    # Sinkronkan kolom antara data training dan testing
    missing_cols = set(X_train.columns) - set(X_test.columns)
    for c in missing_cols:
        X_test[c] = 0
    X_test = X_test[X_train.columns]

    # 3. Training Model SVM
    model = SVC(kernel='linear', probability=True, random_state=42)
    model.fit(X_train, y_train)

    # 4. Simpan model dan kolom
    model_path = 'svm_model.pkl'
    columns_path = 'model_columns.pkl'
    joblib.dump(model, model_path)
    joblib.dump(list(X_train.columns), columns_path)

    # 5. Prediksi pada data testing
    y_pred = model.predict(X_test)

    # 6. Hitung Confusion Matrix
    # Pastikan label diurutkan dengan benar
    # Misal: [Tidak Miskin, Miskin] -> [0, 1] jika positive_class_val adalah 1
    labels_in_order = [1 - positive_class_val, positive_class_val]
    tn, fp, fn, tp = confusion_matrix(y_test, y_pred, labels=labels_in_order).ravel()

    # 7. Hitung metrik
    metrics = calculate_metrics(int(tn), int(fp), int(fn), int(tp))
    
    # 8. Siapkan output JSON
    output = {
        "success": True,
        "message": f"Model berhasil dibuat ({model_path}). Analisis selesai.",
        "matrix": {"TP": int(tp), "TN": int(tn), "FP": int(fp), "FN": int(fn)},
        "metrics": metrics
    }

except Exception as e:
    output = {
        "success": False,
        "message": f"Error pada skrip Python: {str(e)}"
    }

# Cetak output JSON agar bisa ditangkap oleh PHP
print(json.dumps(output))