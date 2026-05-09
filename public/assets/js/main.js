document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    if (themeToggleBtn) {
        // İkonu ayarla
        const icon = themeToggleBtn.querySelector('i');
        if (document.body.classList.contains('light-mode')) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }

        themeToggleBtn.addEventListener('click', function() {
            document.body.classList.toggle('light-mode');
            
            // Tema tercihini cookie'ye kaydet
            let theme = 'dark';
            if (document.body.classList.contains('light-mode')) {
                theme = 'light';
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
            
            document.cookie = "theme=" + theme + "; path=/; max-age=" + (365*24*60*60);
            
            // Oturuma kaydetmek için arka planda ajax isteği (eğer varsa)
            try {
                const formData = new FormData();
                formData.append('theme', theme);
                fetch('/settings/changeTheme', { method: 'POST', body: formData });
            } catch (e) {}
        });
    }
});
