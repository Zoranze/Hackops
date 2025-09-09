from fastapi import FastAPI, UploadFile, File
import tensorflow as tf
import numpy as np
from PIL import Image
import io

app = FastAPI()

# Load your trained model
# model = tf.keras.models.load_model("diabetes_model.h5")

@app.get("/")
def home():
    return {"message": "DR detection API is running"}

@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    # Read and preprocess image
    img = Image.open(io.BytesIO(await file.read())).convert("RGB").resize((224, 224))
    arr = np.array(img) / 255.0
    arr = arr[np.newaxis, ...]  # add batch dimension


    # Temporary dummy prediction
    dummy_prob = 0.75

    return {
        "filename": file.filename,
        "predicted_probability": dummy_prob
    }
    # Make prediction
    # prob = float(model.predict(arr)[0][0])
    # return {"probability": prob}
