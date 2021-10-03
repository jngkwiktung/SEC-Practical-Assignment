function hashPassword() {
    var input = document.getElementById('pw').value;
    if (input == "") {
        var hash = "";

    } else {
        var hash = SHA256.hash(input);
    }

    return hash;
}

function encryptUsnPass(publicKey) {
    let username = document.getElementById('username').value;
    let passwordHash = hashPassword();

    let timestamp = Math.floor(new Date().getTime() / 1000);

    let passwordPlaintext = passwordHash + "&&&&&" + timestamp;


    // encrypt the plaintext using the public key
    let usernameCiphertext = RSA_encryption(username, publicKey);
    let passwordCiphertext = RSA_encryption(passwordPlaintext, publicKey);

    document.getElementById("username").value = usernameCiphertext;
    document.getElementById("pw").value = passwordCiphertext;

}

function RSA_encryption(message, publicKey) {

    var encrypt = new JSEncrypt();
    encrypt.setPublicKey(publicKey);
    var encrypted = encrypt.encrypt(message);

    return encrypted;
}

function RSA_decryption(ciphertext, privateKey) {

    var decrypt = new JSEncrypt();
    decrypt.setPrivateKey(privateKey);
    var decrypted = decrypt.decrypt(ciphertext);

    return decrypted;
}

function generatePrivatePublicKey() {
    var sKeySize = 1024;
    var keySize = parseInt(sKeySize);
    var crypt = new JSEncrypt({ default_key_size: keySize });

    crypt.getKey();
    localStorage.setItem("privateKey", crypt.getPrivateKey());
    localStorage.setItem("publicKey", crypt.getPublicKey());
}

function removePrivatePublicKey() {
    localStorage.removeItem("privateKey");
    localStorage.removeItem("publicKey");
}

function sendPublicKey() {
    publicKey = localStorage.getItem("publicKey");
    $.ajax({
        url: '../setClientPublicKey.php',
        type: 'POST',
        data: { publicKeyClient: publicKey },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Error: Ajax cannot send client public key to server");
        }
    });
}

function decryptData(htmlId, ciphertext, prefix) {
    privateKey = localStorage.getItem("privateKey");
    decrypted = RSA_decryption(ciphertext, privateKey);
    document.getElementById(htmlId).innerHTML = prefix + decrypted;
}

function encryptProductLink(htmlId, message, publicKey, prefix) {
    let encrypted = RSA_encryption(message, publicKey);
    document.getElementById(htmlId).href = prefix + encrypted;
}

function encryptProduct(message, publicKey) {
    let encrypted = RSA_encryption(message, publicKey);
    // document.getElementById(htmlId).innerHTML = encrypted;
    return encrypted;
}

function encryptSendQuantity(publicKey) {
    let quantity = document.getElementById('quantity').value;
    let timestamp = Math.floor(new Date().getTime() / 1000);
    let plaintext = quantity + "&&&&&" + timestamp;
    let ciphertext = RSA_encryption(plaintext, publicKey);
    document.getElementById("quantity").value = ciphertext;
}

function decryptImage(htmlId, ciphertext, prefix) {
    privateKey = localStorage.getItem("privateKey");
    decrypted = RSA_decryption(ciphertext, privateKey);
    document.getElementById(htmlId).src = prefix + decrypted;
}

function generateRandomDESKey() {
    var length = 15;
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}

function sendDESKey(publicKey) {
    desKey = localStorage.getItem("desKey");
    let keyCiphertext = RSA_encryption(desKey, publicKey);
    $.ajax({
        url: '../setSessionKey.php',
        type: 'POST',
        data: { desKeyClient: keyCiphertext },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Error: Ajax cannot send client public key to server");
        }
    });
}

function establishKeys(publicKey) {
    generatePrivatePublicKey(); // Generate client side rsa keys
    sendPublicKey();
    let desKey = generateRandomDESKey();
    localStorage.setItem("desKey", desKey);
    sendDESKey(publicKey);
}

function checkSessionKey(ciphertext) {
    let desKey = localStorage.getItem("desKey");
    let privateKey = localStorage.getItem("privateKey");
    let decryptedKey = RSA_decryption(ciphertext, privateKey);
    if (desKey !== decryptedKey) {
        alert("Session timeout:\n\nSession ID between client and server are not the same or has been broken!\nRedirecting to login page.");
        $.ajax({
            url: '../login/index.php',
            type: 'HEAD',
            error: function() {
                window.location = 'logout.php';
            },
            success: function() {
                window.location = '../logout.php';
            }
        });
    }
}


var calcSubTotal = function(productName) {
    var quantity = parseInt(document.getElementById(productName + 'quantity').value);
    if (quantity > 0) {
        var price = parseFloat(document.getElementById(productName + 'price').value);
        var subtotal = price * quantity;
        document.getElementById(productName + "subtotal").innerHTML = '$' + subtotal;
        document.getElementById(productName + "total").value = subtotal;
        return subtotal;
    }
    document.getElementById(productName + "subtotal").innerHTML = 0;
    document.getElementById(productName + "total").value = 0;
    return 0;
}

function updateCart(ciphertext) {
    privateKey = localStorage.getItem("privateKey");
    size = RSA_decryption(ciphertext, privateKey);
    var price = null;
    var total = null;
    var quantity = null;
    for (let i = 0; i < size; i++) {
        price += parseFloat(document.getElementById('Product' + i + 'price').value);
        total += calcSubTotal('Product' + i);
        quantity += parseInt(document.getElementById('Product' + i + 'quantity').value);
    }

    document.getElementById("pPrice").innerHTML = '$' + price;
    document.getElementById("ptotalQuantity").innerHTML = quantity;
    document.getElementById("totalQuantity").value = quantity;
    document.getElementById("ptotalPrice").innerHTML = '$' + total;
    document.getElementById("totalPrice").value = total;
}

function setCartInputValue(htmlId, ciphertext) {
    privateKey = localStorage.getItem("privateKey");
    decrypted = RSA_decryption(ciphertext, privateKey);
    document.getElementById(htmlId).value = decrypted;
}

function emptyCart(publicKey) {
    // r = confirm('Are you sure you want to empty your cart?')
    if (confirm('Are you sure you want to empty your cart?')) {
        let cartId = document.getElementById("cartId").value;
        let timestamp = Math.floor(new Date().getTime() / 1000);
        let plaintext = cartId + "&&&&&" + timestamp;
        let ciphertext = RSA_encryption(plaintext, publicKey);
        document.getElementById("emptyCartInput").value = ciphertext;
    }
}

function placeOrder(publicKey, ciphertext) {
    privateKey = localStorage.getItem("privateKey");
    size = RSA_decryption(ciphertext, privateKey);
    cartId = document.getElementById("cartId").value;
    address = document.getElementById("addressInput").value;
    cardNum = document.getElementById("creditCardInput").value;
    timestamp = Math.floor(new Date().getTime() / 1000);
    totalPrice = document.getElementById("totalPrice").value;
    plaintext = cartId + "&&&&&" + timestamp + "&&&&&" + address + "&&&&&" + cardNum + "&&&&&" + totalPrice;
    for (let i = 0; i < size; i++) {
        productId = document.getElementById("Product" + i).value;
        productQuantity = document.getElementById("Product" + i + "quantity").value;
        plaintext += "&&&&&" + productId + "&&&&&" + productQuantity;
    }
    ciphertext = RSA_encryption(plaintext, publicKey);
    document.getElementById("placeOrderInput").value = ciphertext;
}