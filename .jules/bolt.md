## 2024-05-19 - Fast Word Counting
**Learning:** `text.match(/\S+/g)` is significantly faster than `text.split(/\s+/).filter(w => w.length > 0)` and allocates much less memory.
**Action:** Use regex matching for counting words in long strings to improve performance.
## 2025-04-18 - Debouncing large script processing in UI
**Learning:** Attaching expensive operations (like regex matching for word counts) to synchronous `input` events on a textarea meant for large texts (like scripts) blocks the main thread during rapid typing, leading to input lag.
**Action:** Use a debounce utility function to wrap event handlers that process large inputs. This delays execution until the user stops typing, keeping the UI responsive.

## 2025-03-20 - File System as a Database Optimization
**Learning:** This codebase uses the file system (`srt_files/` directory) as a database to list recently generated files. The previous implementation loaded the entire directory, performed expensive disk I/O (`filesize()`) on every single file, serialized the whole array to JSON, and let the frontend discard all but the top 10. This is a severe O(N) bottleneck as the directory grows.
**Action:** When a file system is used to list recent files, always use a two-pass approach: first read minimal metadata (`filemtime()`) to sort, slice to the exact page size needed, and *then* perform expensive disk I/O (`filesize()`, etc.) only on the paginated slice before sending to the client.

## 2026-03-21 - File Extension Checking Performance
**Learning:** `str_ends_with($file, '.ext')` is significantly faster (about 5x in simple benchmarks) than `pathinfo($file, PATHINFO_EXTENSION) === 'ext'` in PHP userland when iterating through directories with many files. `pathinfo` has more overhead because it returns an array of path information or extracts a specific part, whereas `str_ends_with` is a highly optimized simple string check.
**Action:** Use `str_ends_with()` or `strpos()` for simple extension checking instead of `pathinfo()` inside loops or hot paths, particularly for tasks involving bulk file processing like `scandir()`.
