// ============================================================
// MP SWEET TREATS - MAIN JAVASCRIPT
// ============================================================


// ============================================================
// HAMBURGER MENU
// ============================================================

const hamburgerBtn = document.getElementById("hamburgerBtn");
const navMenu = document.getElementById("navMenu");

if (hamburgerBtn && navMenu) {

    hamburgerBtn.addEventListener("click", function () {

        navMenu.classList.toggle("active");

    });

}


// ============================================================
// CLOSE MOBILE MENU AFTER CLICK
// ============================================================

const navLinks = document.querySelectorAll("#navMenu a");

navLinks.forEach(function (link) {

    link.addEventListener("click", function () {

        if (navMenu) {
            navMenu.classList.remove("active");
        }

    });

});


// ============================================================
// FEATURED PRODUCTS SCROLL
// ============================================================

const productScroll = document.getElementById("productScroll");
const scrollLeftBtn = document.getElementById("scrollLeftBtn");
const scrollRightBtn = document.getElementById("scrollRightBtn");


if (productScroll && scrollLeftBtn && scrollRightBtn) {

    scrollRightBtn.addEventListener("click", function () {

        productScroll.scrollBy({
            left: 300,
            behavior: "smooth"
        });

    });


    scrollLeftBtn.addEventListener("click", function () {

        productScroll.scrollBy({
            left: -300,
            behavior: "smooth"
        });

    });

}


// ============================================================
// CART VARIABLES
// ============================================================

let cart = [];


const cartCount = document.getElementById("cartCount");
const cartIcon = document.getElementById("cartIcon");
const cartBox = document.getElementById("cartBox");
const cartItemsDiv = document.getElementById("cartItems");
const cartTotalSpan = document.getElementById("cartTotal");


// ============================================================
// ADD PRODUCTS TO CART
// ============================================================

const addToCartButtons =
    document.querySelectorAll(".add-to-cart");


addToCartButtons.forEach(function (button) {

    button.addEventListener("click", function () {

        const name = button.dataset.name;
        const price = Number(button.dataset.price);


        if (!name || price <= 0) {

            return;

        }


        // Add product
        cart.push({

            name: name,
            price: price

        });


        updateCart();

        openCart();

    });

});


// ============================================================
// WEDDING / CUSTOM PRODUCT
// ============================================================

const customProductButton =
    document.querySelector(".custom-product-btn");


if (customProductButton) {

    customProductButton.addEventListener("click", function () {

        const customSection =
            document.getElementById("custom-orders");


        if (customSection) {

            customSection.scrollIntoView({

                behavior: "smooth",
                block: "center"

            });

        }

    });

}


// ============================================================
// CART ICON
// ============================================================

if (cartIcon) {

    cartIcon.addEventListener("click", function (event) {

        event.preventDefault();

        cartBox.classList.toggle("active");

    });

}


// ============================================================
// OPEN CART
// ============================================================

function openCart() {

    if (cartBox) {

        cartBox.classList.add("active");

    }

}


// ============================================================
// UPDATE CART
// ============================================================

function updateCart() {

    updateCartCount();

    renderCart();

}


// ============================================================
// CART COUNT
// ============================================================

function updateCartCount() {

    if (cartCount) {

        cartCount.textContent = cart.length;

    }

}


// ============================================================
// RENDER CART
// ============================================================

function renderCart() {

    if (!cartItemsDiv || !cartTotalSpan) {

        return;

    }


    cartItemsDiv.innerHTML = "";


    let total = 0;


    // Empty cart
    if (cart.length === 0) {

        cartItemsDiv.innerHTML = `
            <p class="empty-cart">
                Your cart is empty.
            </p>
        `;

        cartTotalSpan.textContent = "0";

        return;

    }


    // Display products
    cart.forEach(function (item, index) {

        total += item.price;


        const itemDiv =
            document.createElement("div");


        itemDiv.className = "cart-item";


        itemDiv.innerHTML = `

            <span>
                ${item.name}
            </span>

            <span>
                TZS ${item.price.toLocaleString()}
            </span>

            <button
                class="remove-btn"
                data-index="${index}"
                title="Remove item">

                <i class="fa-solid fa-trash"></i>

            </button>

        `;


        cartItemsDiv.appendChild(itemDiv);

    });


    cartTotalSpan.textContent =
        total.toLocaleString();


    // Remove buttons
    const removeButtons =
        document.querySelectorAll(".remove-btn");


    removeButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const index =
                Number(button.dataset.index);


            cart.splice(index, 1);


            updateCart();

        });

    });

}


// ============================================================
// CUSTOM ORDER FORM
// ============================================================

const customOrderForm =
    document.getElementById("customOrderForm");


const customFormMessage =
    document.getElementById("customFormMessage");


