# highlight-within-textarea

jQuery plugin for highlighting bits of text within a textarea.

## Introduction

It's not actually possible to style text in a textarea, because any markup within a textarea is treated as literal text. This plugin aims to fake it, allowing you to highlight pieces of text inside a textarea.

A native textarea element is used and familiar behavior (auto-correct, scrolling, resizing, etc.) works as expected.

## Usage

To start, call `highlightWithinTextarea()` on a textarea, passing in a config object.

```javascript
$('.my-textarea').highlightWithinTextarea({
    highlight: whatever // string, regexp, array, function, or custom object
});
```

The `highlight` property accepts several different types of values to describe what will be highlighted. You can see the various ways to highlight things, along with example code, on **[the demo page](https://lonekorean.github.io/highlight-within-textarea/)**.

## Styling

For reference, **[the demo page](https://lonekorean.github.io/highlight-within-textarea/)** has some sample styling (view source to see the CSS).

There are some guidelines for getting your styles in the right places. Here are the classes you'll want to use.

### .hwt-container

Use for visibility, positioning, and background.
- `display`
- `position`
- `top`
- `left`
- `margin`
- `background`

### .hwt-content

Use for sizing and text formatting.
- `width`
- `height`
- `padding`
- `border`
- `color`
- `font`

### .hwt-content mark

Use for highlighted text. Generally, stuff that doesn't change size is fine.
- `background-color`
- `border-radius`
- `box-shadow`

Changes to `color` won't be visible, since text in the textarea covers colored text in the highlights.

## Updating

Highlighting will automatically be updated as the user edits the contents of the textarea. But sometimes other scripts may directly change the contents of the textarea. In these cases, you can manually trigger a highlighting update.

```javascript
$('.my-textarea').highlightWithinTextarea('update');
```

## Destroying

You can remove the plugin from a textarea with this.

```javascript
$('.my-textarea').highlightWithinTextarea('destroy');
```
