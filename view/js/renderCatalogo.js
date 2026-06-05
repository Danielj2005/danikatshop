
const renderCatalogo = (productos) => {
    document.getElementById('cards').innerHTML = productos.map((p) => createCatalogo(p.id, p.nombre, p.precio, p.images, p.categorias)).join('');       
}