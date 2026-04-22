from flask import Flask, request, jsonify
from datetime import datetime, timedelta
import pickle
import os
import numpy as np
import random

app = Flask(__name__)

# ================= 1. LOAD MODEL =================
MODEL_PATH = "model_forecast_eis.pkl"
model_data = None

def load_model():
    global model_data
    if os.path.exists(MODEL_PATH):
        try:
            with open(MODEL_PATH, "rb") as f:
                model_data = pickle.load(f)
            print("✅ Model EIS (Holt) berhasil diload")
        except Exception as e:
            print(f"❌ Gagal load model: {e}")
    else:
        print("⚠️ Model belum ada, jalankan train.py dulu")

load_model()

# ================= 2. FORECAST LOGIC =================
def calculate_forecast(m_data, start_date, days=7):
    level = m_data['level']
    trend = m_data['trend']
    multipliers = m_data['multipliers']
    
    forecast = []
    for k in range(1, days + 1):
        target_date = start_date + timedelta(days=k-1)
        dow = target_date.weekday()
        
        # Holt's Forecast: F(t+k) = L + k*T
        base_forecast = level + (k * trend)
        
        # Apply Seasonal Multiplier
        multiplier = multipliers.get(dow, 1.0)
        final_val = base_forecast * multiplier
        
        # HARI MINGGU LIBUR (Force 0)
        if dow == 6:
            final_val = 0
        else:
            # Add Small Random Noise (±5%) only on active days
            noise = random.uniform(0.95, 1.05)
            final_val = final_val * noise
        
        forecast.append({
            "tanggal": target_date.strftime("%Y-%m-%d"),
            "hari": target_date.strftime("%A"),
            "nilai": round(max(0, final_val), 1)
        })
    return forecast

def generate_insights(avg_demand, avg_interval):
    insights = []
    if avg_demand > 23:
        insights.append("Tren kunjungan meningkat tajam. Disarankan menambah jadwal dokter atau memperpanjang jam layanan.")
    elif avg_demand > 10:
        insights.append("Beban pasien stabil. Pastikan jadwal dokter terisi penuh.")
    else:
        insights.append("Beban pasien cenderung rendah (Sepi).")

    if avg_interval < 14:
        insights.append("Pola kontrol pasien cukup rapat (Kontrol rutin/akut).")
    else:
        insights.append("Pasien jarang kembali dalam waktu dekat (Kontrol bulanan).")
    return insights

# ================= 3. ENDPOINTS =================

@app.route('/predict', methods=['POST'])
@app.route('/predict-7days', methods=['POST'])
@app.route('/predict-demand-7days', methods=['POST'])
def predict():
    if not model_data:
        load_model()
        if not model_data:
            return jsonify({"error": "Model belum dilatih"}), 500

    data = request.get_json()
    start_date_str = data.get('start_date', datetime.now().strftime("%Y-%m-%d"))
    
    try:
        start_date = datetime.strptime(start_date_str, "%Y-%m-%d")
    except:
        return jsonify({"error": "Format tanggal salah"}), 400

    # Demand Forecast
    demand_f = calculate_forecast(model_data['demand'], start_date)
    
    # Interval Forecast
    interval_f = calculate_forecast(model_data['interval'], start_date)

    avg_demand = np.mean([f['nilai'] for f in demand_f])
    avg_interval = np.mean([f['nilai'] for f in interval_f])

    return jsonify({
        "status": "success",
        "method": model_data['method'],
        "prediksi": round(avg_demand),
        "summary": {
            "avg_demand": round(avg_demand, 1),
            "avg_interval": round(avg_interval, 1),
            "insights": generate_insights(avg_demand, avg_interval)
        },
        "data": {
            "demand": demand_f,
            "interval": interval_f
        }
    })

if __name__ == '__main__':
    print("🚀 Flask Server starting on http://127.0.0.1:5050")
    app.run(host='0.0.0.0', port=5050, debug=True)