# 🎨 Quick Icon Reference Card

A quick cheat sheet for commonly used icons in Gombe SS Hub.

---

## 🚀 Most Used Icons

```html
<!-- Navigation -->
<i class="fas fa-chart-line"></i>        <!-- Dashboard -->
<i class="fas fa-user-graduate"></i>     <!-- Students -->
<i class="fas fa-chalkboard-teacher"></i><!-- Staff -->
<i class="fas fa-cogs"></i>              <!-- Settings -->
<i class="fas fa-chart-bar"></i>         <!-- Reports -->

<!-- Actions -->
<i class="fas fa-plus-circle"></i>       <!-- Add/Create -->
<i class="fas fa-edit"></i>              <!-- Edit -->
<i class="fas fa-trash"></i>             <!-- Delete -->
<i class="fas fa-eye"></i>               <!-- View -->
<i class="fas fa-save"></i>              <!-- Save -->

<!-- Status -->
<i class="fas fa-check-circle"></i>      <!-- Success -->
<i class="fas fa-times-circle"></i>      <!-- Error -->
<i class="fas fa-exclamation-triangle"></i><!-- Warning -->
<i class="fas fa-info-circle"></i>       <!-- Info -->

<!-- User Management -->
<i class="fas fa-user"></i>              <!-- User -->
<i class="fas fa-users"></i>             <!-- Multiple Users -->
<i class="fas fa-user-plus"></i>         <!-- Add User -->
<i class="fas fa-user-shield"></i>       <!-- Admin -->
<i class="fas fa-crown"></i>             <!-- Super Admin -->

<!-- Common UI -->
<i class="fas fa-search"></i>            <!-- Search -->
<i class="fas fa-filter"></i>            <!-- Filter -->
<i class="fas fa-chevron-right"></i>     <!-- Dropdown -->
<i class="fas fa-arrow-right"></i>       <!-- Next/Forward -->
<i class="fas fa-sign-out-alt"></i>      <!-- Logout -->
```

---

## 🎨 Color Classes

```html
<!-- Text Colors -->
text-blue-500      <!-- Primary/Info -->
text-green-500     <!-- Success -->
text-yellow-500    <!-- Warning -->
text-red-500       <!-- Danger/Error -->
text-gray-500      <!-- Neutral -->
text-purple-500    <!-- Admin -->
text-indigo-500    <!-- Accent -->

<!-- Background Colors -->
bg-blue-500        <!-- Primary -->
bg-green-500       <!-- Success -->
bg-yellow-500      <!-- Warning -->
bg-red-500         <!-- Danger -->
bg-gray-500        <!-- Neutral -->
```

---

## 📏 Size Classes

```html
<!-- Font Awesome Sizes -->
text-xs            <!-- Extra Small -->
text-sm            <!-- Small -->
text-base          <!-- Base (default) -->
text-lg            <!-- Large -->
text-xl            <!-- Extra Large -->
text-2xl           <!-- 2X Large -->
text-3xl           <!-- 3X Large -->
text-4xl           <!-- 4X Large -->
text-5xl           <!-- 5X Large -->

<!-- Tailwind Width/Height -->
w-3 h-3            <!-- Small (sidebar sub-items) -->
w-4 h-4            <!-- Medium-Small -->
w-5 h-5            <!-- Medium (sidebar main items) -->
w-6 h-6            <!-- Medium-Large -->
w-8 h-8            <!-- Large -->
```

---

## 🔄 Common Patterns

### Icon with Text (Left)
```html
<i class="fas fa-icon mr-2"></i>Text
```

### Icon with Text (Right)
```html
Text<i class="fas fa-icon ml-2"></i>
```

### Icon Button
```html
<button class="...">
    <i class="fas fa-icon mr-2"></i>
    Button Text
</button>
```

### Icon Only Button
```html
<button class="...">
    <i class="fas fa-icon"></i>
</button>
```

### Colored Icon
```html
<i class="fas fa-icon text-blue-500"></i>
```

### Large Icon with Background
```html
<div class="bg-blue-100 rounded-full p-4">
    <i class="fas fa-icon text-4xl text-blue-600"></i>
</div>
```

---

## 🎯 Feature-Specific Icons

### Students
```html
<i class="fas fa-user-graduate"></i>     <!-- Student -->
<i class="fas fa-book-reader"></i>       <!-- O'Level -->
<i class="fas fa-graduation-cap"></i>    <!-- A'Level -->
```

### Staff
```html
<i class="fas fa-chalkboard-teacher"></i><!-- Teacher -->
<i class="fas fa-user-tie"></i>          <!-- Private Staff -->
<i class="fas fa-landmark"></i>          <!-- Government Staff -->
```

### Settings
```html
<i class="fas fa-users"></i>             <!-- User Management -->
<i class="fas fa-palette"></i>           <!-- Appearance -->
<i class="fas fa-sliders-h"></i>         <!-- General -->
<i class="fas fa-shield-alt"></i>        <!-- Security -->
```

### Roles
```html
<i class="fas fa-crown text-yellow-500"></i>    <!-- Super Admin -->
<i class="fas fa-user-tie text-purple-500"></i> <!-- Admin -->
<i class="fas fa-user text-gray-500"></i>       <!-- User -->
```

---

## 💡 Pro Tips

1. **Consistency**: Always use the same icon for the same action
2. **Spacing**: Use `mr-2` or `ml-2` for icon-text spacing
3. **Size**: Match icon size to text size
4. **Color**: Use semantic colors (green=success, red=danger)
5. **Accessibility**: Include text labels or aria-labels

---

## 🔗 Quick Links

- **Font Awesome Search**: https://fontawesome.com/search
- **Tailwind Colors**: https://tailwindcss.com/docs/customizing-colors
- **Full Icon Guide**: See `ICON_GUIDE.md`

---

**Print this page for quick reference while coding!** 📄