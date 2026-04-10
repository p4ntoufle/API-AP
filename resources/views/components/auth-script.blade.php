<script>
    // Les cookies de session sont automatiquement envoyés avec les requêtes
    // Pas besoin de Bearer tokens
    
    // Exposer le user globalement
    window.authUser = () => @json(Auth::user());
    window.isAuthenticated = () => @json(Auth::check());

    // Logout helper
    window.logout = () => {
        document.querySelector('form[action="{{ route("logout") }}"]')?.submit();
    };
</script>
