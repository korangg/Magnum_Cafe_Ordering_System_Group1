let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(name, price) {
    const itemIndex = cart.findIndex(item => item.name === name);
    if (itemIndex !== -1) {
        cart[itemIndex].qty += 1;
    } else {
        cart.push({ name, price, qty: 1 });
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    alert(name + ' added to cart!');
}

function updateCartDisplay() {
    if (!document.getElementById('cart-items')) return;

    const items = document.getElementById('cart-items');
    const totalSpan = document.getElementById('total');
    items.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        const li = document.createElement('li');
        li.innerHTML = `
            <img src="images/${item.name.toLowerCase().replace(/\s+/g, '')}.jpg" alt="${item.name}" style="width:50px; border-radius:5px; margin-right:10px;">
            ${item.name} - RM${item.price.toFixed(2)} x ${item.qty}
            <button onclick="removeItem(${index})">Remove</button>
            <button onclick="increaseQty(${index})">+</button>
            <button onclick="decreaseQty(${index})">-</button>
        `;
        items.appendChild(li);
        total += item.price * item.qty;
    });

    totalSpan.textContent = total.toFixed(2);
}

function removeItem(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

function increaseQty(index) {
    cart[index].qty += 1;
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

function decreaseQty(index) {
    if (cart[index].qty > 1) {
        cart[index].qty -= 1;
    } else {
        cart.splice(index, 1);
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

window.addEventListener('DOMContentLoaded', updateCartDisplay);

function searchProducts() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const products = document.querySelectorAll(".product h2");

    products.forEach(product => {
        const card = product.closest(".product");
        if (product.textContent.toLowerCase().includes(input)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

function viewProduct(name, price, image, description) {
    const product = { name, price, image, description };
    localStorage.setItem("selectedProduct", JSON.stringify(product));
    window.location.href = "product_detail.php";
}
