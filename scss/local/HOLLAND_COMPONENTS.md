# Holland Modernist Design System

A comprehensive SCSS design system inspired by 20th and 21st-century Dutch modernism, featuring De Stijl, Bauhaus, Memphis design, and contemporary geometric aesthetics.

## Design Philosophy

This design system draws inspiration from:
- **De Stijl Movement** (1917-1931): Mondrian's primary colors and geometric abstraction
- **Gerrit Rietveld**: Bold color blocking and architectural forms (Red-Blue Chair)
- **Bauhaus**: Functional geometry and industrial elegance
- **Memphis Design**: Postmodern playfulness and bold patterns
- **Contemporary Dutch Design**: Modern interpretations with vibrant colors

## Typography

### Recommended Fonts
- **Primary (Headings)**: Space Grotesk - Geometric, modern, highly legible
- **Secondary (Body)**: Work Sans - Clean, functional, versatile

Both fonts are automatically imported via Google Fonts.

### Font Classes
```html
<h1 class="ho-heading-display">Display Heading</h1>
<p class="ho-text-geometric">Geometric Text</p>
```

## Color Palette

### De Stijl Colors (Mondrian-inspired)
- `--ho-stijl-red`: #E63946 (Bold red)
- `--ho-stijl-blue`: #1D3557 (Deep blue)
- `--ho-stijl-yellow`: #F1C40F (Vibrant yellow)
- `--ho-stijl-black`: #14213D (Rich black)
- `--ho-stijl-white`: #F8F9FA (Off-white)

### Rietveld Colors
- `--ho-rietveld-red`: #DD4132
- `--ho-rietveld-blue`: #0077B6
- `--ho-rietveld-yellow`: #FCA311
- `--ho-rietveld-black`: #2B2D42

### Memphis Colors
- `--ho-memphis-pink`: #FF006E
- `--ho-memphis-cyan`: #00B4D8
- `--ho-memphis-lime`: #CAFFBF
- `--ho-memphis-violet`: #7209B7
- `--ho-memphis-coral`: #FF7F51

### Contemporary Dutch
- `--ho-orange`: #FF6B35 (Dutch orange)
- `--ho-canal-blue`: #006BA6 (Amsterdam canals)
- `--ho-tulip-pink`: #FF006E (Tulip fields)
- `--ho-windmill-gray`: #8D99AE (Dutch skies)

## Components

### Buttons

```html
<!-- De Stijl Button -->
<button class="btn-stijl">Primary Action</button>

<!-- Rietveld Button (with border) -->
<button class="btn-rietveld">Secondary Action</button>

<!-- Bauhaus Button -->
<button class="btn-bauhaus">Tertiary Action</button>

<!-- Memphis Button (gradient with shine effect) -->
<button class="btn-memphis">Special Action</button>

<!-- Dutch Orange Button -->
<button class="btn-dutch">Dutch Action</button>
```

### Cards

```html
<!-- Standard Holland Card -->
<div class="card-holland">
    <div class="card-header">Card Title</div>
    <div class="card-body">Card content goes here</div>
</div>

<!-- De Stijl Card (with colored borders) -->
<div class="card-stijl">
    <div class="card-body">Content</div>
</div>

<!-- Rietveld Card (with rainbow top bar) -->
<div class="card-rietveld">
    <div class="card-body">Content</div>
</div>

<!-- Memphis Card (layered shadows) -->
<div class="card-memphis">
    <div class="card-body">Content</div>
</div>
```

### Badges

```html
<span class="badge-stijl">New</span>
<span class="badge-bauhaus">Featured</span>
<span class="badge-memphis">Hot</span>
```

### Alerts

```html
<div class="alert-holland alert-success">Success message</div>
<div class="alert-holland alert-warning">Warning message</div>
<div class="alert-holland alert-danger">Danger message</div>
<div class="alert-holland alert-info">Info message</div>
```

### Forms

```html
<label class="form-label-holland">Input Label</label>
<input type="text" class="form-control-holland" placeholder="Enter text">
```

### Tables

```html
<table class="table-holland">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data 1</td>
            <td>Data 2</td>
        </tr>
    </tbody>
</table>
```

## Background Utilities

### Solid Backgrounds
```html
<div class="bg-holland-stijl-red">Red background</div>
<div class="bg-holland-stijl-blue">Blue background</div>
<div class="bg-holland-stijl-yellow">Yellow background</div>
<div class="bg-holland-orange">Dutch orange background</div>
<div class="bg-holland-canal">Canal blue background</div>
<div class="bg-holland-tulip">Tulip pink background</div>
```

### Pattern Backgrounds
```html
<!-- Rietveld striped gradient -->
<div class="bg-holland-rietveld">Rietveld pattern</div>

<!-- Mondrian grid with colored blocks -->
<div class="bg-holland-mondrian">Mondrian pattern</div>

<!-- Bauhaus radial gradient -->
<div class="bg-holland-bauhaus">Bauhaus pattern</div>

<!-- Memphis geometric patterns -->
<div class="bg-holland-memphis">Memphis pattern</div>
```

