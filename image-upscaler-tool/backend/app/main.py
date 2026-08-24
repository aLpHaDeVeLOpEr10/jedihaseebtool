"""
Image Upscaler Tool — FastAPI backend
"""

import os

from fastapi import FastAPI, File, Form, HTTPException, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import FileResponse, JSONResponse

from app.config import (
    APP_DESCRIPTION,
    APP_NAME,
    APP_VERSION,
    OUTPUT_FOLDER,
    SUPPORTED_SCALE_FACTORS,
)
from app.upscaler import upscale_image
from app.utils import make_output_path, validate_file

# ── app setup ────────────────────────────────────────────────────────────────

app = FastAPI(
    title=APP_NAME,
    description=APP_DESCRIPTION,
    version=APP_VERSION,
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

os.makedirs(OUTPUT_FOLDER, exist_ok=True)


# ── routes ───────────────────────────────────────────────────────────────────

@app.get("/", summary="Health check")
def root():
    return {
        "app": APP_NAME,
        "version": APP_VERSION,
        "status": "running",
        "supported_scales": SUPPORTED_SCALE_FACTORS,
    }


@app.post("/upscale", summary="Upscale an image")
async def upscale(
    file: UploadFile = File(..., description="Image file to upscale"),
    scale_factor: int = Form(2, description="Upscale factor: 2 or 4"),
):
    if scale_factor not in SUPPORTED_SCALE_FACTORS:
        raise HTTPException(
            status_code=400,
            detail=f"scale_factor must be one of {SUPPORTED_SCALE_FACTORS}",
        )

    contents = await file.read()
    ext = validate_file(file, contents)

    result_img = upscale_image(contents, scale_factor)

    output_path = make_output_path(file.filename or f"image.{ext}", scale_factor, OUTPUT_FOLDER)

    # Pick save format
    fmt = ext.upper()
    if fmt == "JPG":
        fmt = "JPEG"

    save_kwargs = {}
    if fmt in ("JPEG", "WEBP"):
        from app.config import OUTPUT_QUALITY
        save_kwargs["quality"] = OUTPUT_QUALITY

    result_img.save(output_path, format=fmt, **save_kwargs)

    filename = os.path.basename(output_path)
    return JSONResponse({
        "success": True,
        "filename": filename,
        "download_url": f"/download/{filename}",
        "original_size": {
            "width": result_img.width // scale_factor,
            "height": result_img.height // scale_factor,
        },
        "upscaled_size": {
            "width": result_img.width,
            "height": result_img.height,
        },
        "scale_factor": scale_factor,
    })


@app.get("/download/{filename}", summary="Download a processed image")
def download(filename: str):
    # Prevent path traversal
    safe_name = os.path.basename(filename)
    file_path = os.path.join(OUTPUT_FOLDER, safe_name)

    if not os.path.isfile(file_path):
        raise HTTPException(status_code=404, detail="File not found")

    return FileResponse(
        path=file_path,
        filename=safe_name,
        media_type="application/octet-stream",
    )
