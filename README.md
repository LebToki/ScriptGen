# CapCut SRT Generator (PHP + JS)
===============================
An open-source, lightweight tool to convert plain text scripts into CapCut-compatible .SRT subtitle files with accurate timing and formatting.

📌 DESCRIPTION
-------------
This tool allows content creators, video editors, and storytellers to paste their script into a textbox, set timing parameters, and instantly generate subtitles for platforms like CapCut or Premiere Pro.

✅ FEATURES
----------
- Paste your script and generate .srt files instantly
- Adjust Words Per Second (WPS), Minimum Duration, and Pause Padding
- Copy subtitles or download the .srt file
- Clean and responsive 60/40 UI layout with live preview
- Fully runs in-browser with a PHP backend (no database required)

![UI/UX Interface](scriptgen-local-2025-04-18-16_05_26.png)


🚀 HOW TO USE
-------------
1. Clone or download the repository to your localhost or web server.
2. Make sure you have PHP installed (PHP 7.4 or later recommended).
3. Ensure the `/srt_files/` folder exists and is writable:
   - On Linux/Mac:
     mkdir srt_files
     chmod 775 srt_files
4. Open `index.php` in your browser via localhost (e.g. http://localhost/CapCut-SRT/index.php).
5. Paste your text, adjust timing, and click “Process” to preview subtitles.
6. Use the Copy or Download buttons to export your final .srt file.

🧱 FILE STRUCTURE
-----------------
- index.php          → Main interface with input, preview, and controls
- generate_srt.php   → PHP backend script that parses text and generates SRT
- /srt_files/        → Writable folder where .srt files are saved before download

🛠 TECH STACK
------------
- PHP (no database required)
- HTML/CSS (Flexbox layout)
- Vanilla JavaScript (Fetch API, clipboard, file generation)

🎯 ROADMAP (Next Features)
--------------------------
- Auto line splitting for long lines
- Speaker label parsing (e.g. `Narrator:`)
- Dark/Light mode toggle
- SRT structure validation (time overlaps, line length)

👨‍💻 CREDITS
------------
Developed by Tarek Tarabichi
MIT License – Free to use, modify, and distribute.

📬 FEEDBACK & CONTRIBUTIONS
---------------------------
Found a bug? Have a feature idea?
Feel free to open an issue or submit a pull request via GitHub.
