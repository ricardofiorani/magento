Squid Fácil Magento API
=======

Introduction
------------
This is the Squid Fácil Webservice API module. This Magento module connects to
our Webservice and display the products inside the Magento backend, so the 
system administrator can import our products, and also its responsible to keep 
the product stock and information updated.

Installation
------------
1. Copy this app folder over your project
2. There will be a new tab in your admin backend
3. Fill the configuration with your email and token
4. Import the products.

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
1. Get the HTTP posts and update product info
2. Mass action for importing products
3. Order Webservice integration
