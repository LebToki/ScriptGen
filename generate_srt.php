<?php
/**
 * ScriptGen - Professional SRT Generator Backend
 * Version: 2.1.0
 * 
 * Handles script processing, SRT generation, and file downloads
 * with enhanced security and error handling
 */

// Security headers
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Error reporting for production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);

// Request size limit (10MB max)
$maxInputSize = 10 * 1024 * 1024;
ini_set('post_max_size', $maxInputSize);
ini_set('upload_max_filesize', $maxInputSize);

// Rate limiting (simple implementation)
$rateLimitFile = __DIR__ . '/.rate_limit';
$rateLimitWindow = 60; // seconds
$rateLimitMax = 30; // requests per window

function checkRateLimit($rateLimitFile, $rateLimitWindow, $rateLimitMax) {
    $now = time();
    $requests = [];
    
    if (file_exists($rateLimitFile)) {
        $data = json_decode(file_get_contents($rateLimitFile), true);
        if ($data && is_array($data)) {
            foreach ($data as $timestamp => $count) {
                if ($now - $timestamp < $rateLimitWindow) {
                    $requests[$timestamp] = $count;
                }
            }
        }
    }
    
    $totalRequests = array_sum($requests);
    if ($totalRequests >= $rateLimitMax) {
        return false;
    }
    
    // Increment counter
    $requests[$now] = isset($requests[$now]) ? $requests[$now] + 1 : 1;
    @file_put_contents($rateLimitFile, json_encode($requests));
    return true;
}

function cleanOutput($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

function stripMarkdown($text) {
    // Remove markdown symbols
    $text = preg_replace('/[#*_`\[\]()+~|^>-]+/', '', $text);
    // Clean up multiple spaces
    $text = preg_replace('/\s+/', ' ', $text);
    // Clean up special Unicode whitespace
    $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);
    return trim($text);
}

/**
 * Sanitize filename to prevent directory traversal and injection
 */
function sanitizeFilename($filename, $default = 'script') {
    // Remove any path components
    $filename = basename($filename);
    // Remove anything except alphanumeric, dash, underscore, and period
    $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $filename);
    // Remove duplicate underscores
    $filename = preg_replace('/_+/', '_', $filename);
    // Limit length
    $filename = substr($filename, 0, 100);
    return !empty($filename) ? $filename : $default;
}

/**
 * Validate and sanitize export path
 */
function validateExportPath($exportPath, $defaultDir) {
    if (empty($exportPath)) {
        return [
            'path' => $defaultDir,
            'relative' => 'srt_files/',
            'valid' => true
        ];
    }
    
    $defaultRealPath = realpath($defaultDir);
    if (!$defaultRealPath) {
        if (!is_dir($defaultDir)) {
            mkdir($defaultDir, 0755, true);
        }
        $defaultRealPath = realpath($defaultDir);
    }

    // Sanitize the requested path to prevent directory traversal
    $safePath = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $exportPath);
    $safePath = trim(str_replace('..', '', $safePath), '/');

    $targetPath = $defaultDir . '/' . $safePath;
    
    // Ensure path is within allowed scope BEFORE creating it
    if (!file_exists($targetPath)) {
        // Only create if we're sure it's inside the default directory
        if (is_writable(dirname($targetPath))) {
            @mkdir($targetPath, 0755, true);
        }
    }
    
    $realPath = realpath($targetPath);

    if ($realPath && is_dir($realPath) && is_writable($realPath)) {
        // Strictly ensure path is within the default directory (no arbitrary /var/www access)
        if (strpos($realPath, $defaultRealPath) === 0) {
            return [
                'path' => $realPath,
                'relative' => 'srt_files/' . $safePath,
                'valid' => true
            ];
        }
    }
    
    return [
        'path' => $defaultDir,
        'relative' => 'srt_files/',
        'valid' => true,
        'warning' => 'Custom path not allowed, using default'
    ];
}

