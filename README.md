Squid Fácil Magento Module
=======

Introduction
------------
This is the Squid Fácil Webservice Module. This Magento module connects to
our Webservice and display the products inside the Magento backend, so the 
system administrator can import our products, and also its responsible to keep 
the product stock and information updated.


Installation
------------
1. Copy this app folder over your project
2. Make sure you refreshed or disabled your cache
3. There will be a new tab in your admin backend
4. Logout and Login again so you refresh your permissions
5. Fill the configuration with your email and token
6. Import the products.
7. Update your store URL in our platform so your store receive update notifications (usually the address is http://yourstore.com/notification)

Update Instructions
-------------------
Remove the module folder at app/local/squidfacil.

Copy/Upload the app folder over your installation directory.

Warning
-------
Do not forget to add a category and set it as active before importing the products,
otherwise, the product will not be available in frontend.


To-do List
----------
1. Order Webservice integration
