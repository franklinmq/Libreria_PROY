document.addEventListener('DOMContentLoaded', function() {
    const showPasswordBtn = document.getElementById('showPassword');
    const passwordInput = document.getElementById('password');

    if (showPasswordBtn && passwordInput) {
        showPasswordBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                this.textContent = 'OCULTAR';
            } else {
                this.textContent = 'MOSTRAR';
            }
        });
    }

    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', function() {
            const btnText = loginBtn.querySelector('.btn-text');
            if (btnText) {
                btnText.textContent = 'Iniciando...';
            }
            loginBtn.style.opacity = '0.8';
            loginBtn.style.pointerEvents = 'none';
        });
    }
});