try {
    // Rate limiting check
    if (!checkRateLimit($rateLimitFile, $rateLimitWindow, $rateLimitMax)) {
        throw new Exception('Rate limit exceeded. Please wait before making another request.');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check content length
        $contentLength = $_SERVER['CONTENT_LENGTH'] ?? 0;
        if ($contentLength > $maxInputSize) {
            throw new Exception('Request too large. Maximum size is 10MB.');
        }
        
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: $_POST;

        if (empty($data['script'])) {
            throw new Exception('Script text is required');
        }
        
        // Validate script is not too long
        if (strlen($data['script']) > $maxInputSize) {
            throw new Exception('Script too long. Maximum size is 10MB.');
        }
        
        // Get preview_only flag
        $previewOnly = isset($data['preview_only']) && $data['preview_only'] === '1';

        $result = processScript(
            $data['script'],
            floatval($data['wpm'] ?? 3),
            floatval($data['min_time'] ?? 1.5),
            floatval($data['punctuation_pad'] ?? 0.5),
            intval($data['max_length'] ?? 450),
            $data['name'] ?? '',
            floatval($data['fps'] ?? 0),
            floatval($data['start_offset'] ?? 0),
            intval($data['subtitle_gap'] ?? 100),
            $data['export_path'] ?? '',
            $previewOnly,
            $data['capcut_mode'] ?? false,
            $data['capcut_template'] ?? 'standard',
            $data['capcut_style'] ?? 'default'
        );

        $response = [
            'success' => true,
            'preview' => $result['srt'],
            'filename' => $result['filename'],
            'stats' => [
                'subtitle_count' => $result['subtitle_count'],
                'total_duration' => $result['total_duration'],
                'word_count' => $result['word_count']
            ],
            'export_path' => $result['export_path'],
            'saved' => !$previewOnly
        ];
        
        echo cleanOutput($response);
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['download'])) {
        handleDownload($_GET['download']);
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['list'])) {
        listGeneratedFiles();
        exit;
    }

    throw new Exception('Invalid request method');

} catch (Exception $e) {
    http_response_code(400);
    echo cleanOutput([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}

function processScript($script, $wpm, $minTime, $punctuationPad, $maxLength, $name = '', $fps = 0, $startOffset = 0, $subtitleGap = 100, $exportPath = '', $previewOnly = false, $capcutMode = false, $capcutTemplate = 'standard', $capcutStyle = 'default') {
    // Validate inputs
    if ($wpm < 0.5 || $wpm > 10) throw new Exception('Invalid words per second value (0.5-10)');
    if ($minTime < 0.5 || $minTime > 10) throw new Exception('Invalid minimum duration (0.5-10s)');
    if ($punctuationPad < 0 || $punctuationPad > 2) throw new Exception('Invalid punctuation padding (0-2s)');
    if ($maxLength < 100 || $maxLength > 1000) throw new Exception('Invalid max block length (100-1000)');
    if ($startOffset < 0 || $startOffset > 3600) throw new Exception('Invalid start offset (0-3600s)');
    if ($subtitleGap < 0 || $subtitleGap > 1000) throw new Exception('Invalid subtitle gap (0-1000ms)');
    
    // Validate script content
    if (strlen($script) < 1) throw new Exception('Script is empty');
    if (strlen($script) > 10 * 1024 * 1024) throw new Exception('Script exceeds maximum size (10MB)');

    $script = stripMarkdown($script);
    $chunks = splitIntoChunks($script, $maxLength);
    if (empty($chunks)) throw new Exception('No valid chunks found in script');

    $srt = "";
    $index = 1;
    $currentTime = $startOffset; // Apply start offset
    $totalWords = 0;
    $gapSeconds = $subtitleGap / 1000; // Convert ms to seconds

    // CapCut-specific optimizations
    if ($capcutMode) {
        // CapCut optimal settings
        $minTime = max($minTime, 1.0); // Minimum 1 second for CapCut
        $wpm = max($wpm, 2.5); // Slower pace for mobile viewing
        $maxLength = min($maxLength, 60); // Shorter lines for mobile screens
    }

    foreach ($chunks as $line) {
        $wordCount = str_word_count($line);
        $totalWords += $wordCount;
        $duration = max($minTime, $wordCount / $wpm);
        
        // Add punctuation pause
        if (preg_match('/[.!?]$/', trim($line))) {
            $duration += $punctuationPad;
        }

        // CapCut-specific timing adjustments
        if ($capcutMode) {
            // Ensure minimum visibility for mobile screens
            $duration = max($duration, 1.2);
            
            // Add slight padding for better readability
            $duration += 0.2;
        }

        // Align to frame boundary if FPS is set
        if ($fps > 0) {
            $currentTime = alignToFrame($currentTime, $fps);
            $duration = alignToFrame($duration, $fps);
        }

        $start = formatTime($currentTime);
        $end = formatTime($currentTime + $duration);

        // Apply CapCut formatting
        if ($capcutMode) {
            $formattedLine = applyCapCutFormatting($line, $capcutTemplate, $capcutStyle);
            $srt .= "$index\n$start --> $end\n$formattedLine\n\n";
        } else {
            $srt .= "$index\n$start --> $end\n$line\n\n";
        }
        
        // Move to next subtitle with gap
        $currentTime += $duration + $gapSeconds;
        $index++;
    }
    
    $subtitleCount = $index - 1;
    $totalDuration = $currentTime - $startOffset - $gapSeconds; // Exclude final gap

    // Generate safe filename
    $safeName = sanitizeFilename($name, '');
    if ($safeName === '') {
        $safeName = 'script_' . date('Y-m-d_H-i-s');
    }
    $filename = $safeName . '.srt';

    // Determine export directory
    $defaultDir = __DIR__ . '/srt_files';
    $pathValidation = validateExportPath($exportPath, $defaultDir);
    $dir = $pathValidation['path'];
    $usedPath = $pathValidation['relative'];
    
    // Create directory if it doesn't exist
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new Exception('Could not create output directory');
    }

    // Only save file if not preview-only mode
    if (!$previewOnly) {
        $path = "$dir/$filename";
        if (file_put_contents($path, $srt) === false) {
            throw new Exception('Could not save SRT file');
        }
    }

    return [
        'srt' => $srt,
        'filename' => $filename,
        'subtitle_count' => $subtitleCount,
        'total_duration' => round($totalDuration, 2),
        'word_count' => $totalWords,
        'export_path' => $usedPath,
        'warning' => $pathValidation['warning'] ?? null
    ];
}

/**
 * Align time to nearest frame boundary
 */
function alignToFrame($seconds, $fps) {
    if ($fps <= 0) return $seconds;
    $frameTime = 1 / $fps;
    return round($seconds / $frameTime) * $frameTime;
}

/**
 * Split text into chunks - each sentence becomes its own subtitle
 * Long sentences are split at ~80 characters for screen readability
 * Short orphan chunks are merged with previous to avoid single-word subtitles
 */
function splitIntoChunks($text, $maxLength = 450) {
    $rawChunks = [];
    $idealLineLength = min(80, $maxLength);
    $minChunkLength = 20; // Minimum chars before a chunk stands alone
    
    // Split into sentences first
    $sentences = preg_split('/(?<=[.!?])\s+/', $text);

    foreach ($sentences as $sentence) {
        $sentence = trim($sentence);
        if (empty($sentence)) continue;
        
        // If sentence is short enough, add as single subtitle
        if (strlen($sentence) <= $idealLineLength) {
            $rawChunks[] = $sentence;
        } else {
            // Split long sentences into readable chunks
            $words = explode(' ', $sentence);
            $buffer = '';
            
            foreach ($words as $word) {
                $testLength = strlen($buffer . ' ' . $word);
                
                // Start new chunk if adding word exceeds ideal length
                if ($testLength > $idealLineLength && !empty($buffer)) {
                    $rawChunks[] = trim($buffer);
                    $buffer = $word;
                } else {
                    $buffer = trim($buffer . ' ' . $word);
                }
            }
            
            // Add remaining buffer
            if (!empty($buffer)) {
                $rawChunks[] = trim($buffer);
            }
        }
    }
    
    // Merge short orphan chunks with previous chunk
    $chunks = [];
    foreach ($rawChunks as $chunk) {
        if (count($chunks) > 0 && strlen($chunk) < $minChunkLength) {
            // Merge with previous chunk
            $chunks[count($chunks) - 1] .= ' ' . $chunk;
        } else {
            $chunks[] = $chunk;
        }
    }
    
    return $chunks;
}

/**
 * Format seconds to SRT time format (HH:MM:SS,mmm)
 */
function formatTime($seconds) {
    $totalMs = round($seconds * 1000);
    $h = floor($totalMs / 3600000);
    $totalMs %= 3600000;
    $m = floor($totalMs / 60000);
    $totalMs %= 60000;
    $s = floor($totalMs / 1000);
    $ms = $totalMs % 1000;
    return sprintf("%02d:%02d:%02d,%03d", $h, $m, $s, $ms);
}

/**
 * Handle file downloads
 */
function handleDownload($filename) {
    $dir = __DIR__ . '/srt_files';
    $safeName = sanitizeFilename($filename); // Prevent directory traversal
    $path = "$dir/$safeName";

    // Verify file exists and is within allowed directory
    $realPath = realpath($path);
    if (!$realPath || !file_exists($realPath)) {
        throw new Exception('File not found');
    }
    
    // Ensure file is within allowed directory
    $realDir = realpath($dir);
    if (strpos($realPath, $realDir) !== 0) {
        throw new Exception('Access denied');
    }

    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $safeName . '"');
    header('Content-Length: ' . filesize($realPath));
    header('Cache-Control: no-cache');
    header('X-Content-Type-Options: nosniff');
    readfile($realPath);
    exit;
}

