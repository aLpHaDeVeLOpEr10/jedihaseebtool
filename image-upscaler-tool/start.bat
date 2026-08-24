@echo off
setlocal

echo ============================================================
echo   Image Upscaler Tool — Local Startup
echo ============================================================
echo.

:: ── Backend ─────────────────────────────────────────────────
echo [1/2] Starting backend (FastAPI)...

cd /d "%~dp0backend"

:: Create venv if it doesn't exist
if not exist "venv\Scripts\activate.bat" (
    echo      Creating Python virtual environment...
    python -m venv venv
    if errorlevel 1 (
        echo ERROR: 'python' not found. Install Python 3.9+ and retry.
        pause
        exit /b 1
    )
)

call venv\Scripts\activate.bat

:: Install deps
echo      Installing Python dependencies...
pip install -r requirements.txt --quiet

:: Create outputs folder
if not exist "..\outputs" mkdir "..\outputs"

:: Start FastAPI in background
start "ImageUpscaler-Backend" /min cmd /c "call venv\Scripts\activate.bat && uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload 2>&1"

echo      Backend starting on http://localhost:8000
echo.

:: ── Frontend ─────────────────────────────────────────────────
echo [2/2] Starting frontend (Vite)...

cd /d "%~dp0frontend"

:: Install node modules if needed
if not exist "node_modules" (
    echo      Installing Node.js dependencies...
    npm install
    if errorlevel 1 (
        echo ERROR: 'npm' not found. Install Node.js 18+ and retry.
        pause
        exit /b 1
    )
)

echo      Frontend starting on http://localhost:3000
echo.
echo ============================================================
echo   Open http://localhost:3000 in your browser
echo   Press Ctrl+C here to stop the frontend
echo ============================================================
echo.

npm run dev

endlocal
