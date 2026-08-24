import { API_BASE_URL } from "../config";

function SizeTag({ label, w, h }) {
  if (!w || !h) return null;
  return (
    <span className="size-tag">
      {label}: {w} × {h}px
    </span>
  );
}

function PreviewCard({ label, src, downloadUrl, filename, size }) {
  return (
    <div className="preview-card">
      <div className="preview-header">
        <span className="preview-label">{label}</span>
        {size && <SizeTag label="Size" w={size.width} h={size.height} />}
      </div>
      <div className="preview-image-wrap">
        {src ? (
          <img src={src} alt={label} className="preview-image" />
        ) : (
          <div className="preview-placeholder">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
            <p>No image yet</p>
          </div>
        )}
      </div>
      {downloadUrl && (
        <a
          className="download-btn"
          href={`${API_BASE_URL}${downloadUrl}`}
          download={filename}
        >
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" y1="15" x2="12" y2="3" />
          </svg>
          Download
        </a>
      )}
    </div>
  );
}

export default function PreviewPanel({ originalSrc, result }) {
  return (
    <div className="preview-panel">
      <PreviewCard
        label="Original"
        src={originalSrc}
        size={result?.original_size}
      />
      <PreviewCard
        label={result ? `Upscaled (${result.scale_factor}×)` : "Upscaled"}
        src={result ? `${API_BASE_URL}${result.download_url}` : null}
        downloadUrl={result?.download_url}
        filename={result?.filename}
        size={result?.upscaled_size}
      />
    </div>
  );
}
