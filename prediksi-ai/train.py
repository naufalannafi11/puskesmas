import pandas as pd
import numpy as np
import pickle
import os

# ================= 1. CONFIG =================
EXCEL_FILE = "dataset.xlsx"

# ================= 2. LOAD & CLEAN DATA =================
if not os.path.exists(EXCEL_FILE):
    print(f"❌ File {EXCEL_FILE} tidak ditemukan!")
    exit()

df = pd.read_excel(EXCEL_FILE)
df.columns = df.columns.astype(str).str.strip().str.lower()

# Mapping kolom
df = df.rename(columns={
    'id_pasien': 'pasien_id',
    'id_dokter': 'dokter_id'
})

# Konversi Tanggal
df['tanggal_kunjungan'] = pd.to_datetime(df['tanggal_kunjungan'], errors='coerce')
df = df.dropna(subset=['tanggal_kunjungan'])

# ================= 3. PREPARASI DATA =================

# --- DATASET 1: BEBAN PASIEN (DEMAND) ---
df_demand = df.groupby('tanggal_kunjungan').size().reset_index(name='jumlah_pasien')
df_demand = df_demand.sort_values('tanggal_kunjungan')

# --- DATASET 2: INTERVAL KUNJUNGAN ---
df_sorted = df.sort_values(['pasien_id', 'tanggal_kunjungan'])
df_sorted['next_visit'] = df_sorted.groupby('pasien_id')['tanggal_kunjungan'].shift(-1)
df_sorted['interval_hari'] = (df_sorted['next_visit'] - df_sorted['tanggal_kunjungan']).dt.days
df_interval = df_sorted.dropna(subset=['interval_hari']).groupby('tanggal_kunjungan')['interval_hari'].mean().reset_index()
df_interval = df_interval.sort_values('tanggal_kunjungan')

# ================= 4. HOLT'S LINEAR TREND (DOUBLE EIS) =================
# Mencari Level (L) dan Trend (T)
# Lt = alpha * Yt + (1-alpha) * (Lt-1 + Tt-1)
# Tt = beta * (Lt - Lt-1) + (1-beta) * Tt-1

def train_holt(series, alpha=0.3, beta=0.1):
    if len(series) < 2: 
        return series[0] if len(series) > 0 else 0, 0
    
    level = series[0]
    trend = series[1] - series[0]
    
    for i in range(1, len(series)):
        val = series[i]
        last_level = level
        level = alpha * val + (1 - alpha) * (level + trend)
        trend = beta * (level - last_level) + (1 - beta) * trend
        
    return level, trend

# Training
last_level_d, last_trend_d = train_holt(df_demand['jumlah_pasien'].values, alpha=0.5, beta=0.2)
last_level_i, last_trend_i = train_holt(df_interval['interval_hari'].values, alpha=0.4, beta=0.1)

# ================= 5. SEASONAL MULTIPLIER (DAY OF WEEK) =================
# Hitung bobot per hari (0-6) dibanding rata-rata total
def get_seasonal_multipliers(df_target, col_name):
    df_copy = df_target.copy()
    df_copy['dow'] = df_copy['tanggal_kunjungan'].dt.dayofweek
    overall_mean = df_copy[col_name].mean()
    if overall_mean == 0: return {i: 1.0 for i in range(7)}
    
    dow_means = df_copy.groupby('dow')[col_name].mean()
    multipliers = (dow_means / overall_mean).to_dict()
    
    # Isi hari yang kosong dengan 1.0
    for i in range(7):
        if i not in multipliers: multipliers[i] = 1.0
    return multipliers

multi_demand = get_seasonal_multipliers(df_demand, 'jumlah_pasien')
multi_interval = get_seasonal_multipliers(df_interval, 'interval_hari')

# ================= 6. SAVE MODEL =================
model_data = {
    'method': 'Holt Linear Trend + Seasonal Multiplier',
    'demand': {
        'level': last_level_d,
        'trend': last_trend_d,
        'multipliers': multi_demand
    },
    'interval': {
        'level': last_level_i,
        'trend': last_trend_i,
        'multipliers': multi_interval
    }
}

with open("model_forecast_eis.pkl", "wb") as f:
    pickle.dump(model_data, f)

print("✅ MODEL DOUBLE EIS + SEASONAL BERHASIL DILATIH")
print(f"📈 Demand -> Level: {last_level_d:.2f}, Trend: {last_trend_d:.2f}")
print(f"🕒 Interval -> Level: {last_level_i:.2f}, Trend: {last_trend_i:.2f}")