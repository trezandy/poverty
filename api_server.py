from flask import Flask, request, jsonify
import pandas as pd
import joblib
import numpy as np
import warnings

warnings.filterwarnings('ignore', category=UserWarning)

# Inisialisasi aplikasi Flask
app = Flask(__name__)

# Muat model dan kolom HANYA SEKALI saat server dimulai
try:
    model = joblib.load('klasifikasi/svm_model.pkl')
    model_columns = joblib.load('klasifikasi/model_columns.pkl')
except FileNotFoundError:
    model = None
    model_columns = None
    print("WARNING: svm_model.pkl atau model_columns.pkl tidak ditemukan. Endpoint /predict tidak akan berfungsi.")

@app.route('/predict', methods=['POST'])
def predict():
    if model is None or model_columns is None:
        return jsonify({
            "success": False,
            "message": "Model tidak dapat dimuat saat server dimulai. Pastikan file model ada."
        }), 500

    try:
        # Ambil data JSON yang dikirim oleh PHP
        input_data = request.get_json()
        if not input_data:
            return jsonify({"success": False, "message": "Request JSON kosong."}), 400

        # Buat DataFrame dari satu baris data
        df = pd.DataFrame([input_data])
        
        # Pra-pemrosesan data input
        features_encoded = pd.get_dummies(df, drop_first=False, dtype=int)
        
        # Rekonsiliasi kolom
        processed_df = pd.DataFrame(columns=model_columns)
        processed_df = pd.concat([processed_df, features_encoded]).fillna(0)
        processed_df = processed_df[model_columns].head(1)

        # Lakukan prediksi dan dapatkan probabilitas
        probabilities = model.predict_proba(processed_df)[0]
        prediction_encoded = np.argmax(probabilities)
        confidence = round(np.max(probabilities) * 100, 2)

        # Konversi hasil
        from sklearn.preprocessing import LabelEncoder
        le = LabelEncoder()
        le.fit(['Miskin', 'Tidak Miskin'])
        prediction_label = le.inverse_transform([prediction_encoded])[0]
        
        # Siapkan output
        result = {
            "success": True,
            "prediction": {
                "class": prediction_label,
                "confidence": confidence
            }
        }
        return jsonify(result)

    except Exception as e:
        return jsonify({
            "success": False,
            "message": f"Error internal pada API Python: {str(e)}"
        }), 500

if __name__ == '__main__':
    # Jalankan server di localhost pada port 5000
    app.run(host='127.0.0.1', port=5000)