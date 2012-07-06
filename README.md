# Secure Callback for Wicked

This module handles the creation and expiration of secure URLs that map to PHP function callbacks executed when the user clicks on the link.
Secure Callback creates a unique, cryptographically secure callback URL and stores a data payload with it.

When the URL is clicked, the data payload is loaded and executed. 

Secure Callback can be used to generate asynchronous events such as registration validation.

## Usage