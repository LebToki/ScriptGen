<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CapCut SRT Generator - Convert your script to SRT format for easy subtitles in CapCut</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    :root {
      --bg-primary: #0f0f1e;
      --bg-secondary: #1a1a2e;
      --bg-tertiary: #16213e;
      --bg-card: #1e2749;
      --bg-input: #16213e;
      --border-color: #2a3a5c;
      --border-hover: #3d5a80;
      --text-primary: #e8e8e8;
      --text-secondary: #b8c5d6;
      --text-muted: #7a8fa3;
      --accent-primary: #4a90e2;
      --accent-hover: #5ba0f2;
      --accent-secondary: #6c5ce7;
      --success: #00d4aa;
      --warning: #f39c12;
      --error: #e74c3c;
      --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
      --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.4);
      --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.5);
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body {
      background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      overflow-x: hidden;
      line-height: 1.6;
    }
    
    .app-container {
      max-width: 1600px;
      margin: 0 auto;
      padding: 32px 24px;
      min-height: 100vh;
    }
    
    .header {
      margin-bottom: 32px;
      text-align: center;
    }
    
    .header-title {
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }
    
    .header-subtitle {
      font-size: 1rem;
      color: var(--text-secondary);
      font-weight: 400;
      opacity: 0.9;
    }
    
    .main-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 24px;
    }
    
    @media (max-width: 1024px) {
      .main-grid {
        grid-template-columns: 1fr;
      }
    }
    
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      padding: 24px;
      box-shadow: var(--shadow-md);
      transition: var(--transition);
      backdrop-filter: blur(10px);
    }
    
    .card:hover {
      border-color: var(--border-hover);
      box-shadow: var(--shadow-lg);
      transform: translateY(-2px);
    }
    
    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--border-color);
    }
    
    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .card-title-icon {
      font-size: 1.5rem;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-secondary);
      margin-bottom: 8px;
      letter-spacing: 0.3px;
    }
    
    .form-label-required::after {
      content: ' *';
      color: var(--accent-primary);
    }
    
    .form-control {
      width: 100%;
      background: var(--bg-input);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-sm);
      padding: 12px 16px;
      font-size: 0.95rem;
      font-family: inherit;
      transition: var(--transition);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--accent-primary);
      box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
      background: var(--bg-tertiary);
    }
    
    .form-control::placeholder {
      color: var(--text-muted);
      opacity: 0.6;
    }
    
    textarea.form-control {
      min-height: 300px;
      resize: vertical;
      font-family: 'Courier New', monospace;
      line-height: 1.7;
    }
    
    .controls-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
      .controls-grid {
        grid-template-columns: 1fr;
      }
    }
    
    .input-group {
      position: relative;
    }
    
    .input-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1rem;
    }
    
    .input-with-icon {
      padding-left: 40px;
    }
    
    .button-group {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-top: 24px;
    }
    
    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-family: inherit;
      letter-spacing: 0.3px;
      position: relative;
      overflow: hidden;
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }
    
    .btn:hover::before {
      width: 300px;
      height: 300px;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
      color: #fff;
      box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
    }
    
    .btn-primary:active {
      transform: translateY(0);
    }
    
    .btn-secondary {
      background: var(--bg-tertiary);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
    }
    
    .btn-secondary:hover {
      background: var(--bg-input);
      border-color: var(--border-hover);
      transform: translateY(-2px);
    }
    
    .btn-success {
      background: linear-gradient(135deg, var(--success) 0%, #00b894 100%);
      color: #fff;
      box-shadow: 0 4px 12px rgba(0, 212, 170, 0.3);
    }
    
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 212, 170, 0.4);
    }
    
    .btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none !important;
    }
    
    .btn-loading {
      pointer-events: none;
    }
    
    .btn-loading::after {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin-left: -8px;
      margin-top: -8px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    .preview-container {
      position: relative;
      min-height: 400px;
    }
    
    .preview-box {
      background: var(--bg-input);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-sm);
      padding: 20px;
      font-family: 'Courier New', monospace;
      font-size: 0.9rem;
      line-height: 1.8;
      white-space: pre-wrap;
      overflow-y: auto;
      max-height: 500px;
      min-height: 400px;
      transition: var(--transition);
    }
    
    .preview-box:focus {
      outline: none;
      border-color: var(--accent-primary);
      box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }
    
    .preview-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100%;
      color: var(--text-muted);
      text-align: center;
      padding: 40px;
    }
    
    .preview-empty-icon {
      font-size: 3rem;
      margin-bottom: 16px;
      opacity: 0.5;
    }
    
    .preview-empty-text {
      font-size: 1rem;
      font-weight: 500;
    }
    
    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid var(--border-color);
    }
    
    .status-message {
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      font-size: 0.9rem;
      font-weight: 500;
      display: none;
      animation: slideIn 0.3s ease-out;
      margin-top: 12px;
    }
    
    .status-message.show {
      display: block;
    }
    
    .status-success {
      background: rgba(0, 212, 170, 0.15);
      color: var(--success);
      border: 1px solid rgba(0, 212, 170, 0.3);
    }
    
    .status-error {
      background: rgba(231, 76, 60, 0.15);
      color: var(--error);
      border: 1px solid rgba(231, 76, 60, 0.3);
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .stats-bar {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-top: 16px;
      padding-top: 16px;
      border-top: 1px solid var(--border-color);
    }
    
    .stat-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    
    .stat-label {
      font-size: 0.75rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .stat-value {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--accent-primary);
    }
    
    .tooltip {
      position: relative;
      cursor: help;
    }
    
    .tooltip::after {
      content: attr(data-tooltip);
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%);
      padding: 8px 12px;
      background: var(--bg-tertiary);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-sm);
      font-size: 0.8rem;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: var(--transition);
      margin-bottom: 8px;
      z-index: 1000;
    }
    
    .tooltip:hover::after {
      opacity: 1;
    }
    
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
      background: rgba(74, 144, 226, 0.15);
      color: var(--accent-primary);
      border: 1px solid rgba(74, 144, 226, 0.3);
    }
    
    @media (max-width: 768px) {
      .app-container {
        padding: 20px 16px;
      }
      
      .header-title {
        font-size: 2rem;
      }
      
      .card {
        padding: 20px;
      }
      
      .button-group {
        flex-direction: column;
      }
      
      .btn {
        width: 100%;
        justify-content: center;
      }
      
      .action-bar {
        flex-direction: column;
        align-items: stretch;
      }
    }
    
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .pulse {
      animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.6;
      }
    }
  </style>
  <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
  <div class="app-container fade-in">
    <div class="header">
      <div class="header-title">🎬 CapCut SRT Generator</div>
      <div class="header-subtitle">Convert your script to SRT format for easy subtitles in CapCut</div>
    </div>
    
    <div class="main-grid">
      <!-- Input Card -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <span class="card-title-icon">📝</span>
            <span>Script Input</span>
          </div>
        </div>
        
        <div class="controls-grid">
          <div class="form-group">
            <label class="form-label tooltip" data-tooltip="Words per second for timing calculation">
              Words per Second
            </label>
            <div class="input-group">
              <span class="input-icon">⚡</span>
              <input type="number" id="wpm" class="form-control input-with-icon" value="3" step="0.1" min="0.5" max="10">
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label tooltip" data-tooltip="Minimum duration for each subtitle block">
              Minimum Duration (s)
            </label>
            <div class="input-group">
              <span class="input-icon">⏱️</span>
              <input type="number" id="min_time" class="form-control input-with-icon" value="1.5" step="0.1" min="0.5" max="10">
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label tooltip" data-tooltip="Extra pause time after punctuation marks">
              Pause Padding (s)
            </label>
            <div class="input-group">
              <span class="input-icon">⏸️</span>
              <input type="number" id="punctuation_pad" class="form-control input-with-icon" value="0.5" step="0.1" min="0" max="2">
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="form-label">Filename (optional)</label>
          <div class="input-group">
            <span class="input-icon">📄</span>
            <input type="text" id="filename" class="form-control input-with-icon" placeholder="my_subtitles">
          </div>
        </div>
        
        <div class="form-group">
          <label class="form-label form-label-required">Script Content</label>
          <textarea id="script" class="form-control" placeholder="Paste your script here...&#10;&#10;The tool will automatically:&#10;• Split into optimal chunks&#10;• Calculate timing based on your settings&#10;• Generate CapCut-compatible SRT format"></textarea>
        </div>
        
        <div class="stats-bar" id="scriptStats" style="display: none;">
          <div class="stat-item">
            <span class="stat-label">Words</span>
            <span class="stat-value" id="wordCount">0</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Characters</span>
            <span class="stat-value" id="charCount">0</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Estimated Duration</span>
            <span class="stat-value" id="estDuration">0s</span>
          </div>
        </div>
        
        <div class="action-bar">
          <div class="button-group">
            <button id="processBtn" class="btn btn-primary">
              <span>⚙️</span>
              <span>Process Script</span>
            </button>
            <button onclick="location.reload()" class="btn btn-secondary">
              <span>🔄</span>
              <span>New Script</span>
            </button>
          </div>
        </div>
        
        <div id="statusMessage" class="status-message"></div>
      </div>
      
      <!-- Preview Card -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <span class="card-title-icon">🔍</span>
            <span>SRT Preview</span>
          </div>
          <div class="badge" id="previewBadge" style="display: none;">Ready</div>
        </div>
        
        <div class="preview-container">
          <div id="previewBox" class="preview-box" contenteditable="false">
            <div class="preview-empty">
              <div class="preview-empty-icon">📋</div>
              <div class="preview-empty-text">Click "Process Script" to generate your SRT file</div>
            </div>
          </div>
        </div>
        
        <div class="action-bar">
          <div class="button-group">
            <button id="copyBtn" class="btn btn-success" disabled>
              <span>📋</span>
              <span>Copy All</span>
            </button>
            <button id="downloadBtn" class="btn btn-primary" disabled>
              <span>⬇️</span>
              <span>Download SRT</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    const previewBox = document.getElementById("previewBox");
    const processBtn = document.getElementById("processBtn");
    const downloadBtn = document.getElementById("downloadBtn");
    const copyBtn = document.getElementById("copyBtn");
    const statusMessage = document.getElementById("statusMessage");
    const scriptStats = document.getElementById("scriptStats");
    const previewBadge = document.getElementById("previewBadge");
    
    let latestFile = "";
    let isProcessing = false;
    
    // Update stats as user types
    const scriptInput = document.getElementById("script");
    const wpmInput = document.getElementById("wpm");
    const minTimeInput = document.getElementById("min_time");
    
    function updateStats() {
      const script = scriptInput.value.trim();
      if (!script) {
        scriptStats.style.display = "none";
        return;
      }
      
      const words = script.split(/\s+/).filter(w => w.length > 0).length;
      const chars = script.length;
      const wpm = parseFloat(wpmInput.value) || 3;
      const minTime = parseFloat(minTimeInput.value) || 1.5;
      const estimatedDuration = Math.max(minTime, words / wpm);
      
      document.getElementById("wordCount").textContent = words.toLocaleString();
      document.getElementById("charCount").textContent = chars.toLocaleString();
      document.getElementById("estDuration").textContent = estimatedDuration.toFixed(1) + "s";
      
      scriptStats.style.display = "flex";
    }
    
    scriptInput.addEventListener("input", updateStats);
    wpmInput.addEventListener("input", updateStats);
    minTimeInput.addEventListener("input", updateStats);
    
    function showStatus(message, type = "success") {
      statusMessage.textContent = message;
      statusMessage.className = `status-message status-${type} show`;
      setTimeout(() => {
        statusMessage.classList.remove("show");
      }, 3000);
    }
    
    function setLoading(loading) {
      isProcessing = loading;
      processBtn.disabled = loading;
      if (loading) {
        processBtn.classList.add("btn-loading");
        processBtn.querySelector("span:last-child").textContent = "Processing...";
      } else {
        processBtn.classList.remove("btn-loading");
        processBtn.querySelector("span:last-child").textContent = "Process Script";
      }
    }
    
    function processScript() {
      const script = scriptInput.value.trim();
      
      if (!script) {
        showStatus("Please enter a script to process", "error");
        scriptInput.focus();
        return;
      }
      
      const wpm = parseFloat(wpmInput.value) || 3;
      const min_time = parseFloat(minTimeInput.value) || 1.5;
      const punctuation_pad = parseFloat(document.getElementById("punctuation_pad").value) || 0.5;
      const filename = document.getElementById("filename").value.trim();
      
      if (wpm < 0.5 || wpm > 10) {
        showStatus("Words per second must be between 0.5 and 10", "error");
        return;
      }
      
      setLoading(true);
      showStatus("Processing your script...", "success");
      
      const formData = new FormData();
      formData.append("script", script);
      formData.append("wpm", wpm);
      formData.append("min_time", min_time);
      formData.append("punctuation_pad", punctuation_pad);
      formData.append("name", filename);
      formData.append("preview_only", "1");
      
      fetch("generate_srt.php", {
        method: "POST",
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then(data => {
        setLoading(false);
        
        if (data.success === false) {
          showStatus(data.error || "An error occurred", "error");
          return;
        }
        
        previewBox.textContent = data.preview;
        previewBox.classList.remove("preview-empty");
        previewBox.style.color = "var(--text-primary)";
        latestFile = data.filename;
        downloadBtn.disabled = false;
        copyBtn.disabled = false;
        previewBadge.style.display = "inline-flex";
        
        if (data.stats) {
          previewBadge.textContent = `${data.stats.subtitle_count} subtitles • ${data.stats.total_duration}s`;
        } else {
          previewBadge.textContent = "Generated";
        }
        
        showStatus(`SRT file generated successfully! ${data.stats ? `(${data.stats.subtitle_count} subtitles, ${data.stats.total_duration}s)` : ''}`, "success");
        
        // Scroll preview into view on mobile
        if (window.innerWidth <= 1024) {
          previewBox.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }
      })
      .catch(error => {
        setLoading(false);
        console.error("Error:", error);
        showStatus("Failed to process script. Please try again.", "error");
      });
    }
    
    function copyToClipboard() {
      const text = previewBox.textContent || previewBox.innerText;
      
      if (!text || text.includes("Click \"Process Script\"")) {
        showStatus("Nothing to copy. Please process a script first.", "error");
        return;
      }
      
      navigator.clipboard.writeText(text).then(() => {
        showStatus("✅ Copied to clipboard!", "success");
        copyBtn.querySelector("span:last-child").textContent = "Copied!";
        setTimeout(() => {
          copyBtn.querySelector("span:last-child").textContent = "Copy All";
        }, 2000);
      }).catch(err => {
        console.error("Failed to copy:", err);
        showStatus("Failed to copy. Please try again.", "error");
      });
    }
    
    function downloadSRT() {
      if (!latestFile) {
        showStatus("No file available to download", "error");
        return;
      }
      
      window.location.href = "generate_srt.php?download=" + latestFile;
      showStatus("Download started...", "success");
    }
    
    // Event listeners
    processBtn.addEventListener("click", processScript);
    copyBtn.addEventListener("click", copyToClipboard);
    downloadBtn.addEventListener("click", downloadSRT);
    
    // Allow Enter key to process (Ctrl/Cmd + Enter)
    scriptInput.addEventListener("keydown", (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === "Enter") {
        e.preventDefault();
        processScript();
      }
    });
    
    // Initialize
    updateStats();
  </script>
</body>
</html>