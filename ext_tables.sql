#
# Table structure for table 'tx_pleskwidget_server'
#
CREATE TABLE tx_pleskwidget_server (
    -- The password field stores encrypted data using TYPO3's CipherService (XChaCha20-Poly1305).
    -- For a plaintext input limited to 64 characters in the backend, the encrypted string
    -- (including nonce, authentication tag, and Base64 encoding) is approximately 140 characters long.
    -- VARCHAR(255) provides sufficient buffer for this and potential future minor increases.
    password varchar(255) DEFAULT '' NOT NULL,
);
