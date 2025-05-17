<!DOCTYPE html><html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CapCut SRT Generator - Convert your script to SRT format for easy subtitles in CapCut</title>
  <style>
    * { box-sizing: border-box; }
    body {
      background-color: #1e1e2f;
      color: #f1f1f1;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 20px;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    h2 { margin-top: 0; }
    .container {
      flex: 1;
      display: flex;
      gap: 20px;
    }
    .left, .right {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .left { flex: 0 0 60%; }
    .right { flex: 0 0 40%; }
    .controls {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
      flex-wrap: wrap;
    }
    .controls label {
      flex: 1;
      display: flex;
      flex-direction: column;
      font-size: 0.8rem;
    }
    input[type=number] {
      background-color: #2e2e40;
      color: #e9e9e9;
      border: 1px solid #444;
      border-radius: 5px;
      padding: 6px 8px;
      font-size: 0.9rem;
    }
    textarea {
      background-color: #2e2e40;
      color: #e9e9e9;
      border: 1px solid #444;
      border-radius: 5px;
      padding: 10px;
      font-family: monospace;
      flex: 1;
      resize: none;
      max-height: 300px;
      overflow-y: auto;
    }
    .preview-box {
      background-color: #2e2e40;
      color: #e9e9e9;
      border: 1px solid #444;
      border-radius: 5px;
      padding: 10px;
      font-family: monospace;
      flex: 1;
      white-space: pre-wrap;
      overflow-y: auto;
    }
    .copy-icon {
      align-self: flex-end;
      margin-bottom: 5px;
      cursor: pointer;
      font-size: 1rem;
      background: #3e3e5c;
      padding: 5px 10px;
      border-radius: 4px;
    }
    .copy-icon:hover {
      background: #4a90e2;
    }
    .bottom-bar {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin-top: 10px;
    }
    button {
      background-color: #4a90e2;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }
    button:disabled {
      background-color: #555;
    }
    .status {
      margin-top: 5px;
      font-size: 0.9rem;
      color: #99ffbb;
      display: none;
    }
  </style>
  <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
<h2>🎬 CapCut SRT Generator<br>
  <small style="font-size: 0.8rem; font-weight: normal;font-family: 'Poppins', sans-serif;">
    Convert your script to SRT format for easy subtitles in CapCut
  </small>
</h2>
<div class="container">
  <div class="left">
    <div class="controls">
      <label><small>Words per Second:</small>
        <input type="number" id="wpm" value="3" step="0.1" min="0.5">
      </label>
      <label><small>Minimum Duration (s):</small>
        <input type="number" id="min_time" value="1.5" step="0.1" min="0.5">
      </label>
      <label><small>Pause Padding (s):</small>
        <input type="number" id="punctuation_pad" value="0.5" step="0.1" min="0">
      </label>
    </div>
    <textarea id="script" placeholder="Paste your script here..."></textarea>
    <div class="bottom-bar">
      <div>
        <button id="processBtn">⚙️ Process</button>
        <button onclick="location.reload()">🔄 New Script</button>
      </div>
      <div>
        <button id="copyBtn" disabled>📋 Copy All</button>
        <button id="downloadBtn" disabled>⬇️ Download</button>
      </div>
    </div>
    <div id="copyStatus" class="status">✅ Copied!</div>
  </div>
  <div class="right">
    <h3>🔍 SRT Preview</h3>
    <div class="copy-icon" onclick="copyToClipboard()">📋 Copy Preview</div>
    <div id="previewBox" class="preview-box">(Click "Process" to preview)</div>
  </div>
</div>
<script>
  const previewBox = document.getElementById("previewBox");
  const processBtn = document.getElementById("processBtn");
  const downloadBtn = document.getElementById("downloadBtn");
  const copyBtn = document.getElementById("copyBtn");
  const copyStatus = document.getElementById("copyStatus");
  let latestFile = "";function processScript() { const script = document.getElementById("script").value; const wpm = document.getElementById("wpm").value; const min_time = document.getElementById("min_time").value; const punctuation_pad = document.getElementById("punctuation_pad").value;

const formData = new FormData();
formData.append("script", script);
formData.append("wpm", wpm);
formData.append("min_time", min_time);
formData.append("punctuation_pad", punctuation_pad);
formData.append("preview_only", "1");

fetch("generate_srt.php", {
  method: "POST",
  body: formData
})
.then(response => response.json())
.then(data => {
  previewBox.textContent = data.preview;
  latestFile = data.filename;
  downloadBtn.disabled = false;
  copyBtn.disabled = false;
});

}

function copyToClipboard() { navigator.clipboard.writeText(previewBox.textContent).then(() => { copyStatus.style.display = "inline"; setTimeout(() => { copyStatus.style.display = "none"; }, 1500); }); }

processBtn.addEventListener("click", processScript); copyBtn.addEventListener("click", copyToClipboard); downloadBtn.addEventListener("click", () => { if (latestFile) { window.location.href = "generate_srt.php?download=" + latestFile; } }); </script>

</body>
</html>