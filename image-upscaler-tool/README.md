# Image Upscaler Tool

A fully local image upscaler — **no cloud, no API keys, no internet required at runtime**.

Upload a PNG / JPG / JPEG / WebP image, pick 2× or 4×, and download the upscaled result in seconds.

---

## How it works

| Layer | Stack | Notes |
|-------|-------|-------|
| Frontend | React + Vite | Runs on port 3000 |
| Backend | Python + FastAPI | Runs on port 8000 |
| Upscaling | Pillow LANCZOS + Unsharp Mask | High-quality, no model files |

The backend upscales using **LANCZOS resampling** followed by an **unsharp-mask sharpening pass** — both from Pillow, zero heavyweight dependencies.

---

## Installation & Running

### Prerequisites

| Requirement | Minimum version |
|-------------|----------------|
| Python | 3.9+ |
| Node.js | 18+ |
| npm | 9+ |

---

### Windows — one command

```bat
cd image-upscaler-tool
start.bat
```

The script creates a Python venv, installs deps, starts the backend, then starts the frontend.

---

### macOS / Linux — one command

```bash
cd image-upscaler-tool
chmod +x start.sh
./start.sh
```

---

### Manual start (if you prefer)

**Backend:**

```bash
cd backend
python -m venv venv
# Windows:  venv\Scripts\activate
# Mac/Linux: source venv/bin/activate
pip install -r requirements.txt
uvicorn app.main:app --reload --port 8000
```

**Frontend:**

```bash
cd frontend
npm install
npm run dev
```

Then open **http://localhost:3000**.

---

## Changing the app name

Edit **one file** — everything else updates automatically:

| File | Key |
|------|-----|
| `backend/app/config.py` | `APP_NAME = "Image Upscaler Tool"` |
| `frontend/src/config.js` | `export const APP_NAME = "Image Upscaler Tool"` |

The name propagates to: FastAPI docs title, browser tab title, page heading, footer.

---

## API Endpoints

### `GET /`
Health check.

```json
{
  "app": "Image Upscaler Tool",
  "version": "1.0.0",
  "status": "running",
  "supported_scales": [2, 4]
}
```

---

### `POST /upscale`
Upload and upscale an image.

**Form data:**

| Field | Type | Values |
|-------|------|--------|
| `file` | file | PNG, JPG, JPEG, WEBP |
| `scale_factor` | int | `2` or `4` |

**Response:**

```json
{
  "success": true,
  "filename": "photo_2x_a1b2c3d4.jpg",
  "download_url": "/download/photo_2x_a1b2c3d4.jpg",
  "original_size": { "width": 800, "height": 600 },
  "upscaled_size": { "width": 1600, "height": 1200 },
  "scale_factor": 2
}
```

---

### `GET /download/{filename}`
Download a processed image by filename.

---

## Running Tests

```bash
cd backend
# activate venv first
pytest tests/ -v
```

Expected output: **15 tests, all passing**.

---

## Config Reference

### Backend — `backend/app/config.py`

| Key | Default | Description |
|-----|---------|-------------|
| `APP_NAME` | `"Image Upscaler Tool"` | App name shown everywhere |
| `LOGO_TEXT` | `"ImageUp"` | Badge in the header |
| `DEFAULT_SCALE_FACTOR` | `2` | Pre-selected scale on load |
| `OUTPUT_FOLDER` | `"outputs"` | Where results are saved |
| `MAX_FILE_SIZE_MB` | `30` | Upload size limit |
| `ENABLE_SHARPEN` | `True` | Unsharp-mask after resize |
| `ENABLE_DENOISE` | `False` | cv2 denoise (needs opencv-python) |
| `OUTPUT_QUALITY` | `95` | JPEG/WebP save quality |

### Frontend — `frontend/src/config.js`

| Key | Default | Description |
|-----|---------|-------------|
| `APP_NAME` | `"Image Upscaler Tool"` | Page title and heading |
| `LOGO_TEXT` | `"ImageUp"` | Logo badge |
| `DEFAULT_SCALE_FACTOR` | `2` | Pre-selected scale |
| `API_BASE_URL` | `"http://localhost:8000"` | Backend URL |
| `MAX_FILE_SIZE_MB` | `30` | Client-side size check |

---

## Optional: OpenCV denoise

Install `opencv-python` and set `ENABLE_DENOISE = True` in `backend/app/config.py`:

```bash
pip install opencv-python
```

This runs `fastNlMeansDenoisingColored` before saving — slightly slower but reduces JPEG noise.

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| Backend won't start | Make sure port 8000 is free; check Python ≥ 3.9 |
| Frontend CORS error | Ensure backend is running on port 8000 before opening the UI |
| `pip install` fails | Upgrade pip: `python -m pip install --upgrade pip` |
| Images look blurry | Try 4× scale; enable sharpening (`ENABLE_SHARPEN = True`) |
| Uploads rejected | Check file extension is PNG/JPG/JPEG/WEBP and size < 30 MB |

---

## Project Structure

```
image-upscaler-tool/
├── backend/
│   ├── app/
│   │   ├── config.py       ← central config
│   │   ├── main.py         ← FastAPI app + routes
│   │   ├── upscaler.py     ← upscaling logic
│   │   └── utils.py        ← validation helpers
│   ├── tests/
│   │   └── test_upscaler.py
│   └── requirements.txt
├── frontend/
│   ├── src/
│   │   ├── config.js       ← central config
│   │   ├── App.jsx
│   │   ├── App.css
│   │   ├── main.jsx
│   │   └── components/
│   │       ├── UploadArea.jsx
│   │       ├── ControlPanel.jsx
│   │       └── PreviewPanel.jsx
│   ├── index.html
│   ├── package.json
│   └── vite.config.js
├── outputs/                ← processed images saved here
├── start.bat               ← Windows one-click start
├── start.sh                ← Mac/Linux one-click start
└── README.md
```