## Text Utilities

```html
<p class="text-holland-red">Red text</p>
<p class="text-holland-blue">Blue text</p>
<p class="text-holland-yellow">Yellow text</p>
<p class="text-holland-orange">Orange text</p>
```

## Border Utilities

```html
<div class="border-holland">Thick black border</div>
<div class="border-holland border-holland-red">Red border</div>
<div class="border-holland border-holland-blue">Blue border</div>
<div class="border-geometric">No border radius (pure geometric)</div>
```

## Shadow Utilities

```html
<div class="shadow-holland">Geometric shadow</div>
<div class="shadow-holland-hover">Shadow on hover</div>
```

## Geometric Shapes

```html
<div class="shape-square bg-holland-stijl-red">Square</div>
<div class="shape-rectangle bg-holland-stijl-blue">Rectangle</div>
<div class="shape-circle bg-holland-stijl-yellow">Circle</div>
```

## Grid System

```html
<!-- 2-column grid -->
<div class="grid-holland grid-2">
    <div>Item 1</div>
    <div>Item 2</div>
</div>

<!-- 3-column grid -->
<div class="grid-holland grid-3">
    <div>Item 1</div>
    <div>Item 2</div>
    <div>Item 3</div>
</div>

<!-- Mondrian-style grid (3x3 with varying sizes) -->
<div class="grid-holland grid-mondrian">
    <div class="bg-holland-stijl-red">1</div>
    <div class="bg-holland-stijl-blue">2</div>
    <div class="bg-holland-stijl-yellow">3</div>
    <!-- ... more items -->
</div>
```

## Animations

```html
<div class="animate-slide-in">Slides in from left</div>
<div class="animate-rotate">Rotates continuously</div>
<div class="animate-pulse-holland">Pulses gently</div>
```

## Usage

### Wrapping Content

All Holland components should be wrapped in the `.holland` class:

```html
<body class="holland">
    <!-- All Moodle content -->
</body>
```

Or for specific sections:

```html
<div class="holland">
    <button class="btn-stijl">Click me</button>
    <div class="card-rietveld">
        <div class="card-body">Content</div>
    </div>
</div>
```

### Page Wrapper Override

The design system automatically overrides `#page-wrapper` with:
- Geometric color-blocked header (De Stijl colors)
- Rotating geometric accent shape
- Grid-patterned background

### Responsive Design

All components are responsive and adapt to different screen sizes:
- Mobile: Single column layouts
- Tablet: 2-column layouts where appropriate
- Desktop: Full multi-column layouts with geometric accents

## Design Tokens

### Spacing
- `--ho-space-xs`: 4px
- `--ho-space-sm`: 8px
- `--ho-space-md`: 16px
- `--ho-space-lg`: 24px
- `--ho-space-xl`: 32px
- `--ho-space-2xl`: 48px
- `--ho-space-3xl`: 64px

### Border Radius
- `--ho-radius-none`: 0
- `--ho-radius-sm`: 2px
- `--ho-radius-md`: 4px
- `--ho-radius-lg`: 8px
- `--ho-radius-geometric`: 0 (pure geometric shapes)

### Shadows
- `--ho-shadow-sm`: Subtle shadow
- `--ho-shadow-md`: Medium shadow
- `--ho-shadow-lg`: Large shadow
- `--ho-shadow-geometric`: Offset geometric shadow (4px 4px)

## Examples in Moodle Context

### Course Cards
```html
<div class="holland">
    <div class="card-rietveld">
        <div class="card-header">Course Design 101</div>
        <div class="card-body">
            <p>Learn the fundamentals of modernist design.</p>
            <button class="btn-stijl">Enroll Now</button>
        </div>
    </div>
</div>
```

### Activity Completion
```html
<div class="holland">
    <span class="badge-stijl">Complete</span>
    <span class="badge-bauhaus">In Progress</span>
    <span class="badge-memphis">Not Started</span>
</div>
```

### Dashboard Grid
```html
<div class="holland">
    <div class="grid-holland grid-3">
        <div class="card-holland">Dashboard Item 1</div>
        <div class="card-holland">Dashboard Item 2</div>
        <div class="card-holland">Dashboard Item 3</div>
    </div>
</div>
```

## Browser Support

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support (iOS 12+)
- CSS Grid: Required
- CSS Custom Properties: Required

## Performance Notes

- Fonts are loaded via Google Fonts CDN
- CSS animations use GPU-accelerated transforms
- Grid layouts optimize for modern browsers
- All utilities use efficient CSS custom properties

## Credits

Inspired by:
- Piet Mondrian (1872-1944)
- Gerrit Rietveld (1888-1964)
- Bauhaus Movement (1919-1933)
- Memphis Group (1981-1988)
- Contemporary Dutch Design

## License

GPL-3.0 (matching Moodle license)
