

const scrappCoin = async () => {
    try {
        const url = `../controller/dolarApi.php`;
    
        const response = await fetch(url);
        if (!response.ok) throw new Error("Error en la petición");

        const data = await response.json();

        if (data.status === "success") {
            // Ejemplo de uso:
            // console.log(`Dólar BCV: ${data.USD} Bs.`);
            // console.log(`USDT P2P: ${data.EURO} Bs.`);
            return data;
        }
    }catch (e){
        console.error(e);
    }
    
};