/**
 * Apply CapCut-specific formatting to subtitle text
 */
function applyCapCutFormatting($text, $template = 'standard', $style = 'default') {
    // Remove any existing formatting
    $text = strip_tags($text);
    $text = trim($text);
    
    // Template-specific formatting
    switch ($template) {
        case 'social_media':
            // Add emojis for social media engagement
            $text = addSocialMediaEmojis($text);
            break;
        case 'educational':
            // Add emphasis for educational content
            $text = addEducationalFormatting($text);
            break;
        case 'entertainment':
            // Add fun formatting for entertainment
            $text = addEntertainmentFormatting($text);
            break;
        default:
            // Standard CapCut formatting
            $text = addStandardCapCutFormatting($text);
    }
    
    // Style-specific enhancements
    switch ($style) {
        case 'bold':
            $text = addBoldFormatting($text);
            break;
        case 'italic':
            $text = addItalicFormatting($text);
            break;
        case 'highlight':
            $text = addHighlightFormatting($text);
            break;
    }
    
    return $text;
}

/**
 * Add social media emojis to text
 */
function addSocialMediaEmojis($text) {
    // Add relevant emojis based on content
    $emojis = [
        '!' => '🔥',
        '?' => '🤔',
        'love' => '❤️',
        'amazing' => '✨',
        'cool' => '😎',
        'fun' => '🎉',
        'great' => '👍',
        'best' => '🏆'
    ];
    
    foreach ($emojis as $word => $emoji) {
        if (stripos($text, $word) !== false) {
            $text .= ' ' . $emoji;
            break; // Only add one emoji per subtitle
        }
    }
    
    return $text;
}

