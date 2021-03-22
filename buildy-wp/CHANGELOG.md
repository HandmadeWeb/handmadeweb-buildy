# Changelog

All notable changes to `buildy-wp` will be documented in this file

## 2.6.70

- Replace stickybody with stickyheader (it's all it was used for anyway)
- Add lazy load to youtube iframes
- Add sitewide message bar in site options
- Various cleanups

## 2.6.50 - ## 2.6.68

- Fix code module not stringifying input
- Added gallery module to GUI
- Added blade for gallery module front-end
- Added styles for gallery module
- Added logic to convert gallery to a slider
- Fix gallery title, now using title component
- Fix bug where tiny MCE wouldn't save TEXT tab changes unless switching back to visual first
- Add Masonry to grid layout
- Fix duplicate custom fields
- Fix galleries / lightbox intiiating even if no galleries are on the page
- Add button style dropdown that match theme styles.
- Remove btn class on buildy buttons (gui) to avoid conflicts in styles
- Fix up CSS grid system defaults in buildy.
- Adjust GUI button preview
- Fix issue where image ID is not found on opening media library
- Add background image blend mode to background settings

## 2.6.40 - ## 2.6.49

- Small fix with blade files, body content needing to be null checked
- Ability to disable frontend output for sections/rows/modules
- Fix internalLinkEnabled variable
- Fix page content links not automatically adding ID target
- Add module_style output to global module wrapper (greyed-out bar)
- Add module_style output to columns
- Fix HR blade module --- Totally dickered
- Add description wrapper to custom and text modules.
- Make XS the default breakpoint for the breakpoint switcher in GUI.
- Fix customClasses being overwritten in common.blade.

## 2.6.30 - ## 2.6.39

- Add col-gap option to bmcb-settings
- Fix imageID not being removed from JSON (only image URL was)
- Went through everything and made sure there were no exceptions being thrown in WP_DEBUG anymore
- Few more property checks/fixes
- Add templating functionality
- Add repeater to accordion
- Fix a few more properties
- Fix image style attributes not closing off
- Fix small typo in path for slide fields
- Fix small issue when adding accordion items but leave everything blank -- Bricking the accordion.
- Convert module styles into the template system

## 2.6.29

- Add col-gap root var by default bundled with buildy

## 2.6.28

- Remove camel case for data attributes (in js)
- Remove attributes from adding if they're '' and showing "false" if they're false

## 2.6.27

- Fix missing endif
- Fix autoplay setting in wrong spot

## 2.6.26

- Clean up slider options with toggles where appropriate

## 2.6.12 - ## 2.6.25

- Add slider module
- Improve UX of accordions (sliders, accordions, tabs)
- Add JS for slider
- Add basic styles for slider
- Add blade output for slider

## 2.6.11

- Add inline style attributes to common.blade so modules can use bg-image and options

## 2.6.10

- Swap col-gap to css variables -- We now have fine-grain control over column-gaps. Both globally and individually.

## 2.6.8 - 2.6.9

- Add "Button Class" to blurb buttons.

## 2.6.7

- Move conversion of module styles value to blade instead of JS (lowercase and hyphen)

## 2.6.4 - 2.6.6

- Fixing space select not allowing the value of 0
- Removing previous toubleshooting markers

## 2.6.3

- Adding troubleshooting markers for space select boxes to nail down an issue

## 2.6.2

- Small refactor to space select boxes

## 1.0.0 - 201X-XX-XX

- initial release
