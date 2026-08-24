"""
Local image upscaling — no cloud, no external API.

Strategy:
  1. Resize with Pillow LANCZOS  (always available, high quality)
  2. Optional unsharp-mask sharpening  (config.ENABLE_SHARPEN)
  3. Optional cv2 fast-NL-means denoise  (config.ENABLE_DENOISE, needs opencv-python)
"""

import io
from PIL import Image, ImageFilter

from app.config import (
    ENABLE_SHARPEN,
    ENABLE_DENOISE,
    SHARPEN_RADIUS,
    SHARPEN_PERCENT,
    SHARPEN_THRESHOLD,
)


def upscale_image(
    image_bytes: bytes,
    scale_factor: int,
    sharpen: bool = ENABLE_SHARPEN,
    denoise: bool = ENABLE_DENOISE,
) -> Image.Image:
    """
    Upscale *image_bytes* by *scale_factor* using LANCZOS resampling.
    Returns a Pillow Image ready to be saved.
    """
    img = Image.open(io.BytesIO(image_bytes))

    # Normalise to RGB, compositing transparency onto white
    img = _to_rgb(img)

    new_w = img.width * scale_factor
    new_h = img.height * scale_factor
    upscaled = img.resize((new_w, new_h), Image.LANCZOS)

    if denoise:
        upscaled = _denoise(upscaled)

    if sharpen:
        upscaled = upscaled.filter(
            ImageFilter.UnsharpMask(
                radius=SHARPEN_RADIUS,
                percent=SHARPEN_PERCENT,
                threshold=SHARPEN_THRESHOLD,
            )
        )

    return upscaled


# ── helpers ──────────────────────────────────────────────────────────────────

def _to_rgb(img: Image.Image) -> Image.Image:
    """Convert any mode to RGB, compositing alpha over white if present."""
    if img.mode == "RGB":
        return img
    if img.mode in ("RGBA", "LA", "PA"):
        bg = Image.new("RGB", img.size, (255, 255, 255))
        if img.mode == "LA":
            img = img.convert("RGBA")
        if img.mode == "PA":
            img = img.convert("RGBA")
        alpha = img.split()[-1]
        bg.paste(img.convert("RGB"), mask=alpha)
        return bg
    return img.convert("RGB")


def _denoise(img: Image.Image) -> Image.Image:
    """Apply cv2 fast-NL-means denoising if opencv-python is installed."""
    try:
        import cv2
        import numpy as np

        arr = np.array(img)
        arr = cv2.fastNlMeansDenoisingColored(arr, None, 10, 10, 7, 21)
        return Image.fromarray(arr)
    except ImportError:
        return img
