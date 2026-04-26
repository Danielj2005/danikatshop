const Validator = {
    // Regex para Emails (Estándar RFC 5322 simplificado)
    email: (val) => {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(val);
    },

    // Regex para Password: Mínimo 8 caracteres, al menos una letra y un número
    password: (val) => {
        const re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        return re.test(val);
    },

    // Validación de Productos: Nombre (min 3), Precio (numérico/null), Categoría (requerida)
    product: (data) => {
        const errors = [];
        
        if (!data.name || data.name.trim().length < 3) 
            errors.push("El nombre debe tener al menos 3 caracteres.");
        
        if (data.price !== null && isNaN(parseFloat(data.price))) 
            errors.push("El precio debe ser un número válido.");
        
        if (!data.category || data.category.trim() === "") 
            errors.push("La categoría es obligatoria.");
            
        if (!data.imgs || data.imgs.trim() === "")
            errors.push("Debes incluir al menos una URL de imagen.");

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
};

window.handleLogin = async (e) => {
    e.preventDefault();
    const u = e.target.user.value; // Cédula
    const p = e.target.pass.value;

    if (!Validator.email(u)) {
        Swal.fire("Ocurio un Error!","El correo no cumple con el formato establecido.","error");
        return;
    }
    if (!Validator.password(p)) {
        Swal.fire("Ocurio un Error!","La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.","error");
        return;
    }
    
    // ... resto de tu fetch al api.php
};


window.handleProductSubmit = async (e) => {
    e.preventDefault();
    
    const productData = {
        name: e.target.name.value,
        price: e.target.price.value || null,
        category: e.target.category.value,
        desc: e.target.desc.value,
        imgs: e.target.imgs.value
    };

    const validation = Validator.product(productData);

    if (!validation.isValid) {
        alert("Errores en el formulario:\n- " + validation.errors.join("\n- "));
        return;
    }

    // Si pasa la validación, procedemos al fetch
    console.log("Datos validados, enviando a la base de datos...");
    // ... tu fetch aquí
};