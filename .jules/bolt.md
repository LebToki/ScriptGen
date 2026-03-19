## 2024-05-19 - Fast Word Counting
**Learning:** `text.match(/\S+/g)` is significantly faster than `text.split(/\s+/).filter(w => w.length > 0)` and allocates much less memory.
**Action:** Use regex matching for counting words in long strings to improve performance.
## 2025-04-18 - Debouncing large script processing in UI
**Learning:** Attaching expensive operations (like regex matching for word counts) to synchronous `input` events on a textarea meant for large texts (like scripts) blocks the main thread during rapid typing, leading to input lag.
**Action:** Use a debounce utility function to wrap event handlers that process large inputs. This delays execution until the user stops typing, keeping the UI responsive.
