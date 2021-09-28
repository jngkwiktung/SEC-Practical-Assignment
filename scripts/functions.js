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
    generatePrivatePublicKey(); // Generate client side rsa keys

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
    sessionStorage.setItem("privateKey", crypt.getPrivateKey());
    sessionStorage.setItem("publicKey", crypt.getPublicKey());
}

function removePrivatePublicKey() {
    sessionStorage.removeItem("privateKey");
    sessionStorage.removeItem("publicKey");
}