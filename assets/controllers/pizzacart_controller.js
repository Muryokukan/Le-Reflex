import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        "cartCount",
        "cartItems",
        "cartAmount",
        "cartModal",
        "totalAmount"
    ];

    connect() {
        this.cart = JSON.parse(localStorage.getItem('cart')) || [];
        this.initializePage();
    }

    toggleCart() {
        this.cartModalTarget.classList.toggle('hidden');
        this.cartModalTarget.classList.toggle('flex');
        this.updateCartDisplay();
    }

    updateCartCount() {
        try {
            const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
            this.cartCountTarget.textContent = totalItems;
            
            // Optionnel : cacher/montrer le compteur
            if (totalItems === 0) {
                this.cartCountTarget.classList.add('hidden');
            } else {
                this.cartCountTarget.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    updateCartDisplay() {
        try {
            this.cartItemsTarget.innerHTML = '';
            let total = 0;

            this.cart.forEach((item, index) => {
                const itemTotal = item.totalPrice * item.quantity;
                total += itemTotal;

                const itemElement = document.createElement('div');
                itemElement.className = 'flex justify-between items-center p-2 border-b';
                itemElement.innerHTML = `
                    <div class="flex-1">
                        <h3 class="font-bold">${item.name}</h3>
                        <p class="text-sm text-gray-600">
                            ${item.toppings.map(t => t.name).join(', ')}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button data-action="click->pizzacart#decrementQuantity" data-index="${index}" class="px-2 bg-gray-200 rounded">-</button>
                        <span>${item.quantity}</span>
                        <button data-action="click->pizzacart#incrementQuantity" data-index="${index}" class="px-2 bg-gray-200 rounded">+</button>
                        <span class="ml-4">${(itemTotal).toFixed(2).replace('.', ',')} €</span>
                        <button data-action="click->pizzacart#removeItem" data-index="${index}" class="ml-2 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                this.cartItemsTarget.appendChild(itemElement);
            });

            this.totalAmountTarget.textContent = `${total.toFixed(2).replace('.', ',')} €`;
        } catch (error) {
            console.error('Error updating cart display:', error);
        }
    }

    addToCart(event) {
        try {
            const pizzaId = event.currentTarget.dataset.pizzacartPizzaIdParam;
            const pizzaCard = document.querySelector(`[data-pizza-id="${pizzaId}"]`);
            
            if (!pizzaCard) return;

            const pizzaName = pizzaCard.querySelector('h2').textContent;
            const basePrice = parseFloat(pizzaCard.querySelector('.text-red-600').textContent.replace(' €', '').replace(',', '.'));
            
            const selectedToppings = Array.from(pizzaCard.querySelectorAll('input[type="checkbox"]:checked')).map(checkbox => ({
                id: checkbox.value,
                name: checkbox.dataset.name,
                price: parseFloat(checkbox.dataset.price)
            }));

            const totalPrice = basePrice + selectedToppings.reduce((sum, topping) => sum + topping.price, 0);

            const cartItem = {
                id: pizzaId,
                name: pizzaName,
                basePrice: basePrice,
                toppings: selectedToppings,
                totalPrice: totalPrice,
                quantity: 1
            };

            const existingItemIndex = this.cart.findIndex(item => 
                item.id === cartItem.id && 
                JSON.stringify(item.toppings) === JSON.stringify(cartItem.toppings)
            );

            if (existingItemIndex > -1) {
                this.cart[existingItemIndex].quantity += 1;
            } else {
                this.cart.push(cartItem);
            }

            this.saveCart();
            this.updateCartCount();
            this.updateCartDisplay();
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    }

    incrementQuantity(event) {
        const index = event.currentTarget.dataset.index;
        this.updateQuantity(index, 1);
    }

    decrementQuantity(event) {
        const index = event.currentTarget.dataset.index;
        this.updateQuantity(index, -1);
    }

    updateQuantity(index, change) {
        try {
            this.cart[index].quantity += change;
            if (this.cart[index].quantity <= 0) {
                this.cart.splice(index, 1);
            }
            this.saveCart();
            this.updateCartCount();
            this.updateCartDisplay();
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    }

    removeItem(event) {
        try {
            const index = event.currentTarget.dataset.index;
            this.cart.splice(index, 1);
            this.saveCart();
            this.updateCartCount();
            this.updateCartDisplay();
        } catch (error) {
            console.error('Error removing item:', error);
        }
    }

    clearCart() {
        this.cart = [];
        this.saveCart();
        this.updateCartCount();
        this.updateCartDisplay();
    }

    saveCart() {
        localStorage.setItem('cart', JSON.stringify(this.cart));
    }

    initializePage() {
        this.initializeToppingToggles();
        this.initializeToppingCheckboxes();
        this.updateCartCount();
    }

    initializeToppingToggles() {
        document.querySelectorAll('[id^="topping-toggle-"]').forEach(button => {
            button.addEventListener('click', (event) => {
                const pizzaId = event.currentTarget.id.split('-')[2];
                const toppingList = document.getElementById(`topping-list-${pizzaId}`);
                toppingList?.classList.toggle('hidden');
            });
        });
    }

    initializeToppingCheckboxes() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', (event) => {
                const pizzaCard = event.currentTarget.closest('[data-pizza-id]');
                if (!pizzaCard) return;

                const basePrice = parseFloat(pizzaCard.querySelector('.text-red-600').textContent.replace(' €', '').replace(',', '.'));
                const totalElement = pizzaCard.querySelector('.total-price');
                
                let total = basePrice;
                pizzaCard.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                    total += parseFloat(checkbox.dataset.price);
                });
                
                totalElement.textContent = `${total.toFixed(2).replace('.', ',')} €`;
            });
        });
    }
}
