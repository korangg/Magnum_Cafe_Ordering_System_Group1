let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(name, price, qtyToAdd) {
    // This is the updated addToCart function to check stock
    const itemIndex = cart.findIndex(item => item.name === name);
    let currentQty = itemIndex !== -1 ? cart[itemIndex].qty : 0;
    let newQty = currentQty + qtyToAdd;

    fetch('check_stock.php?name=' + encodeURIComponent(name))
        .then(response => response.json())
        .then(data => {
            if (data.stock >= newQty) {
                if (itemIndex !== -1) {
                    cart[itemIndex].qty = newQty;
                } else {
                    cart.push({ name, price, qty: qtyToAdd });
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                alert(name + ' added to cart!');
                updateCartDisplay();
            } else {
                alert('Not enough stock for ' + name + '! Only ' + data.stock + ' left.');
            }
        })
        .catch(error => {
            console.error('Error fetching stock:', error);
            alert('Could not add to cart due to a server error.');
        });
}

function updateCartDisplay() {
    const itemsContainer = document.getElementById('cart-items');
    const totalSpan = document.getElementById('total');
    if (!itemsContainer || !totalSpan) {
        // Stop the function if the elements are not on the page
        return;
    }

    itemsContainer.innerHTML = '';
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
        itemsContainer.appendChild(li);
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
    const item = cart[index];
    const newQty = item.qty + 1;
    
    fetch('check_stock.php?name=' + encodeURIComponent(item.name))
        .then(response => response.json())
        .then(data => {
            if (data.stock >= newQty) {
                cart[index].qty = newQty;
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
            } else {
                alert('Cannot add more. Only ' + data.stock + ' left in stock.');
            }
        })
        .catch(error => {
            console.error('Error fetching stock:', error);
        });
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