/**
 * Add educational formatting
 */
function addEducationalFormatting($text) {
    // Add numbering or bullet points for educational content
    if (preg_match('/^(step|tip|fact|note|remember|important)/i', $text)) {
        $text = '📌 ' . $text;
    } elseif (preg_match('/^(first|second|third|next|then)/i', $text)) {
        $text = '➡️ ' . $text;
    }
    
    return $text;
}

/**
 * Add entertainment formatting
 */
function addEntertainmentFormatting($text) {
    // Add fun elements for entertainment content
    if (preg_match('/(fun|exciting|awesome|cool)/i', $text)) {
        $text = '🎬 ' . $text . ' 🎬';
    }
    
    return $text;
}

/**
 * Add standard CapCut formatting
 */
function addStandardCapCutFormatting($text) {
    // Ensure proper capitalization and punctuation
    $text = ucfirst(strtolower($text));
    
    // Add proper ending punctuation if missing
    if (!preg_match('/[.!?]$/', $text)) {
        $text .= '.';
    }
    
    return $text;
}

/**
 * Add bold formatting for emphasis
 */
function addBoldFormatting($text) {
    // Convert to CapCut bold format (using asterisks)
    return '**' . $text . '**';
}

/**
 * Add italic formatting
 */
function addItalicFormatting($text) {
    // Convert to CapCut italic format (using underscores)
    return '_' . $text . '_';
}

/**
 * Add highlight formatting
 */
function addHighlightFormatting($text) {
    // Convert to CapCut highlight format (using backticks)
    return '`' . $text . '`';
}

/**
 * List all generated SRT files
 */
function listGeneratedFiles() {
    $dir = __DIR__ . '/srt_files';
    
    if (!is_dir($dir)) {
        echo cleanOutput([
            'success' => true,
            'files' => [],
            'count' => 0
        ]);
        return;
    }
    
    $files = [];
    $scanDir = scandir($dir);
    
    if ($scanDir) {
        foreach ($scanDir as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'srt') {
                $filePath = "$dir/$file";
                $files[] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'modified' => filemtime($filePath),
                    'path' => 'srt_files/' . $file
                ];
            }
        }
    }
    
    // Sort by modified time (newest first)
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    echo cleanOutput([
        'success' => true,
        'files' => $files,
        'count' => count($files)
    ]);
}
