function show_password(idIcon, idInput) {
    
    const icon = document.getElementById(`${idIcon}`);
    const input = document.getElementById(`${idInput}`);

    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
    input.type == 'text' ? input.type = 'password' : input.type = 'text';
}
