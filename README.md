Included are wip Magento 2 modules:

Brad_Base

* Base module which currently contains one admin menu item

Brad_Dropship

* Simple dropship module with a custom model for suppliers / vendors, a product attribute to set a supplier and order observer to send order details to appropriate supplier(s)
* TODO: fix source model for product attribute, build out observer logic for new order that sends info to the supplier email
* Goals: continue building out the supplier integrations to include API, make that process as dynamic as possible

Brad_Restrictions

* Product restriction module with a custom model for restriction rules, a product attribute to set restriction rules
* TODO: add source model for product attribute, build out rule model & admin forms to include restricting by shipping region / zip, products that it can't be purchased with, etc.
* Goals: make rule creation as simple and efficient as possible. Build out mass actions and look into dynamic rule creation based on attributes
