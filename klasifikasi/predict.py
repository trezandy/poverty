import sys
import json
import pandas as pd
import joblib
import warnings

warnings.filterwarnings('ignore', category=UserWarning)

try:
    # 1. Muat model dan kolom yang sudah disimpan
    model = joblib.load('svm_model.pkl')
    model_columns = joblib.load('model_columns.pkl')

    # 2. Baca path file data input dari argumen
    input_file_path = sys.argv[1]
    df = pd.read_json(input_file_path)

    if df.empty:
        raise ValueError("Data input kosong.")

    # Simpan NIK untuk mapping hasil nanti
    niks = df['nik']
    
    # 3. Pra-pemrosesan data input
    # Pisahkan fitur dari data input
    features = df.drop(columns=['id', 'nik', 'nama', 'rt', 'rw', 'keterangan'])
    
    # One-Hot Encoding
    features_encoded = pd.get_dummies(features, drop_first=True, dtype=int)
    
    # Rekonsiliasi kolom agar sama persis dengan saat training
    # Ini langkah krusial untuk prediksi
    processed_df = pd.DataFrame(columns=model_columns)
    processed_df = pd.concat([processed_df, features_encoded], axis=0).fillna(0)
    processed_df = processed_df[model_columns] # Pastikan urutan dan nama kolom sama

    # 4. Lakukan prediksi massal
    predictions_encoded = model.predict(processed_df)

    # 5. Konversi hasil prediksi dari angka kembali ke label ('Miskin', 'Tidak Miskin')
    # LabelEncoder di-fit ulang dengan urutan yang sama (alfabetis)
    from sklearn.preprocessing import LabelEncoder
    le = LabelEncoder()
    le.fit(['Miskin', 'Tidak Miskin'])
    predictions_labels = le.inverse_transform(predictions_encoded)
    
    # 6. Siapkan output dalam format {nik: hasil}
    results = dict(zip(niks, predictions_labels))

    output = {
        "success": True,
        "predictions": results
    }

except Exception as e:
    output = {
        "success": False,
        "message": f"Error pada skrip prediksi Python: {str(e)}"
    }

print(json.dumps(output))