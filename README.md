# Gremaza WPB Addons

**Author:** Marsel Preci  
**Version:** 1.0.0  
**Requires:** WordPress 5.0+, WPBakery Page Builder  

## Description

Gremaza WPB Addons is a powerful extension for WPBakery Page Builder that adds custom elements to enhance your website building experience. The plugin creates a new category called "By Gremaza" with professional, responsive elements.

## Features

### Hero Banner Element
- **Two Layout Styles:**
  - Style 1: Text content on the left, image on the right
  - Style 2: Image on the left, text content on the right
- **Mobile Responsive:** Both styles automatically switch to image-top, content-bottom layout on mobile devices
- **Customizable Content:**
  - Title with selectable HTML tags (H1-H6)
  - Rich text description
  - Call-to-action button with link
  - Featured image with multiple size options
- **Design Options:**
  - Custom colors for title, description, and button
  - Background color and text color for buttons
  - Extra CSS classes for advanced styling

## Installation

1. Upload the `gremaza-wpb-addons` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure WPBakery Page Builder is installed and activated
4. Start using the new elements in the "By Gremaza" category

## Requirements

- WordPress 5.0 or higher
- WPBakery Page Builder plugin (active)
- PHP 7.0 or higher

## Usage

### Hero Banner

1. In WPBakery Page Builder, click "Add Element"
2. Look for the "By Gremaza" category
3. Select "Hero Banner"
4. Configure your content:
   - Choose layout style (Style 1 or Style 2)
   - Add your title, description, and button text
   - Set button link and target
   - Upload your hero image
   - Customize colors in the Design tab

### Mobile Behavior

On mobile devices (768px and below), both layout styles will automatically display:
- Image at the top
- Title below the image
- Description below the title
- Button below the description
- All content centered

## Styling

The plugin includes comprehensive CSS that handles:
- Responsive design for all screen sizes
- Smooth animations and hover effects
- Professional styling out of the box
- Easy customization through CSS classes

## Customization

### Adding Custom CSS

You can add custom CSS to further customize the appearance:

```css
/* Custom hero banner styling */
.gremaza-hero-banner.my-custom-class {
    background: linear-gradient(45deg, #f0f0f0, #ffffff);
}

.gremaza-hero-banner.my-custom-class .gremaza-hero-title {
    font-family: 'Your Custom Font', sans-serif;
}
```

### Available CSS Classes

- `.gremaza-hero-banner` - Main container
- `.gremaza-hero-style1` - Style 1 layout
- `.gremaza-hero-style2` - Style 2 layout
- `.gremaza-hero-content` - Text content wrapper
- `.gremaza-hero-title` - Title element
- `.gremaza-hero-description` - Description wrapper
- `.gremaza-hero-button` - Call-to-action button
- `.gremaza-hero-image-wrapper` - Image container
- `.gremaza-hero-image` - Image element

## Upcoming Elements

More elements will be added to the "By Gremaza" category in future updates. Stay tuned for:
- Service boxes
- Team member cards
- Testimonial sliders
- Portfolio grids
- And more!

## Support

For support and feature requests, please contact Marsel Preci.

## Changelog

### 1.0.0
- Initial release
- Added Hero Banner element with two layout styles
- Responsive design implementation
- Custom styling options
- WPBakery Page Builder integration

## License

This plugin is licensed under the GPL v2 or later.

---

**Made with ❤️ by Marsel Preci**
