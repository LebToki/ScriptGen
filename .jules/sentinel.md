## 2025-05-24 - Path Traversal bypass via empty `strpos` needle
**Vulnerability:** A path traversal check logic used `strpos($path, $_SERVER['DOCUMENT_ROOT'] ?? '') !== 0` to ensure a given file path is outside the webroot. Because `$_SERVER['DOCUMENT_ROOT']` can be empty (for instance, in CLI mode or misconfigured servers), the needle evaluates to an empty string. `strpos($path, '')` always returns 0 in PHP 8, satisfying the condition and bypassing the check entirely.
**Learning:** Checking for prefixes using `strpos` with an empty needle string returns 0, falsely implying a match at the beginning of the string.
**Prevention:** Always ensure the prefix needle is non-empty before using it in security-critical `strpos` checks, e.g. `$prefix !== '' && strpos($path, $prefix) === 0`.
