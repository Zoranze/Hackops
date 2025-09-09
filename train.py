import torch
import torch.nn as nn
import torch.optim as optim
from torch.utils.data import Dataset, DataLoader
import pandas as pd
from sklearn.model_selection import train_test_split
import os
import time
from PIL import Image
import torchvision.transforms as transforms

DEVICE = "cuda" if torch.cuda.is_available() else "cpu"
CSV_PATH = "labels.csv"
MODEL_SAVE_PATH = "final_model.pth"
BATCH_SIZE = 32
LEARNING_RATE = 0.001
EPOCHS = 10
IMAGE_SIZE = 128

class ImageDataset(Dataset):
    def __init__(self, df, transform=None):
        self.dataframe = df
        self.transform = transform

    def __len__(self):
        return len(self.dataframe)

    def __getitem__(self, idx):
        # --- THIS IS THE NEW, ROBUST CODE ---
        try:
            # Try to get path and label
            full_img_path = self.dataframe.iloc[idx, 0]
            label = torch.tensor(int(self.dataframe.iloc[idx, 1]))

            # Try to open the image. This is where the error happened.
            image = Image.open(full_img_path).convert("RGB")
            
            # Apply transformations
            if self.transform:
                image = self.transform(image)
            
            return image, label
            
        except Exception as e:
            # If ANY error happens while opening an image (e.g., it's corrupted)
            # we will print a warning and return a dummy black image.
            # This prevents the entire training process from crashing.
            print(f"\nWARNING: Skipping corrupted/missing image at index {idx}, path: {self.dataframe.iloc[idx, 0]}. Error: {e}")
            black_image = torch.zeros((3, IMAGE_SIZE, IMAGE_SIZE)) # A black image
            dummy_label = torch.tensor(0) # A dummy label
            return black_image, dummy_label

class SimpleCNN(nn.Module):
    def __init__(self, num_classes=5):
        super(SimpleCNN, self).__init__()
        self.conv_stack = nn.Sequential(
            nn.Conv2d(3, 32, kernel_size=3, padding=1), nn.ReLU(), nn.MaxPool2d(2, 2),
            nn.Conv2d(32, 64, kernel_size=3, padding=1), nn.ReLU(), nn.MaxPool2d(2, 2),
            nn.Conv2d(64, 128, kernel_size=3, padding=1), nn.ReLU(), nn.MaxPool2d(2, 2))
        self.classifier = nn.Sequential(
            nn.Flatten(), nn.Linear(128 * 16 * 16, 512),
            nn.ReLU(), nn.Dropout(0.5), nn.Linear(512, num_classes))
    def forward(self, x):
        return self.classifier(self.conv_stack(x))

if __name__ == "__main__":
    print(f"--- Starting session --- Using device: {DEVICE} ---")
    transform = transforms.Compose([
        transforms.Resize((IMAGE_SIZE, IMAGE_SIZE)),
        transforms.ToTensor(),
        transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225])])
    
    print("1. Loading and cleaning data from CSV...")
    df = pd.read_csv(CSV_PATH)
    df.dropna(subset=['label'], inplace=True)
    df['label'] = pd.to_numeric(df['label'], errors='coerce')
    df.dropna(subset=['label'], inplace=True)
    print(f"Cleaned data to {len(df)} samples.")
    
    print("2. Splitting data...")
    train_df, val_df = train_test_split(df, test_size=0.2, random_state=42, stratify=df['label'])

    train_dataset = ImageDataset(df=train_df, transform=transform)
    val_dataset = ImageDataset(df=val_df, transform=transform)
    train_loader = DataLoader(train_dataset, batch_size=BATCH_SIZE, shuffle=True)
    val_loader = DataLoader(val_dataset, batch_size=BATCH_SIZE, shuffle=False)
    
    print("3. Initializing model...")
    model = SimpleCNN().to(DEVICE)
    optimizer = optim.Adam(model.parameters(), lr=LEARNING_RATE)
    criterion = nn.CrossEntropyLoss()
    
    print("\n--- Starting Training ---")
    start_time = time.time()
    for epoch in range(EPOCHS):
        model.train()
        for images, labels in train_loader:
            images, labels = images.to(DEVICE), labels.to(DEVICE)
            outputs = model(images)
            loss = criterion(outputs, labels)
            optimizer.zero_grad(); loss.backward(); optimizer.step()
        
        model.eval()
        correct, total = 0, 0
        with torch.no_grad():
            for images, labels in val_loader:
                images, labels = images.to(DEVICE), labels.to(DEVICE)
                outputs = model(images); _, predicted = torch.max(outputs.data, 1)
                total += labels.size(0); correct += (predicted == labels).sum().item()
        accuracy = 100 * correct / total
        print(f"Epoch [{epoch+1}/{EPOCHS}], Loss: {loss.item():.4f}, Validation Accuracy: {accuracy:.2f}%")
    end_time = time.time()
    
    print("\n--- Training Complete ---")
    print(f"Total time: {end_time - start_time:.2f} seconds")
    torch.save(model.state_dict(), MODEL_SAVE_PATH)
    print(f"✅ Model saved to {MODEL_SAVE_PATH}")