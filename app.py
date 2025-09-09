"""
# --------------------------------------------
# Original backend code (commented out)
# --------------------------------------------
import io, base64
import numpy as np
from fastapi import FastAPI, File, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from PIL import Image
import tensorflow as tf
import cv2

app = FastAPI()

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

MODEL_PATH = "diabetes_model.h5"
IMG_SIZE = 512
model = tf.keras.models.load_model(MODEL_PATH, compile=False)

def preprocess_image(image_bytes):
    img = Image.open(io.BytesIO(image_bytes)).convert("RGB")
    img = img.resize((IMG_SIZE, IMG_SIZE))
    arr = np.array(img)
    arr = arr[None, ...]
    arr = arr.astype("float32")
    return arr, img

def gradcam_heatmap(img_array, model, last_conv_layer_name="top_conv"):
    grad_model = tf.keras.models.Model(
        [model.inputs], [model.get_layer(last_conv_layer_name).output, model.output]
    )
    with tf.GradientTape() as tape:
        conv_outputs, preds = grad_model(img_array)
        loss = preds[:, 0]
    grads = tape.gradient(loss, conv_outputs)
    pooled_grads = tf.reduce_mean(grads, axis=(0,1,2))
    conv_outputs = conv_outputs[0]
    heatmap = tf.reduce_sum(tf.multiply(pooled_grads, conv_outputs), axis=-1)
    heatmap = tf.maximum(heatmap, 0) / (tf.reduce_max(heatmap) + 1e-8)
    heatmap = heatmap.numpy()
    heatmap = cv2.resize(heatmap, (img_array.shape[2], img_array.shape[1]))
    heatmap = (heatmap * 255).astype("uint8")
    return heatmap

@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    content = await file.read()
    arr, pil_img = preprocess_image(content)
    preds = model.predict(arr, verbose=0)
    prob = float(preds[0][0])
    try:
        heat = gradcam_heatmap(arr, model, last_conv_layer_name="top_conv")
        heat_color = cv2.applyColorMap(heat, cv2.COLORMAP_JET)
        overlay = cv2.addWeighted(np.array(pil_img), 0.7, heat_color, 0.3, 0)
        _, buffer = cv2.imencode(".png", overlay[:, :, ::-1])
        heat_b64 = base64.b64encode(buffer).decode("utf-8")
    except Exception as e:
        heat_b64 = None
    return JSONResponse({"prob_DR": prob, "heatmap_base64": heat_b64})

@app.get("/health")
def health():
    return {"status": "ok"}
"""
"""
# --------------------------------------------
# Dummy backend code for testing (active)
# --------------------------------------------
from fastapi import FastAPI, File, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from PIL import Image
import numpy as np

app = FastAPI()

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

IMG_SIZE = 512

# ------------------------------
# Dummy model predict function
# ------------------------------
def dummy_predict(arr):
    # Return random probability for testing
    prob = np.random.rand()
    diagnosis = "Diabetic Retinopathy Detected" if prob > 0.5 else "No DR detected"
    return diagnosis, prob

# ------------------------------
# Image preprocessing
# ------------------------------
def preprocess_image(image_bytes):
    img = Image.open(io.BytesIO(image_bytes)).convert("RGB")
    img = img.resize((IMG_SIZE, IMG_SIZE))
    arr = np.array(img)
    arr = arr[None, ...]
    return arr, img

# ------------------------------
# Prediction endpoint (dummy)
# ------------------------------
@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    content = await file.read()
    arr, _ = preprocess_image(content)
    diagnosis, prob = dummy_predict(arr)
    return JSONResponse({
        "diagnosis": diagnosis,
        "confidence": prob,
        "heatmap_base64": None  # dummy backend doesn't generate heatmap
    })

# ------------------------------
# Health check endpoint
# ------------------------------
@app.get("/health")
def health():
    return {"status": "ok"}


"""



from fastapi import FastAPI, UploadFile, File
from pydantic import BaseModel

app = FastAPI()

# Root endpoint (for quick test)
@app.get("/")
def root():
    return {"message": "Backend is running!"}

# Example dummy prediction endpoint
@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    # Dummy prediction (replace with real model later)
    return {"diagnosis": "Positive", "confidence": 0.85}
