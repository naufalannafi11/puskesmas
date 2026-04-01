from flask import Flask, request, jsonify
import pandas as pd
from sklearn.linear_model import LinearRegression

app = Flask(__name__)

# load dataset
data = pd.read_excel("dataset_prediksi_pasien_3bulan.xlsx")

X = data[['hari','bulan']]
y = data['jumlah_pasien']

model = LinearRegression()
model.fit(X, y)

@app.route('/predict', methods=['POST'])
def predict():
    req = request.json

    hari = int(req['hari'])
    bulan = int(req['bulan'])

    pred = model.predict([[hari, bulan]])

    return jsonify({
        'prediksi': int(pred[0])
    })

if __name__ == '__main__':
    app.run(debug=True)