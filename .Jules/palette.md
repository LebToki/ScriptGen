## 2024-03-18 - Missing label fors
**Learning:** Adding htmlFor to labels matching the input id ensures correct linking for screen readers.
**Action:** Add `for` attributes to all `label` elements that are missing them, tying them to their respective input fields.

## 2025-04-18 - Interactive Divs & Fitts's Law
**Learning:** When using `div` elements as custom interactive components (like CapCut template cards), they must have `role="button"`, `tabindex="0"`, and `keydown` event listeners for `Enter` and `Space` to be fully accessible. Furthermore, small click targets for toggles should be expanded using a `<label for="...">` wrapper around adjacent descriptive text.
**Action:** Always verify keyboard navigability of custom components and convert adjacent descriptive text into `<label>` elements for checkboxes/toggles to increase the clickable area.
