#!/usr/bin/env bash
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "============================================================"
echo "  Image Upscaler Tool — Local Startup"
echo "============================================================"
echo ""

# ── Backend ──────────────────────────────────────────────────
echo "[1/2] Starting backend (FastAPI)..."

cd "$SCRIPT_DIR/backend"

if [ ! -d "venv" ]; then
    echo "     Creating Python virtual environment..."
    python3 -m venv venv || { echo "ERROR: python3 not found."; exit 1; }
fi

source venv/bin/activate

echo "     Installing Python dependencies..."
pip install -r requirements.txt --quiet

mkdir -p "$SCRIPT_DIR/outputs"

uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload &
BACKEND_PID=$!
echo "     Backend PID $BACKEND_PID — http://localhost:8000"
echo ""

# ── Frontend ──────────────────────────────────────────────────
echo "[2/2] Starting frontend (Vite)..."

cd "$SCRIPT_DIR/frontend"

if [ ! -d "node_modules" ]; then
    echo "     Installing Node.js dependencies..."
    npm install || { echo "ERROR: npm not found. Install Node.js 18+."; exit 1; }
fi

echo ""
echo "============================================================"
echo "  Open http://localhost:3000 in your browser"
echo "  Press Ctrl+C to stop both servers"
echo "============================================================"
echo ""

# Stop backend when this script exits
trap "kill $BACKEND_PID 2>/dev/null" EXIT

npm run dev
