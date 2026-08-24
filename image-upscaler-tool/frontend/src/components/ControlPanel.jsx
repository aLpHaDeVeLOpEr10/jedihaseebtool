import { DEFAULT_SCALE_FACTOR } from "../config";

export default function ControlPanel({ scaleFactor, onScaleChange, onUpscale, loading, hasFile }) {
  return (
    <div className="control-panel">
      <div className="scale-selector">
        <span className="scale-label">Upscale factor</span>
        <div className="scale-buttons">
          {[2, 4].map((s) => (
            <button
              key={s}
              className={`scale-btn ${scaleFactor === s ? "active" : ""}`}
              onClick={() => onScaleChange(s)}
              disabled={loading}
            >
              {s}×
            </button>
          ))}
        </div>
      </div>

      <button
        className="upscale-btn"
        onClick={onUpscale}
        disabled={!hasFile || loading}
      >
        {loading ? (
          <span className="btn-loading">
            <span className="spinner" />
            Upscaling…
          </span>
        ) : (
          "Upscale Image"
        )}
      </button>
    </div>
  );
}
