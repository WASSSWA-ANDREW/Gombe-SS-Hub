# Mobile Development Guide v2.0

## Quick Start

### For New Pages
1. Always include the admin layout: `@extends('layouts.admin')`
2. Use Tailwind CSS responsive classes: `md:`, `lg:`
3. Test with mobile viewport (375px width)
4. Use semantic HTML

### Adding Responsive Features

#### 1. Responsive Text
```html
<!-- Smaller on mobile, larger on desktop -->
<h1 class="text-xl md:text-2xl lg:text-3xl">Title</h1>
<p class="text-sm md:text-base">Paragraph</p>
```

#### 2. Responsive Spacing
```html
<!-- Compact mobile, more space on desktop -->
<div class="p-2 md:p-4 lg:p-6 mb-3 md:mb-4 lg:mb-6">Content</div>
```

#### 3. Responsive Grid
```html
<!-- 1 column on mobile, 2 on tablet, 4 on desktop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
  <div>Item 1</div>
  <div>Item 2</div>
</div>
```

#### 4. Show/Hide Content
```html
<!-- Only show on mobile -->
<div class="show-mobile md:hidden">Mobile Menu</div>

<!-- Hide on mobile -->
<div class="hidden-xs d-block-md">Desktop Content</div>
```

### Touch-Friendly Elements

#### Buttons
```html
<!-- Always 44px+ height -->
<button class="touch-target py-3 px-4 rounded-lg">Button</button>
```

#### Icons
```html
<!-- Touch-friendly icon sizing -->
<i class="touch-icon fas fa-home"></i>
```

#### Form Inputs
```html
<!-- Mobile form input with proper sizing -->
<input type="text" class="mobile-input w-full py-3 px-4">

<!-- Mobile select -->
<select class="mobile-input w-full py-3 px-4">
  <option>Option</option>
</select>
```

## CSS Utilities

### Breakpoints
- **xs**: 0px (mobile)
- **sm**: 576px (small mobile)
- **md**: 768px (tablet)
- **lg**: 1024px (desktop)
- **xl**: 1280px (large desktop)

### Responsive Classes
```
.hidden-xs        // Hide on mobile
.show-mobile      // Show only on mobile
.d-none-md        // Hide on desktop
.text-xs-left     // Text align on mobile
.p-xs-4           // Padding on mobile
.grid-xs-1        // Grid columns on mobile
.flex-xs-col      // Flex direction on mobile
```

### Touch Targets
```
.touch-target     // 44x44px minimum
.touch-padding    // 12px padding
.touch-icon       // Icon sizing (44x44px)
.tap-highlight    // Touch visual feedback
```

## JavaScript APIs

### Mobile Navigation
```javascript
// Toggle mobile sidebar
Mobile.toggleSidebar();
Mobile.openSidebar();
Mobile.closeSidebar();
```

### Modals
```javascript
// Create and show modal
const modal = new Mobile.Modal(document.getElementById('myModal'));
modal.open();
modal.close();
modal.toggle();
```

### Alerts
```javascript
// Show toast notification
Mobile.Alert.show('Success!', 'success', 3000);  // Auto-close in 3s
Mobile.Alert.show('Error!', 'error');            // No auto-close
Mobile.Alert.show('Warning!', 'warning', 5000);
Mobile.Alert.show('Info', 'info', 3000);
```

### Dropdowns
```javascript
// Manual dropdown control
const dropdown = new Mobile.Dropdown(triggerElement);
dropdown.open();
dropdown.close();
dropdown.toggle();
```

### Tables
```javascript
// Manually refresh tables (after dynamic content)
Mobile.Table.refresh();

// Convert table to scrollable
Mobile.Table.makeScrollable(tableElement);

// Toggle column visibility
Mobile.Table.toggleColumn('table-selector', 2, true);  // Show column 2
Mobile.Table.toggleColumn('table-selector', 2, false); // Hide column 2
```

### Forms
```javascript
// Manually refresh forms (after dynamic content)
Mobile.Form.initialize();
Mobile.Form.enhanceInputs(formElement);
Mobile.Form.addValidation(formElement);
Mobile.Form.stackColumns();
```

## Common Patterns

### Mobile Menu
```html
<!-- Mobile menu button (auto-hidden on desktop) -->
<button id="mobileSidebarToggle" class="md:hidden fixed top-4 left-4 z-50">
  <i class="fas fa-bars"></i>
</button>

<!-- Sidebar (already includes mobile styles) -->
<aside id="adminSidebar" class="md:relative fixed ...">
  <!-- Navigation -->
</aside>
```

### Responsive Table
```html
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>John</td>
      <td>john@example.com</td>
      <td><a href="#">Edit</a></td>
    </tr>
  </tbody>
</table>
```
Tables are automatically mobilized - no additional code needed!

