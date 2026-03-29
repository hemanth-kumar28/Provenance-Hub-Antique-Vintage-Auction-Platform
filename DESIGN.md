# Design System: Modern Heritage Editorial

## 1. Overview & Creative North Star
The Creative North Star for this design system is **"The Digital Curator."** 

We are not building a marketplace; we are designing a high-end, digital gallery experience. The aesthetic must bridge the gap between historical weight and modern clarity. By utilizing the "Modern Heritage" aesthetic, we avoid the clinical coldness of typical e-commerce. Instead, we embrace intentional asymmetry, overlapping elements (e.g., a product image subtly breaking the container of a "Warm Parchment" card), and high-contrast typography scales that mirror a bespoke auction catalog. 

Every pixel must feel "archival." The goal is to make the user feel as though they are browsing a private collection in a sun-drenched library, rather than scrolling through an app.

---

## 2. Technical Implementation via Tailwind CSS
To ensure absolute compliance with the "Digital Curator" vision, developers MUST utilize the following customized `tailwind.config.js` framework. Standard Tailwind utilities (colors, fonts, shadows, borders) are heavily restricted or overridden.

### Universal Configuration Map
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                "background": "#fdf9f0",
                "on-background": "#3D2B1F",
                "surface": "#fdf9f0",
                "surface-container-lowest": "#ffffff",
                "surface-container-low": "#f7f3ea",
                "surface-container": "#f1eee5",
                "surface-container-highest": "#e6e2d9",
                "on-surface": "#3D2B1F",
                "on-surface-variant": "#4d4639",
                "primary": "#745b1b",
                "primary-container": "#c9a961",
                "secondary": "#8B6F47",
                "outline": "#7f7667",
                "outline-variant": "#d0c5b4"
            },
            fontFamily: {
                "display": ["Playfair Display", "serif"],
                "headline": ["Cormorant Garamond", "serif"],
                "body": ["Lora", "serif"],
                "ui": ["Montserrat", "sans-serif"],
                "price": ["IBM Plex Mono", "monospace"]
            },
            boxShadow: {
                "ambient": "0 24px 40px -15px rgba(77, 70, 57, 0.05)"
            },
            borderRadius: { "DEFAULT": "0.125rem", "sm": "0.125rem", "md": "0.25rem", "lg": "0.5rem", "xl": "0.5rem", "full": "9999px" }
        }
    }
}
```

---

## 3. Strict Rules of Engagement

### The "No-Line" Rule
Prohibit the use of 1px solid black or high-contrast borders for sectioning. Structural boundaries must be defined through background color shifts. For example, a detailed item description section should be rendered in `bg-surface-container-low` to distinguish it from the `bg-surface` background without the need for a "divider line." 

If a ghost border is absolutely required, use `border-outline-variant/10`.

### Typography Application
- **`<h1 class="font-display">`:** For highest impact headers.
- **`<h2 class="font-headline">`:** For item titles and sub-headers.
- **`<p class="font-body">`:** For all long-form text, descriptions, and provenance histories.
- **`<button class="font-ui uppercase">`:** For navigation, buttons, and system labels.
- **`<span class="font-price">`:** Exclusively for financial data, timers, and lot numbers.

### Elevation & Shadows
Standard shadows (`shadow-md`, `shadow-xl`) are explicitly forbidden as they look artificial. Depth is achieved via:
1. **Tonal Layering:** Stacking `surface` over `surface-container`.
2. **Ambient Light:** Use the custom `shadow-ambient` class where necessary to create a "floating in gallery light" effect.

### The Paper Grain Texture
A subtle 2-3% opacity paper texture must overlay the main `body` background. This is achieved via a dedicated fixed div:
```html
<div class="fixed inset-0 z-50 pointer-events-none opacity-3 mix-blend-multiply bg-[url('/path/to/noise.png')]"></div>
```

---

## 4. Components

### Buttons
- **Primary:** `bg-primary` or gold gradient, `text-white`, `font-ui uppercase font-bold`, `rounded-lg` (0.5rem max).
- **Secondary:** Transparent background with `border border-outline-variant/20`, text `text-primary`.

### Input Fields ("The Ledger")
Forms must NOT use full bordered boxes. They should utilize pure underlines:
`class="bg-transparent border-0 border-b border-outline-variant focus:border-primary focus:ring-0"`

### Avatars & Chips
- Circular profiles (`rounded-full`) are permitted ONLY for user profile photos.
- Item thumbnails must never exceed `rounded-lg` (0.5rem) to preserve the sharp, framed look of archival photography. 

### Do's and Don'ts
- **Do:** Use pure `#3D2B1F` instead of `#000000` for all text.
- **Don't:** Round structural containers above `0.5rem`.
- **Don't:** Rely on standard Tailwind gray scales. Always map to the warm `surface` palette.