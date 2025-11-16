document.addEventListener('DOMContentLoaded', function() {
    const themeRadios = document.querySelectorAll('input[name="theme"]');
    
    function setTheme(themeName) {
        document.documentElement.classList.remove('theme-green', 'theme-cream');
        
        document.documentElement.classList.add(`theme-${themeName}`);
        
        localStorage.setItem('theme', themeName);
    }
    
    let currentTheme = 'green';
    const htmlClasses = document.documentElement.className;
    const themeMatch = htmlClasses.match(/theme-(\w+)/);
    
    if (themeMatch) {
        currentTheme = themeMatch[1];
    } else {
        currentTheme = localStorage.getItem('theme') || 'green';
        setTheme(currentTheme);
    }
    
    themeRadios.forEach(radio => {
        if (radio.value === currentTheme) {
            radio.checked = true;
        }
    });
    
    themeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            setTheme(this.value);
        });
    });
});