### Mobile Form
```html
<form>
  <div class="form-group mb-4">
    <label for="name" class="block font-semibold mb-2">Name</label>
    <input type="text" id="name" class="w-full py-3 px-4" required>
  </div>
  
  <div class="form-group mb-4">
    <label for="email" class="block font-semibold mb-2">Email</label>
    <input type="email" id="email" class="w-full py-3 px-4" required>
  </div>
  
  <button type="submit" class="w-full md:w-auto py-3 px-6">Submit</button>
</form>
```
Forms are automatically enhanced - just add `class="w-full py-3 px-4"` to inputs!

### Alert Example
```html
@if (session('success'))
  <div class="alert alert-success" role="alert">
    <strong>Success!</strong> {{ session('success') }}
  </div>
@endif
```

## Best Practices

### 1. Mobile First
- Design mobile experience first
- Then add desktop enhancements
- Use min-width media queries when possible

### 2. Touch Targets
- Never make buttons smaller than 44×44px
- Space buttons with at least 8px margin
- Use larger fonts (16px minimum on inputs)

### 3. Performance
- Use CSS when possible, avoid JS
- Lazy-load images and content
- Minimize layout shifts
- Compress images for mobile

### 4. Accessibility
- Use semantic HTML
- Include alt text for images
- Use ARIA attributes when needed
- Test with keyboard navigation
- Support screen readers

### 5. Testing
- Test on real devices, not just DevTools
- Check both portrait and landscape
- Test with slow networks
- Verify touch interactions
- Check dark mode

### 6. Forms
- Always set font-size to 16px (prevents zoom)
- Use proper input types (email, tel, date)
- Provide clear error messages
- Make labels clickable
- Use autocomplete attributes

### 7. Navigation
- Use mobile hamburger menu
- Keep menu items at 44px height
- Close menu when navigating
- Support swipe gestures

## Debugging

### Check Mobile Styles
```javascript
// Verify mobile optimization CSS loaded
console.log(document.styleSheets);

// Check if element has mobile class
console.log(element.classList);
```

### Test Breakpoints
```javascript
// Log current breakpoint
const width = window.innerWidth;
if (width < 576) console.log('XS - Mobile');
else if (width < 768) console.log('SM - Small');
else if (width < 1024) console.log('MD - Tablet');
else if (width < 1280) console.log('LG - Desktop');
else console.log('XL - Large Desktop');
```

### Monitor Touch Events
```javascript
document.addEventListener('touchstart', (e) => {
  console.log('Touch started at:', e.touches[0].clientX, e.touches[0].clientY);
});

document.addEventListener('touchend', (e) => {
  console.log('Touch ended');
});
```

## Common Issues & Solutions

### Issue: Modal not showing
**Solution**: Ensure `Mobile.initModals()` is called or element has proper data attributes.

### Issue: Form not validating
**Solution**: Check that inputs have `required` attribute and `Mobile.Form.initialize()` is called.

### Issue: Table not stacking
**Solution**: Verify table is in DOM when page loads or call `Mobile.Table.refresh()` after dynamic load.

### Issue: Sidebar not closing
**Solution**: Check that sidebar has `id="adminSidebar"` and toggle has `id="mobileSidebarToggle"`.

### Issue: Elements not responsive
**Solution**: Add responsive classes like `md:hidden`, `p-xs-2`, etc. Check CSS is loaded.

## File Organization

```
resources/
├── views/
│   ├── layouts/
│   │   └── admin.blade.php (includes all mobile CSS/JS)
│   └── [your pages]
public/
├── css/
│   ├── mobile-optimization.css (core mobile styles)
│   ├── mobile-modals-alerts.css (modal/alert styles)
│   └── mobile-utilities.css (responsive utilities)
└── js/
    ├── mobile-navigation.js (nav handling)
    ├── mobile-tables.js (table responsive)
    ├── mobile-forms.js (form enhancement)
    └── mobile-modals.js (modals/alerts)
```

## Performance Tips

1. **Minimize JavaScript**: Use CSS animations when possible
2. **Cache**: Browser caches CSS and JS files
3. **Compress Images**: Use WebP for modern browsers
4. **Lazy Load**: Load images only when needed
5. **Reduce Repaints**: Batch DOM changes
6. **Use CSS Variables**: For dynamic theming

## Resources

- `MOBILE_OPTIMIZATION_V2.0.md` - Full mobile optimization guide
- `V2.0_RELEASE_NOTES.md` - Release notes and features
- Inline comments in CSS/JS files for specific implementations

## Support

For issues or questions:
1. Check inline documentation in mobile CSS/JS files
2. Review examples in existing pages
3. Test on real devices
4. Check browser console for errors

---

**Version**: v2.0  
**Last Updated**: November 16, 2025  
**Author**: Mobile Optimization Team
