# predict_single.py
import sys
import json
import pandas as pd
import joblib
import warnings
import numpy as np

warnings.filterwarnings('ignore', category=UserWarning)

try:
    # 1. Muat model dan kolom yang sudah disimpan
    model = joblib.load('svm_model.pkl')
    model_columns = joblib.load('model_columns.pkl')

    # 2. Baca data JSON dari argumen (sekarang hanya satu objek)
    input_data = json.loads(sys.argv[1])
    
    # Buat DataFrame dari satu baris data
    df = pd.DataFrame([input_data])

    # 3. Pra-pemrosesan data input
    features_encoded = pd.get_dummies(df, drop_first=True, dtype=int)
    
    # Rekonsiliasi kolom agar sama persis dengan saat training
    processed_df = pd.DataFrame(columns=model_columns)
    processed_df = pd.concat([processed_df, features_encoded], axis=0).fillna(0)
    processed_df = processed_df[model_columns]

    # 4. Lakukan prediksi dan dapatkan probabilitas
    # Pastikan model dilatih dengan probability=True
    probabilities = model.predict_proba(processed_df)[0]
    prediction_encoded = np.argmax(probabilities)
    confidence = round(np.max(probabilities) * 100, 2)

    # 5. Konversi hasil prediksi dari angka kembali ke label
    from sklearn.preprocessing import LabelEncoder
    le = LabelEncoder()
    le.fit(['Miskin', 'Tidak Miskin'])
    prediction_label = le.inverse_transform([prediction_encoded])[0]
    
    # 6. Siapkan output
    output = {
        "success": True,
        "prediction": {
            "class": prediction_label,
            "confidence": confidence
        }
    }

except Exception as e:
    output = {
        "success": False,
        "message": f"Error pada skrip prediksi Python: {str(e)}"
    }

print(json.dumps(output))