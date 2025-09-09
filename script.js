/*
const imageUpload = document.getElementById('imageUpload');
const predictButton = document.getElementById('predictButton');
const resultsContainer = document.getElementById('results-container');
const loadingText = document.getElementById('loading');
const diagnosisText = document.getElementById('diagnosisText');
const confidenceText = document.getElementById('confidenceText');

predictButton.addEventListener('click', async () => {
    const file = imageUpload.files[0];

    // Simple validation
    if (!file) {
        alert("Please select an image file first.");
        return;
    }

    // Show loading state and hide previous results
    resultsContainer.classList.remove('hidden');
    loadingText.classList.remove('hidden');
    diagnosisText.textContent = '';
    confidenceText.textContent = '';
    
    // Create a FormData object to send the file
    const formData = new FormData();
    formData.append('file', file);

    try {
        // Send the image to your backend API
        // NOTE: The URL must be your local backend server's address
        const response = await fetch('http://127.0.0.1:8000/predict', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        // Update the UI with the results
        diagnosisText.textContent = result.diagnosis;
        confidenceText.textContent = `Confidence: ${result.confidence.toFixed(2)}`;

    } catch (error) {
        console.error('Error:', error);
        diagnosisText.textContent = "Error during prediction. Please check the backend server.";
        confidenceText.textContent = '';
    } finally {
        // Hide the loading state
        loadingText.classList.add('hidden');
    }
});


*/
// ===== Dummy Code for Testing =====
const imageUpload = document.getElementById('imageUpload');
const predictButton = document.getElementById('predictButton');
const resultsContainer = document.getElementById('results-container');
const loadingText = document.getElementById('loading');
const diagnosisText = document.getElementById('diagnosisText');
const confidenceText = document.getElementById('confidenceText');

predictButton.addEventListener('click', () => {
    const file = imageUpload.files[0];

    if (!file) {
        alert("Please select an image file first.");
        return;
    }

    resultsContainer.classList.remove('hidden');
    loadingText.classList.remove('hidden');
    diagnosisText.textContent = '';
    confidenceText.textContent = '';

    // Simulate prediction after 1 second
    setTimeout(() => {
        loadingText.classList.add('hidden');
        diagnosisText.textContent = "Dummy Diagnosis: Diabetes Detected";
        confidenceText.textContent = "Confidence: 0.85";
    }, 1000);
});