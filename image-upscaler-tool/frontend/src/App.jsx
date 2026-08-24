import { useState, useCallback } from "react";
import { APP_NAME, LOGO_TEXT, DEFAULT_SCALE_FACTOR, API_BASE_URL } from "./config";
import UploadArea from "./components/UploadArea";
import ControlPanel from "./components/ControlPanel";
import PreviewPanel from "./components/PreviewPanel";
import "./App.css";

export default function App() {
  const [file, setFile] = useState(null);
  const [originalSrc, setOriginalSrc] = useState(null);
  const [scaleFactor, setScaleFactor] = useState(DEFAULT_SCALE_FACTOR);
  const [loading, setLoading] = useState(false);
  const [result, setResult] = useState(null);
  const [error, setError] = useState(null);

  const handleFileSelected = useCallback((f) => {
    setFile(f);
    setResult(null);
    setError(null);
    const url = URL.createObjectURL(f);
    setOriginalSrc(url);
  }, []);

  async function handleUpscale() {
    if (!file) return;
    setLoading(true);
    setError(null);
    setResult(null);

    try {
      const form = new FormData();
      form.append("file", file);
      form.append("scale_factor", scaleFactor);

      const resp = await fetch(`${API_BASE_URL}/upscale`, {
        method: "POST",
        body: form,
      });

      const data = await resp.json();

      if (!resp.ok) {
        throw new Error(data.detail || `Server error ${resp.status}`);
      }

      setResult(data);
    } catch (err) {
      setError(err.message || "Something went wrong. Is the backend running?");
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="app">
      <header className="app-header">
        <div className="logo">{LOGO_TEXT}</div>
        <h1 className="app-title">{APP_NAME}</h1>
        <p className="app-subtitle">Local upscaling — no cloud, no API keys</p>
      </header>

      <main className="app-main">
        <section className="upload-section">
          <UploadArea onFileSelected={handleFileSelected} disabled={loading} />

          {file && (
            <p className="file-info">
              {file.name} &nbsp;·&nbsp; {(file.size / 1024).toFixed(0)} KB
            </p>
          )}
        </section>

        <ControlPanel
          scaleFactor={scaleFactor}
          onScaleChange={setScaleFactor}
          onUpscale={handleUpscale}
          loading={loading}
          hasFile={!!file}
        />

        {error && (
          <div className="error-banner">
            <strong>Error:</strong> {error}
          </div>
        )}

        {(originalSrc || result) && (
          <PreviewPanel originalSrc={originalSrc} result={result} />
        )}
      </main>

      <footer className="app-footer">
        <p>{APP_NAME} &mdash; runs 100% locally</p>
      </footer>
    </div>
  );
}
