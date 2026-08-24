import { useRef, useState } from "react";
import { SUPPORTED_FORMATS, MAX_FILE_SIZE_MB } from "../config";

export default function UploadArea({ onFileSelected, disabled }) {
  const inputRef = useRef(null);
  const [dragging, setDragging] = useState(false);

  const accept = SUPPORTED_FORMATS.map((f) => `image/${f.toLowerCase()}`).join(",");

  function handleFile(file) {
    if (!file) return;
    const ext = file.name.split(".").pop().toUpperCase();
    if (!SUPPORTED_FORMATS.includes(ext)) {
      alert(`Unsupported format: .${ext}\nAllowed: ${SUPPORTED_FORMATS.join(", ")}`);
      return;
    }
    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
      alert(`File too large. Maximum size is ${MAX_FILE_SIZE_MB} MB.`);
      return;
    }
    onFileSelected(file);
  }

  function onInputChange(e) {
    handleFile(e.target.files[0]);
    e.target.value = "";
  }

  function onDrop(e) {
    e.preventDefault();
    setDragging(false);
    handleFile(e.dataTransfer.files[0]);
  }

  return (
    <div
      className={`upload-area ${dragging ? "dragging" : ""} ${disabled ? "disabled" : ""}`}
      onClick={() => !disabled && inputRef.current.click()}
      onDragOver={(e) => { e.preventDefault(); !disabled && setDragging(true); }}
      onDragLeave={() => setDragging(false)}
      onDrop={!disabled ? onDrop : undefined}
    >
      <input
        ref={inputRef}
        type="file"
        accept={accept}
        style={{ display: "none" }}
        onChange={onInputChange}
        disabled={disabled}
      />
      <div className="upload-icon">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
          <polyline points="17 8 12 3 7 8" />
          <line x1="12" y1="3" x2="12" y2="15" />
        </svg>
      </div>
      <p className="upload-primary">Drop image here or click to browse</p>
      <p className="upload-secondary">
        {SUPPORTED_FORMATS.join(", ")} &nbsp;·&nbsp; max {MAX_FILE_SIZE_MB} MB
      </p>
    </div>
  );
}
