<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ScriptGen - Professional SRT Generator for Video Editors</title>
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="Convert scripts to frame-accurate SRT subtitles for Filmora, Premiere Pro, DaVinci Resolve, and CapCut. Free, browser-based subtitle generator with glassmorphic UI.">
  <meta name="keywords" content="SRT generator, subtitle generator, video editing, Filmora subtitles, Premiere Pro, CapCut, script to SRT, frame-accurate subtitles">
  <meta name="author" content="2TInteractive">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://github.com/LebToki/ScriptGen">
  <meta property="og:title" content="ScriptGen - Professional SRT Generator">
  <meta property="og:description" content="Convert scripts to frame-accurate SRT subtitles for Filmora, Premiere, and more. Free browser-based tool.">
  <meta property="og:image" content="assets/og-banner.png">
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="ScriptGen - SRT Generator">
  <meta name="twitter:description" content="Convert scripts to frame-accurate subtitles for video editors">
  <meta name="twitter:image" content="assets/og-banner.png">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/favicon.png">
  <link rel="apple-touch-icon" href="assets/favicon.png">
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    :root {
      /* Glassmorphic color palette */
      --bg-primary: #0a0a1a;
      --bg-secondary: #0f0f25;
      --bg-gradient: linear-gradient(135deg, #0a0a1a 0%, #1a1a3e 50%, #0f0f25 100%);
      
      /* Glass effects */
      --glass-bg: rgba(255, 255, 255, 0.03);
      --glass-bg-hover: rgba(255, 255, 255, 0.06);
      --glass-border: rgba(255, 255, 255, 0.08);
      --glass-border-hover: rgba(255, 255, 255, 0.15);
      --glass-blur: blur(20px);
      --glass-glow-primary: 0 0 40px rgba(74, 144, 226, 0.15);
      --glass-glow-accent: 0 0 60px rgba(108, 92, 231, 0.1);
      
      /* Text colors */
      --text-primary: #f0f0f5;
      --text-secondary: #b8c5d6;
      --text-muted: #6a7a8c;
      
      /* Accent colors */
      --accent-primary: #4a90e2;
      --accent-secondary: #6c5ce7;
      --accent-tertiary: #00d4aa;
      --accent-gradient: linear-gradient(135deg, #4a90e2 0%, #6c5ce7 50%, #a855f7 100%);
      
      /* Status colors */
      --success: #00d4aa;
      --warning: #f39c12;
      --error: #e74c3c;
      
      /* Shadows and effects */
      --shadow-glass: 0 8px 32px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.05);
      --shadow-glow: 0 0 50px rgba(74, 144, 226, 0.2);
      
      /* Border radius */
      --radius-sm: 12px;
      --radius-md: 16px;
      --radius-lg: 24px;
      
      /* Transitions */
      --transition-fast: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body {
      background: var(--bg-gradient);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      overflow-x: hidden;
      line-height: 1.6;
      position: relative;
    }
    
    /* Animated background particles */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(ellipse at 20% 20%, rgba(74, 144, 226, 0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 80%, rgba(108, 92, 231, 0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(168, 85, 247, 0.05) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
      animation: ambientPulse 8s ease-in-out infinite;
    }
    
    @keyframes ambientPulse {
      0%, 100% { opacity: 0.6; }
      50% { opacity: 1; }
    }
    
    .app-container {
      max-width: 1600px;
      margin: 0 auto;
      padding: 16px 24px;
      min-height: 100vh;
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
    }
    
    /* Compact Header */
    .header {
      margin-bottom: 16px;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }
    
    .header-title {
      font-size: 1.5rem;
      font-weight: 700;
      background: var(--accent-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 0;
    }
    
    .header-subtitle {
      font-size: 0.85rem;
      color: var(--text-muted);
      font-weight: 400;
    }
    
    .header-divider {
      color: var(--glass-border);
    }
    
    .main-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 28px;
      margin-bottom: 28px;
    }
    
    @media (max-width: 1024px) {
      .main-grid {
        grid-template-columns: 1fr;
      }
    }
    
    /* Glassmorphic card */
    .card {
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      -webkit-backdrop-filter: var(--glass-blur);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-lg);
      padding: 28px;
      box-shadow: var(--shadow-glass);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }
    
    /* Animated border glow */
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border-radius: var(--radius-lg);
      padding: 1px;
      background: linear-gradient(135deg, rgba(74, 144, 226, 0.3), rgba(108, 92, 231, 0.1), rgba(168, 85, 247, 0.3));
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      opacity: 0;
      transition: var(--transition);
      pointer-events: none;
    }
    
    .card:hover {
      background: var(--glass-bg-hover);
      border-color: var(--glass-border-hover);
      box-shadow: var(--shadow-glass), var(--glass-glow-primary);
      transform: translateY(-4px);
    }
    
    .card:hover::before {
      opacity: 1;
    }
    
    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      padding-bottom: 18px;
      border-bottom: 1px solid var(--glass-border);
    }
    
    .card-title {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .card-title-icon {
      font-size: 1.6rem;
      filter: drop-shadow(0 0 10px rgba(74, 144, 226, 0.5));
    }
    
    /* Form elements with glass effect */
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      font-size: 0.85rem;
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
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(10px);
      color: var(--text-primary);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-sm);
      padding: 14px 16px;
      font-size: 0.95rem;
      font-family: inherit;
      transition: var(--transition);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--accent-primary);
      box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15), 0 0 20px rgba(74, 144, 226, 0.1);
      background: rgba(0, 0, 0, 0.4);
    }
    
    .form-control::placeholder {
      color: var(--text-muted);
      opacity: 0.7;
    }
    
    textarea.form-control {
      min-height: 280px;
      resize: vertical;
      font-family: 'JetBrains Mono', 'Courier New', monospace;
      line-height: 1.8;
    }
    
    select.form-control {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23b8c5d6' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
    }
    
    /* Controls grid for timing */
    .controls-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 20px;
    }
    
    .controls-grid-4 {
      grid-template-columns: repeat(4, 1fr);
    }
    
    @media (max-width: 768px) {
      .controls-grid, .controls-grid-4 {
        grid-template-columns: 1fr 1fr;
      }
    }
    
    @media (max-width: 500px) {
      .controls-grid, .controls-grid-4 {
        grid-template-columns: 1fr;
      }
    }
    
    .input-group {
      position: relative;
    }
    
    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1rem;
    }
    
    .input-with-icon {
      padding-left: 44px;
    }
    
    /* Section dividers */
    .section-divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 24px 0 20px;
      color: var(--text-muted);
      font-size: 0.8rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .section-divider::before,
    .section-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
    }
    
    /* Collapsible panel */
    .collapsible-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 16px;
      background: rgba(0, 0, 0, 0.2);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: var(--transition);
      margin-bottom: 0;
    }
    
    .collapsible-header:hover {
      background: rgba(0, 0, 0, 0.3);
      border-color: var(--glass-border-hover);
    }
    
    .collapsible-header.active {
      border-radius: var(--radius-sm) var(--radius-sm) 0 0;
      margin-bottom: 0;
    }
    
    .collapsible-title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    .collapsible-icon {
      transition: var(--transition);
    }
    
    .collapsible-header.active .collapsible-icon {
      transform: rotate(180deg);
    }
    
    .collapsible-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out;
      background: rgba(0, 0, 0, 0.15);
      border: 1px solid var(--glass-border);
      border-top: none;
      border-radius: 0 0 var(--radius-sm) var(--radius-sm);
    }
    
    .collapsible-content.active {
      max-height: 500px;
    }
    
    .collapsible-body {
      padding: 20px;
    }
    
    /* Button styles */
    .button-group {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-top: 24px;
    }
    
    .btn {
      padding: 14px 28px;
      border: none;
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-family: inherit;
      letter-spacing: 0.3px;
      position: relative;
      overflow: hidden;
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn:hover::before {
      left: 100%;
    }
    
    .btn-primary {
      background: var(--accent-gradient);
      color: #fff;
      box-shadow: 0 4px 20px rgba(74, 144, 226, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(74, 144, 226, 0.4), 0 0 40px rgba(74, 144, 226, 0.2);
    }
    
    .btn-secondary {
      background: rgba(255, 255, 255, 0.05);
      color: var(--text-primary);
      border: 1px solid var(--glass-border);
      backdrop-filter: blur(10px);
    }
    
    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: var(--glass-border-hover);
      transform: translateY(-2px);
    }
    
    .btn-success {
      background: linear-gradient(135deg, var(--success) 0%, #00b894 100%);
      color: #fff;
      box-shadow: 0 4px 20px rgba(0, 212, 170, 0.3);
    }
    
    .btn-success:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(0, 212, 170, 0.4);
    }
    
    .btn:disabled {
      opacity: 0.4;
      cursor: not-allowed;
      transform: none !important;
    }
    
    .btn-loading::after {
      content: '';
      position: absolute;
      width: 18px;
      height: 18px;
      top: 50%;
      left: 50%;
      margin-left: -9px;
      margin-top: -9px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    /* Preview container */
    .preview-container {
      position: relative;
      min-height: 400px;
    }
    
    .preview-box {
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(10px);
      color: var(--text-primary);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-sm);
      padding: 20px;
      font-family: 'JetBrains Mono', 'Courier New', monospace;
      font-size: 0.85rem;
      line-height: 1.9;
      white-space: pre-wrap;
      overflow-y: auto;
      max-height: 500px;
      min-height: 400px;
      transition: var(--transition);
    }
    
    .preview-box::-webkit-scrollbar {
      width: 8px;
    }
    
    .preview-box::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 4px;
    }
    
    .preview-box::-webkit-scrollbar-thumb {
      background: var(--glass-border-hover);
      border-radius: 4px;
    }
    
    .preview-box::-webkit-scrollbar-thumb:hover {
      background: var(--accent-primary);
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
      font-size: 4rem;
      margin-bottom: 20px;
      opacity: 0.4;
      filter: grayscale(0.5);
    }
    
    .preview-empty-text {
      font-size: 1rem;
      font-weight: 500;
      font-family: 'Inter', sans-serif;
    }
    
    /* Action bar */
    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid var(--glass-border);
    }
    
    /* Status message with glow */
    .status-message {
      padding: 14px 18px;
      border-radius: var(--radius-sm);
      font-size: 0.9rem;
      font-weight: 500;
      display: none;
      animation: slideIn 0.3s ease-out;
      margin-top: 16px;
      backdrop-filter: blur(10px);
    }
    
    .status-message.show {
      display: block;
    }
    
    .status-success {
      background: rgba(0, 212, 170, 0.1);
      color: var(--success);
      border: 1px solid rgba(0, 212, 170, 0.3);
      box-shadow: 0 0 20px rgba(0, 212, 170, 0.1);
    }
    
    .status-error {
      background: rgba(231, 76, 60, 0.1);
      color: var(--error);
      border: 1px solid rgba(231, 76, 60, 0.3);
      box-shadow: 0 0 20px rgba(231, 76, 60, 0.1);
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
    
    /* Stats bar */
    .stats-bar {
      display: flex;
      gap: 24px;
      flex-wrap: wrap;
      margin-top: 18px;
      padding-top: 18px;
      border-top: 1px solid var(--glass-border);
    }
    
    .stat-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    
    .stat-label {
      font-size: 0.7rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }
    
    .stat-value {
      font-size: 1.4rem;
      font-weight: 700;
      background: var(--accent-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* Badge */
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      background: rgba(74, 144, 226, 0.15);
      color: var(--accent-primary);
      border: 1px solid rgba(74, 144, 226, 0.3);
      box-shadow: 0 0 15px rgba(74, 144, 226, 0.1);
    }
    
    /* Tooltip */
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
      padding: 10px 14px;
      background: rgba(0, 0, 0, 0.9);
      backdrop-filter: blur(10px);
      color: var(--text-primary);
      border: 1px solid var(--glass-border);
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
    
    /* Path input with folder icon */
    .path-input-wrapper {
      display: flex;
      gap: 10px;
    }
    
    .path-input-wrapper .form-control {
      flex: 1;
    }
    
    .btn-icon {
      padding: 14px;
      min-width: 48px;
      justify-content: center;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .app-container {
        padding: 24px 16px;
      }
      
      .header-title {
        font-size: 2.2rem;
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
    
    /* Animations */
    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Help text */
    .help-text {
      font-size: 0.75rem;
      color: var(--text-muted);
      margin-top: 6px;
      opacity: 0.8;
    }
    
    /* Footer */
    .footer {
      text-align: center;
      padding: 30px 20px;
      color: var(--text-muted);
      font-size: 0.85rem;
      border-top: 1px solid var(--glass-border);
      margin-top: 40px;
    }
    
    .footer a {
      color: var(--accent-primary);
      text-decoration: none;
      transition: var(--transition);
    }
    
    .footer a:hover {
      color: var(--accent-secondary);
      text-shadow: 0 0 10px rgba(74, 144, 226, 0.5);
    }
  </style>
</head>
<body>
  <div class="app-container fade-in">
    <div class="header">
      <div class="header-title">🎬 ScriptGen</div>
      <span class="header-divider">|</span>
      <div class="header-subtitle">SRT Generator for Video Editors</div>
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
        
        <!-- Script Name -->
        <div class="form-group">
          <label class="form-label">Script Name</label>
          <div class="input-group">
            <span class="input-icon">🎯</span>
            <input type="text" id="scriptName" class="form-control input-with-icon" placeholder="My Video Script" maxlength="100">
          </div>
          <div class="help-text">Names your project and the exported SRT file</div>
        </div>
        
        <!-- Basic Timing Controls -->
        <div class="section-divider">Timing Controls</div>
        <div class="controls-grid">
          <div class="form-group">
            <label class="form-label tooltip" data-tooltip="Words spoken per second">
              Words/Second
            </label>
            <div class="input-group">
              <span class="input-icon">⚡</span>
              <input type="number" id="wpm" class="form-control input-with-icon" value="3" step="0.1" min="0.5" max="10">
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label tooltip" data-tooltip="Minimum subtitle duration">
              Min Duration (s)
            </label>
            <div class="input-group">
              <span class="input-icon">⏱️</span>
              <input type="number" id="min_time" class="form-control input-with-icon" value="1.5" step="0.1" min="0.5" max="10">
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label tooltip" data-tooltip="Pause after punctuation">
              Pause Padding (s)
            </label>
            <div class="input-group">
              <span class="input-icon">⏸️</span>
              <input type="number" id="punctuation_pad" class="form-control input-with-icon" value="0.5" step="0.1" min="0" max="2">
            </div>
          </div>
        </div>
        
        <!-- Advanced Timing (for Filmora, Premiere, etc.) -->
        <div class="collapsible-header" id="advancedTimingHeader">
          <div class="collapsible-title">
            <span>🎞️</span>
            <span>Advanced Timing (Filmora, Premiere, DaVinci)</span>
          </div>
          <span class="collapsible-icon">▼</span>
        </div>
        <div class="collapsible-content" id="advancedTimingContent">
          <div class="collapsible-body">
            <div class="controls-grid controls-grid-4">
              <div class="form-group">
                <label class="form-label tooltip" data-tooltip="Frame rate for timing alignment">
                  Frame Rate (FPS)
                </label>
                <select id="fps" class="form-control">
                  <option value="0">Auto (No Alignment)</option>
                  <option value="23.976">23.976 (Film)</option>
                  <option value="24">24 (Cinema)</option>
                  <option value="25">25 (PAL)</option>
                  <option value="29.97">29.97 (NTSC)</option>
                  <option value="30" selected>30 (Standard)</option>
                  <option value="50">50 (PAL High)</option>
                  <option value="59.94">59.94 (NTSC High)</option>
                  <option value="60">60 (Smooth)</option>
                </select>
              </div>
              
              <div class="form-group">
                <label class="form-label tooltip" data-tooltip="Offset for all timecodes">
                  Start Offset (s)
                </label>
                <div class="input-group">
                  <span class="input-icon">⏩</span>
                  <input type="number" id="startOffset" class="form-control input-with-icon" value="0" step="0.1" min="0" max="3600">
                </div>
              </div>
              
              <div class="form-group">
                <label class="form-label tooltip" data-tooltip="Minimum gap between subtitles">
                  Subtitle Gap (ms)
                </label>
                <div class="input-group">
                  <span class="input-icon">↔️</span>
                  <input type="number" id="subtitleGap" class="form-control input-with-icon" value="100" step="10" min="0" max="1000">
                </div>
              </div>
              
              <div class="form-group">
                <label class="form-label tooltip" data-tooltip="Maximum characters per block">
                  Max Block Length
                </label>
                <div class="input-group">
                  <span class="input-icon">📏</span>
                  <input type="number" id="maxLength" class="form-control input-with-icon" value="450" step="10" min="100" max="1000">
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Export Settings -->
        <div class="collapsible-header" id="exportSettingsHeader" style="margin-top: 16px;">
          <div class="collapsible-title">
            <span>📁</span>
            <span>Export Settings</span>
          </div>
          <span class="collapsible-icon">▼</span>
        </div>
        <div class="collapsible-content" id="exportSettingsContent">
          <div class="collapsible-body">
            <div class="form-group">
              <label class="form-label">Export Path</label>
              <div class="path-input-wrapper">
                <input type="text" id="exportPath" class="form-control" placeholder="Default: srt_files/">
              </div>
              <div class="help-text">Leave empty to use default folder. Path must exist on server.</div>
            </div>
          </div>
        </div>
        
        <!-- Script Content -->
        <div class="section-divider">Script Content</div>
        <div class="form-group">
          <label class="form-label form-label-required">Your Script</label>
          <textarea id="script" class="form-control" placeholder="Paste your script here...

The tool will automatically:
• Split into optimal chunks for voiceover
• Calculate timing based on your settings
• Generate editor-compatible SRT format
• Align to frame boundaries (if FPS is set)"></textarea>
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
            <span class="stat-label">Est. Duration</span>
            <span class="stat-value" id="estDuration">0s</span>
          </div>
        </div>
        
        <div class="action-bar">
          <div class="button-group">
            <button id="processBtn" class="btn btn-primary">
              <span>⚙️</span>
              <span>Generate SRT</span>
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
              <div class="preview-empty-text">Click "Generate SRT" to create your subtitle file</div>
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
    
    <div class="footer">
      Made with ❤️ by <a href="https://2tinteractive.com" target="_blank">2TInteractive</a> • <a href="https://github.com/LebToki/ScriptGen" target="_blank">GitHub</a> • MIT License
    </div>
  </div>
  
  <script>
    // DOM Elements
    const previewBox = document.getElementById("previewBox");
    const processBtn = document.getElementById("processBtn");
    const downloadBtn = document.getElementById("downloadBtn");
    const copyBtn = document.getElementById("copyBtn");
    const statusMessage = document.getElementById("statusMessage");
    const scriptStats = document.getElementById("scriptStats");
    const previewBadge = document.getElementById("previewBadge");
    
    // Input elements
    const scriptInput = document.getElementById("script");
    const scriptNameInput = document.getElementById("scriptName");
    const wpmInput = document.getElementById("wpm");
    const minTimeInput = document.getElementById("min_time");
    const punctuationPadInput = document.getElementById("punctuation_pad");
    const fpsInput = document.getElementById("fps");
    const startOffsetInput = document.getElementById("startOffset");
    const subtitleGapInput = document.getElementById("subtitleGap");
    const maxLengthInput = document.getElementById("maxLength");
    const exportPathInput = document.getElementById("exportPath");
    
    let latestFile = "";
    let isProcessing = false;
    
    // Collapsible panels
    function setupCollapsible(headerId, contentId) {
      const header = document.getElementById(headerId);
      const content = document.getElementById(contentId);
      
      header.addEventListener("click", () => {
        header.classList.toggle("active");
        content.classList.toggle("active");
      });
    }
    
    setupCollapsible("advancedTimingHeader", "advancedTimingContent");
    setupCollapsible("exportSettingsHeader", "exportSettingsContent");
    
    // Update stats as user types
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
      document.getElementById("estDuration").textContent = formatDuration(estimatedDuration);
      
      scriptStats.style.display = "flex";
    }
    
    function formatDuration(seconds) {
      if (seconds < 60) return seconds.toFixed(1) + "s";
      const mins = Math.floor(seconds / 60);
      const secs = Math.round(seconds % 60);
      return `${mins}m ${secs}s`;
    }
    
    scriptInput.addEventListener("input", updateStats);
    wpmInput.addEventListener("input", updateStats);
    minTimeInput.addEventListener("input", updateStats);
    
    function showStatus(message, type = "success") {
      statusMessage.textContent = message;
      statusMessage.className = `status-message status-${type} show`;
      setTimeout(() => {
        statusMessage.classList.remove("show");
      }, 4000);
    }
    
    function setLoading(loading) {
      isProcessing = loading;
      processBtn.disabled = loading;
      if (loading) {
        processBtn.classList.add("btn-loading");
        processBtn.querySelector("span:last-child").textContent = "Processing...";
      } else {
        processBtn.classList.remove("btn-loading");
        processBtn.querySelector("span:last-child").textContent = "Generate SRT";
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
      const punctuation_pad = parseFloat(punctuationPadInput.value) || 0.5;
      const scriptName = scriptNameInput.value.trim();
      const fps = parseFloat(fpsInput.value) || 0;
      const startOffset = parseFloat(startOffsetInput.value) || 0;
      const subtitleGap = parseInt(subtitleGapInput.value) || 100;
      const maxLength = parseInt(maxLengthInput.value) || 450;
      const exportPath = exportPathInput.value.trim();
      
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
      formData.append("name", scriptName);
      formData.append("fps", fps);
      formData.append("start_offset", startOffset);
      formData.append("subtitle_gap", subtitleGap);
      formData.append("max_length", maxLength);
      formData.append("export_path", exportPath);
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
          previewBadge.textContent = `${data.stats.subtitle_count} subtitles • ${formatDuration(data.stats.total_duration)}`;
        } else {
          previewBadge.textContent = "Generated";
        }
        
        showStatus(`✅ SRT generated! ${data.stats ? `(${data.stats.subtitle_count} subtitles, ${formatDuration(data.stats.total_duration)})` : ''}`, "success");
        
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
      
      if (!text || text.includes("Click \"Generate SRT\"")) {
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
      
      window.location.href = "generate_srt.php?download=" + encodeURIComponent(latestFile);
      showStatus("⬇️ Download started...", "success");
    }
    
    // Event listeners
    processBtn.addEventListener("click", processScript);
    copyBtn.addEventListener("click", copyToClipboard);
    downloadBtn.addEventListener("click", downloadSRT);
    
    // Keyboard shortcut (Ctrl/Cmd + Enter)
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