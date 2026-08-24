import os
import uuid

from fastapi import HTTPException, UploadFile

from app.config import ALLOWED_EXTENSIONS, MAX_FILE_SIZE_MB


def validate_file(file: UploadFile, contents: bytes) -> str:
    """
    Validate extension and size.  Returns the lower-cased extension.
    Raises HTTPException with a clear message on failure.
    """
    name = file.filename or ""
    ext = name.rsplit(".", 1)[-1].lower() if "." in name else ""

    if ext not in ALLOWED_EXTENSIONS:
        raise HTTPException(
            status_code=400,
            detail=(
                f"Unsupported file type '.{ext}'. "
                f"Allowed: {', '.join(sorted(ALLOWED_EXTENSIONS))}"
            ),
        )

    size_mb = len(contents) / (1024 * 1024)
    if size_mb > MAX_FILE_SIZE_MB:
        raise HTTPException(
            status_code=413,
            detail=(
                f"File too large ({size_mb:.1f} MB). "
                f"Maximum allowed size is {MAX_FILE_SIZE_MB} MB."
            ),
        )

    return ext


def make_output_path(original_filename: str, scale_factor: int, output_folder: str) -> str:
    """Build a unique output file path inside *output_folder*."""
    base, ext = os.path.splitext(original_filename)
    if not ext:
        ext = ".png"
    uid = uuid.uuid4().hex[:8]
    filename = f"{base}_{scale_factor}x_{uid}{ext}"
    os.makedirs(output_folder, exist_ok=True)
    return os.path.join(output_folder, filename)
