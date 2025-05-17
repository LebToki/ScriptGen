<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

function cleanOutput($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
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
            intval($data['max_length'] ?? 450)
        );

        echo cleanOutput([
            'success' => true,
            'preview' => $result['srt'],
            'filename' => $result['filename']
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

function processScript($script, $wpm, $minTime, $punctuationPad, $maxLength) {
    if ($wpm < 0.5 || $wpm > 10) throw new Exception('Invalid words per second value');
    if ($minTime < 0.5 || $minTime > 10) throw new Exception('Invalid minimum duration');
    if ($punctuationPad < 0 || $punctuationPad > 2) throw new Exception('Invalid punctuation padding');

    $chunks = splitIntoChunks($script, $maxLength);
    if (empty($chunks)) throw new Exception('No valid chunks found in script');

    $srt = "";
    $index = 1;
    $currentTime = 0.0;

    foreach ($chunks as $line) {
        $wordCount = str_word_count($line);
        $duration = max($minTime, $wordCount / $wpm);
        if (preg_match('/[.!?]$/', $line)) {
            $duration += $punctuationPad;
        }

        $start = formatTime($currentTime);
        $end = formatTime($currentTime + $duration);

        $srt .= "$index\n$start --> $end\n$line\n\n";
        $currentTime += $duration;
        $index++;
    }

    $filename = 'generated_' . time() . '_' . md5($srt) . '.srt';
    $dir = __DIR__ . '/srt_files';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new Exception('Could not create output directory');
    }

    $path = "$dir/$filename";
    if (file_put_contents($path, $srt) === false) {
        throw new Exception('Could not save SRT file');
    }

    return ['srt' => $srt, 'filename' => $filename];
}

function splitIntoChunks($text, $maxLength = 450) {
    $chunks = [];
    $buffer = '';
    $sentences = preg_split('/(?<=[.!?])\s+/', $text);

    foreach ($sentences as $sentence) {
        if (strlen($buffer . ' ' . $sentence) <= $maxLength) {
            $buffer .= ' ' . $sentence;
        } else {
            if (trim($buffer)) $chunks[] = trim($buffer);
            $buffer = $sentence;
        }
    }
    if (trim($buffer)) $chunks[] = trim($buffer);
    return $chunks;
}

function formatTime($seconds) {
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = floor($seconds % 60);
    $ms = ($seconds - floor($seconds)) * 1000;
    return sprintf("%02d:%02d:%02d,%03d", $h, $m, $s, $ms);
}

function handleDownload($filename) {
    $dir = __DIR__ . '/srt_files';
    $path = "$dir/" . basename($filename);

    if (!file_exists($path)) {
        throw new Exception('File not found');
    }

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}
