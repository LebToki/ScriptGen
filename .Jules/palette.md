## 2024-03-18 - Missing label fors
**Learning:** Adding htmlFor to labels matching the input id ensures correct linking for screen readers.
**Action:** Add `for` attributes to all `label` elements that are missing them, tying them to their respective input fields.

## 2025-04-18 - Interactive Divs & Fitts's Law
**Learning:** When using `div` elements as custom interactive components (like CapCut template cards), they must have `role="button"`, `tabindex="0"`, and `keydown` event listeners for `Enter` and `Space` to be fully accessible. Furthermore, small click targets for toggles should be expanded using a `<label for="...">` wrapper around adjacent descriptive text.
**Action:** Always verify keyboard navigability of custom components and convert adjacent descriptive text into `<label>` elements for checkboxes/toggles to increase the clickable area.

## 2025-04-18 - Visually Hidden Inputs & Focus Rings
**Learning:** Visually hiding inputs using `opacity: 0; width: 0; height: 0;` (e.g. for custom toggles like the CapCut switch) completely breaks native focus rings, making them invisible to keyboard users even if `*:focus-visible` is globally defined.
**Action:** When creating custom styled inputs that hide the native input, always explicitly define focus styles on the adjacent styled element (e.g., `input:focus-visible + .slider`) to restore keyboard accessibility.

## 2025-04-18 - Dynamic ARIA for Inline Validation
**Learning:** Inline form validation messages injected dynamically must be linked to their inputs using `aria-invalid="true"` and `aria-describedby` so screen readers announce the error when focus remains on or returns to the input.
**Action:** Whenever implementing `setFieldError` or similar DOM-manipulation validation, dynamically set `aria-invalid` and append the error div's ID to `aria-describedby`, ensuring existing attributes aren't overwritten.
