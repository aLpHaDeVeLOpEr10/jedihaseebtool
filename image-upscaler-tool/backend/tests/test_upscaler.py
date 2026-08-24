"""
Tests for the Image Upscaler Tool backend.

Run with:  pytest tests/ -v   (from the backend/ directory)
"""

import io
import os
import sys

import pytest
from fastapi.testclient import TestClient
from PIL import Image

# Make sure the backend package is importable when running from backend/
sys.path.insert(0, os.path.dirname(os.path.dirname(__file__)))

from app.main import app
from app.upscaler import upscale_image

client = TestClient(app)


# ── helpers ──────────────────────────────────────────────────────────────────

def _make_image_bytes(width=100, height=80, fmt="PNG", color=(100, 150, 200)) -> bytes:
    """Create an in-memory test image and return its bytes."""
    img = Image.new("RGB", (width, height), color=color)
    buf = io.BytesIO()
    img.save(buf, format=fmt)
    buf.seek(0)
    return buf.read()


def _post_upscale(image_bytes: bytes, filename: str, scale: int):
    return client.post(
        "/upscale",
        files={"file": (filename, image_bytes, "image/png")},
        data={"scale_factor": str(scale)},
    )


# ── health ───────────────────────────────────────────────────────────────────

def test_root_returns_status():
    resp = client.get("/")
    assert resp.status_code == 200
    body = resp.json()
    assert body["status"] == "running"
    assert "app" in body


# ── upscale core ─────────────────────────────────────────────────────────────

def test_upscale_2x_dimensions():
    img_bytes = _make_image_bytes(100, 80)
    result = upscale_image(img_bytes, 2)
    assert result.width == 200
    assert result.height == 160


def test_upscale_4x_dimensions():
    img_bytes = _make_image_bytes(50, 40)
    result = upscale_image(img_bytes, 4)
    assert result.width == 200
    assert result.height == 160


def test_upscale_preserves_aspect_ratio():
    img_bytes = _make_image_bytes(120, 90)
    for scale in (2, 4):
        result = upscale_image(img_bytes, scale)
        assert result.width / result.height == pytest.approx(120 / 90, rel=1e-3)


def test_upscale_rgba_converted_to_rgb():
    img = Image.new("RGBA", (60, 60), color=(255, 0, 0, 128))
    buf = io.BytesIO()
    img.save(buf, format="PNG")
    result = upscale_image(buf.getvalue(), 2)
    assert result.mode == "RGB"


# ── API: valid uploads ────────────────────────────────────────────────────────

def test_api_upscale_png_2x():
    resp = _post_upscale(_make_image_bytes(), "test.png", 2)
    assert resp.status_code == 200
    body = resp.json()
    assert body["success"] is True
    assert body["scale_factor"] == 2
    assert "download_url" in body


def test_api_upscale_jpg_4x():
    img_bytes = _make_image_bytes(fmt="JPEG")
    resp = _post_upscale(img_bytes, "test.jpg", 4)
    assert resp.status_code == 200
    body = resp.json()
    assert body["scale_factor"] == 4
    assert body["upscaled_size"]["width"] == body["original_size"]["width"] * 4


def test_api_upscale_webp():
    img_bytes = _make_image_bytes(fmt="WEBP")
    resp = _post_upscale(img_bytes, "test.webp", 2)
    assert resp.status_code == 200


def test_api_upscale_jpeg_extension():
    img_bytes = _make_image_bytes(fmt="JPEG")
    resp = _post_upscale(img_bytes, "photo.jpeg", 2)
    assert resp.status_code == 200


# ── API: invalid inputs ───────────────────────────────────────────────────────

def test_api_rejects_unsupported_extension():
    resp = _post_upscale(b"fake gif data", "image.gif", 2)
    assert resp.status_code == 400
    assert "Unsupported" in resp.json()["detail"]


def test_api_rejects_invalid_scale_factor():
    resp = _post_upscale(_make_image_bytes(), "test.png", 3)
    assert resp.status_code == 400


def test_api_rejects_scale_factor_1():
    resp = _post_upscale(_make_image_bytes(), "test.png", 1)
    assert resp.status_code == 400


def test_api_rejects_oversized_file():
    from app.config import MAX_FILE_SIZE_MB

    # Build a bytes object just over the limit (simulate, not a real image)
    big = b"X" * int(MAX_FILE_SIZE_MB * 1024 * 1024 + 1)
    resp = client.post(
        "/upscale",
        files={"file": ("big.png", big, "image/png")},
        data={"scale_factor": "2"},
    )
    assert resp.status_code == 413


# ── output file creation ──────────────────────────────────────────────────────

def test_output_file_is_created():
    from app.config import OUTPUT_FOLDER

    resp = _post_upscale(_make_image_bytes(), "checkfile.png", 2)
    assert resp.status_code == 200
    filename = resp.json()["filename"]
    assert os.path.isfile(os.path.join(OUTPUT_FOLDER, filename))


# ── download endpoint ─────────────────────────────────────────────────────────

def test_download_endpoint():
    # First create a file via upscale
    resp = _post_upscale(_make_image_bytes(), "dl_test.png", 2)
    assert resp.status_code == 200
    filename = resp.json()["filename"]

    dl = client.get(f"/download/{filename}")
    assert dl.status_code == 200
    assert len(dl.content) > 0


def test_download_missing_file_returns_404():
    resp = client.get("/download/nonexistent_file_xyz.png")
    assert resp.status_code == 404


def test_download_path_traversal_blocked():
    resp = client.get("/download/../../etc/passwd")
    assert resp.status_code == 404
