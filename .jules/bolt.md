## 2024-05-19 - Fast Word Counting
**Learning:** `text.match(/\S+/g)` is significantly faster than `text.split(/\s+/).filter(w => w.length > 0)` and allocates much less memory.
**Action:** Use regex matching for counting words in long strings to improve performance.
