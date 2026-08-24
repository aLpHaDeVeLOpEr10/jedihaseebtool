# ─────────────────────────────────────────────
#  Central config — change any value here and
#  it propagates to the whole backend.
# ─────────────────────────────────────────────

import os

APP_NAME = "Image Upscaler Tool"
LOGO_TEXT = "ImageUp"
APP_VERSION = "1.0.0"
APP_DESCRIPTION = "Local image upscaler — no cloud, no API keys."

# Server
HOST = "0.0.0.0"
PORT = 8000

# Upscaling
DEFAULT_SCALE_FACTOR = 2          # 2 or 4
SUPPORTED_SCALE_FACTORS = [2, 4]

# Files — outputs always land in <project-root>/outputs/ regardless of cwd
_BACKEND_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
_PROJECT_ROOT = os.path.dirname(_BACKEND_DIR)
OUTPUT_FOLDER_NAME = "outputs"    # ← change just this name if needed
OUTPUT_FOLDER = os.path.join(_PROJECT_ROOT, OUTPUT_FOLDER_NAME)

MAX_FILE_SIZE_MB = 30
ALLOWED_EXTENSIONS = {"png", "jpg", "jpeg", "webp"}
OUTPUT_QUALITY = 95               # JPEG/WebP save quality (1-100)

# Processing
ENABLE_SHARPEN = True             # unsharp-mask after resize
ENABLE_DENOISE = False            # cv2 denoise (slower, needs opencv-python)
SHARPEN_RADIUS = 1.5
SHARPEN_PERCENT = 150
SHARPEN_THRESHOLD = 3