if (customOrderForm) {

    customOrderForm.addEventListener("submit", async function (event) {

        event.preventDefault();


        const name =
            document.getElementById("customName").value.trim();


        const phone =
            document.getElementById("customPhone").value.trim();


        const details =
            document.getElementById("customDetails").value.trim();


        // Validate
        if (!name || !phone || !details) {

            showMessage(
                customFormMessage,
                "Please fill all fields.",
                "error"
            );

            return;

        }


        // Validate phone
        if (!validatePhone(phone)) {

            showMessage(
                customFormMessage,
                "Please enter a valid 10-digit phone number.",
                "error"
            );

            return;

        }


        const formData = new FormData();


        formData.append("order_type", "custom");
        formData.append("name", name);
        formData.append("phone", phone);
        formData.append("address", "Custom Order");
        formData.append("details", details);


        showMessage(
            customFormMessage,
            "Sending your request...",
            ""
        );


        try {

            const response =
                await fetch("place_order.php", {

                    method: "POST",
                    body: formData

                });


            const result =
                await response.json();


            if (result.success) {

                showMessage(
                    customFormMessage,
                    result.message,
                    "success"
                );


                customOrderForm.reset();

            } else {

                showMessage(
                    customFormMessage,
                    result.message,
                    "error"
                );

            }

        } catch (error) {

            console.error(error);


            showMessage(
                customFormMessage,
                "Unable to send request. Please try again.",
                "error"
            );

        }

    });

}


// ============================================================
// CHECKOUT VARIABLES
// ============================================================

const checkoutBtn =
    document.getElementById("checkoutBtn");


const checkoutFormBox =
    document.getElementById("checkoutFormBox");


const checkoutForm =
    document.getElementById("checkoutForm");


const checkoutMessage =
    document.getElementById("checkoutMessage");


// ============================================================
// OPEN CHECKOUT
// ============================================================

if (checkoutBtn) {

    checkoutBtn.addEventListener("click", function () {


        if (cart.length === 0) {

            showMessage(
                checkoutMessage,
                "Your cart is empty. Please add a product first.",
                "error"
            );

            return;

        }


        checkoutFormBox.classList.toggle("active");


        if (checkoutFormBox.classList.contains("active")) {

            checkoutFormBox.scrollIntoView({

                behavior: "smooth",
                block: "nearest"

            });

        }

    });

}


// ============================================================
// CHECKOUT SUBMIT
// ============================================================

if (checkoutForm) {

    checkoutForm.addEventListener("submit", async function (event) {

        event.preventDefault();


        // Customer information
        const name =
            document.getElementById("checkoutName").value.trim();


        const phone =
            document.getElementById("checkoutPhone").value.trim();


        const address =
            document.getElementById("checkoutAddress").value.trim();


        const details =
            document.getElementById("checkoutDetails").value.trim();


        // Validate
        if (!name || !phone || !address) {

            showMessage(
                checkoutMessage,
                "Please fill all required fields.",
                "error"
            );

            return;

        }


        // Phone validation
        if (!validatePhone(phone)) {

            showMessage(
                checkoutMessage,
                "Please enter a valid 10-digit phone number.",
                "error"
            );

            return;

        }


        // Cart validation
        if (cart.length === 0) {

            showMessage(
                checkoutMessage,
                "Your cart is empty.",
                "error"
            );

            return;

        }


        // Calculate total
        let total = 0;


        cart.forEach(function (item) {

            total += Number(item.price);

        });


        // Convert cart to JSON
        const cartJSON =
            JSON.stringify(cart);


        // Form data
        const formData =
            new FormData();


        formData.append(
            "order_type",
            "cart"
        );


        formData.append(
            "name",
            name
        );


        formData.append(
            "phone",
            phone
        );


        formData.append(
            "address",
            address
        );


        formData.append(
            "details",
            details
        );


        formData.append(
            "cart",
            cartJSON
        );


        formData.append(
            "total",
            total
        );


        showMessage(
            checkoutMessage,
            "Placing your order...",
            ""
        );


        try {

            const response =
                await fetch("place_order.php", {

                    method: "POST",
                    body: formData

                });


            const result =
                await response.json();


            if (result.success) {


                showMessage(
                    checkoutMessage,
                    result.message,
                    "success"
                );


                // Clear cart
                cart = [];


                updateCart();


                // Clear form
                checkoutForm.reset();


                // Hide checkout form
                setTimeout(function () {

                    checkoutFormBox.classList.remove("active");

                }, 2500);


            } else {

                showMessage(
                    checkoutMessage,
                    result.message,
                    "error"
                );

            }


        } catch (error) {

            console.error(error);


            showMessage(
                checkoutMessage,
                "Something went wrong. Please try again.",
                "error"
            );

        }

    });

}


// ============================================================
// PHONE VALIDATION
// ============================================================

function validatePhone(phone) {

    const phonePattern =
        /^[0-9]{10}$/;


    return phonePattern.test(phone);

}


// ============================================================
// DISPLAY MESSAGE
// ============================================================

function showMessage(element, message, type) {

    if (!element) {

        return;

    }


    element.textContent = message;


    if (type === "success") {

        element.className =
            "form-message success";

    } else if (type === "error") {

        element.className =
            "form-message error";

    } else {

        element.className =
            "form-message";

    }

}


// ============================================================
// INITIALIZE CART
// ============================================================

updateCart();