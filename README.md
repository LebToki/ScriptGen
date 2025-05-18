CapCut SRT Generator (PHP + JS)
===============================

Version: 1.3.0

Last Updated: May 2025
Author: Tarek Tarabichi
License: MIT

An open-source, browser-based tool to convert scripts into CapCut-compatible .srt subtitle files, optimized for voiceover and text-to-speech clarity.

DESCRIPTION
-----------
CapCut SRT Generator allows content creators, editors, and educators to instantly transform written scripts into perfectly timed subtitle files. It splits your content into safe blocks, ensures clean speech flow, and avoids common pitfalls in auto-voiceovers.

FEATURES
--------
- Instant Subtitle Generation – Convert plain scripts into .srt format
- CapCut Voiceover Compatibility – Auto-splits into 450-character blocks (max 500 safe)
- Improved Sentence Flow – Breaks only on full stops (.) for natural voiceovers
- Smart Cleanup – Strips markdown (###, ##, *, **) to prevent speech artifacts
- Parameter Controls – Customize:
    • Words Per Second (WPS)
    • Minimum duration per block
    • Pause padding for punctuation
- Copy & Download Options – Copy to clipboard or save .srt directly
- Fixed Layout – Scrollable text area and preview box for consistent UI
- Refresh Option – “New Script” button resets input instantly

INSTALLATION & USAGE
---------------------
1. Clone or download the repository:
   git clone https://github.com/LebToki/ScriptGen.git

2. Navigate to the directory:
   cd ScriptGen

3. Ensure PHP is installed (v7.4+ recommended)

4. Make the output folder writable:
   mkdir srt_files
   chmod 775 srt_files

5. Open index.php in your browser (e.g. http://localhost/ScriptGen/index.php)

FILE STRUCTURE
--------------
- index.php        → Frontend with text input, preview, and controls
- generate_srt.php → Backend PHP processor with cleaning + timing logic
- /srt_files/      → Writable folder for generated .srt files

TECH STACK
----------
- PHP (no database)
- HTML/CSS (Responsive Flexbox)
- JavaScript (Clipboard, Fetch API)

VERSION HISTORY
---------------
v1.3.0 – May 2025
- NEW: Splitting only at full stops (.) for smoother CapCut voiceovers
- NEW: Markdown cleanup to remove symbols like ###, ##, *
- Improved compatibility with text-to-speech readers

v1.2.0 – Early May 2025
- CapCut voiceover block support (450 character chunks)
- Added Copy Preview icon and New Script reset button
- Scrollable, consistent layout

v1.0.0 – Initial Release
- SRT generation from script
- Controls for timing (WPS, min duration, pause padding)
- Live preview and download support

ROADMAP
-------
- Speaker label handling (e.g., “Narrator:”)
- Auto line-break reflow for readability
- Light/Dark mode toggle
- SRT validator (e.g., line length, time overlap)

CREDITS
-------
Developed by Tarek Tarabichi
MIT License – Free to use, modify, and distribute

FEEDBACK & CONTRIBUTIONS
-------------------------
Found a bug or want to suggest a feature?
Submit an issue or pull request at:
https://github.com/LebToki/ScriptGen