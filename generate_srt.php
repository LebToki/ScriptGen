<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

function cleanOutput($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

function stripMarkdown($text) {
    // Remove markdown symbols
    $text = preg_replace('/[#*_`]+/', '', $text);
    // Clean up multiple spaces
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: $_POST;

        if (empty($data['script'])) {
            throw new Exception('Script text is required');
        }

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
            $data['export_path'] ?? ''
        );

        echo cleanOutput([
            'success' => true,
            'preview' => $result['srt'],
            'filename' => $result['filename'],
            'stats' => [
                'subtitle_count' => $result['subtitle_count'],
                'total_duration' => $result['total_duration'],
                'word_count' => $result['word_count']
            ],
            'export_path' => $result['export_path']
        ]);
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['download'])) {
        handleDownload($_GET['download']);
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

function processScript($script, $wpm, $minTime, $punctuationPad, $maxLength, $name = '', $fps = 0, $startOffset = 0, $subtitleGap = 100, $exportPath = '') {
    // Validate inputs
    if ($wpm < 0.5 || $wpm > 10) throw new Exception('Invalid words per second value (0.5-10)');
    if ($minTime < 0.5 || $minTime > 10) throw new Exception('Invalid minimum duration (0.5-10s)');
    if ($punctuationPad < 0 || $punctuationPad > 2) throw new Exception('Invalid punctuation padding (0-2s)');
    if ($maxLength < 100 || $maxLength > 1000) throw new Exception('Invalid max block length (100-1000)');
    if ($startOffset < 0 || $startOffset > 3600) throw new Exception('Invalid start offset (0-3600s)');
    if ($subtitleGap < 0 || $subtitleGap > 1000) throw new Exception('Invalid subtitle gap (0-1000ms)');

    $script = stripMarkdown($script);
    $chunks = splitIntoChunks($script, $maxLength);
    if (empty($chunks)) throw new Exception('No valid chunks found in script');

    $srt = "";
    $index = 1;
    $currentTime = $startOffset; // Apply start offset
    $totalWords = 0;
    $gapSeconds = $subtitleGap / 1000; // Convert ms to seconds

    foreach ($chunks as $line) {
        $wordCount = str_word_count($line);
        $totalWords += $wordCount;
        $duration = max($minTime, $wordCount / $wpm);
        
        // Add punctuation pause
        if (preg_match('/[.!?]$/', $line)) {
            $duration += $punctuationPad;
        }

        // Align to frame boundary if FPS is set
        if ($fps > 0) {
            $currentTime = alignToFrame($currentTime, $fps);
            $duration = alignToFrame($duration, $fps);
        }

        $start = formatTime($currentTime);
        $end = formatTime($currentTime + $duration);

        $srt .= "$index\n$start --> $end\n$line\n\n";
        
        // Move to next subtitle with gap
        $currentTime += $duration + $gapSeconds;
        $index++;
    }
    
    $subtitleCount = $index - 1;
    $totalDuration = $currentTime - $startOffset - $gapSeconds; // Exclude final gap

    // Generate safe filename
    $safeName = '';
    if ($name !== '') {
        $safeName = preg_replace('/[^a-z0-9_-]/i', '_', $name);
        $safeName = preg_replace('/_+/', '_', $safeName); // Remove duplicate underscores
        $safeName = trim($safeName, '_');
    }
    if ($safeName === '') {
        $safeName = 'script_' . date('Y-m-d_H-i-s');
    }
    $filename = $safeName . '.srt';

    // Determine export directory
    $dir = __DIR__ . '/srt_files'; // Default
    $usedPath = 'srt_files/';
    
    if (!empty($exportPath)) {
        // Sanitize and validate custom path
        $customPath = realpath($exportPath);
        if ($customPath && is_dir($customPath) && is_writable($customPath)) {
            $dir = $customPath;
            $usedPath = $exportPath;
        }
    }

    // Create directory if it doesn't exist
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new Exception('Could not create output directory');
    }

    $path = "$dir/$filename";
    if (file_put_contents($path, $srt) === false) {
        throw new Exception('Could not save SRT file');
    }

    return [
        'srt' => $srt,
        'filename' => $filename,
        'subtitle_count' => $subtitleCount,
        'total_duration' => round($totalDuration, 2),
        'word_count' => $totalWords,
        'export_path' => $usedPath
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
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = floor($seconds % 60);
    $ms = round(($seconds - floor($seconds)) * 1000);
    return sprintf("%02d:%02d:%02d,%03d", $h, $m, $s, $ms);
}

/**
 * Handle file downloads
 */
function handleDownload($filename) {
    $dir = __DIR__ . '/srt_files';
    $safeName = basename($filename); // Prevent directory traversal
    $path = "$dir/$safeName";

    if (!file_exists($path)) {
        throw new Exception('File not found');
    }

    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $safeName . '"');
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: no-cache');
    readfile($path);
    exit;
}
