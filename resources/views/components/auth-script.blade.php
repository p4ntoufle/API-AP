<script>
    // Ajouter le token auth à toutes les requêtes fetch
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const token = localStorage.getItem('auth_token');
        if (token && args[1]) {
            args[1].headers = args[1].headers || {};
            args[1].headers['Authorization'] = `Bearer ${token}`;
        }
        return originalFetch.apply(this, args);
    };

    // Exposer le user globalement
    window.authUser = () => JSON.parse(localStorage.getItem('user') || '{}');
    window.isAuthenticated = () => !!localStorage.getItem('auth_token');

    // Logout helper
    window.logout = () => {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        window.location.href = '{{ route("showLogin") }}';
    };
</script>
