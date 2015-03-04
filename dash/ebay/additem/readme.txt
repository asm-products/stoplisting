###########################################################################
##  AJAXCategories                                                       ##
##  A simple script that utilizes AJAX to pull category listings         ##
##  Dynamically without reloading the screen.                            ##
##  Written by Thomas Deliduka                                           ##
##  Version 1.0                                                          ##
###########################################################################


This is a simple set of code programmed in PHP that pulls the category
cached data from a MySQL database and will display it using a technology
called AJAX. AJAX allows you to run server-side code through JavaScript
without refreshing the page.  An example of this is already working at: 
    http://www.xenocart.com/ebay/ebaycats.php

This code is meant as an example for you to incorporate into your
application. So, you will most-likely be taking the HTML & PHP code that
is within here and editing it as needed and putting it into your own format

DEPENDANCIES
    Xajax: http://xajax.sourceforge.net/

HOW TO RUN THIS CODE
    1. Download the code from this system. The table.sql file contains the
       structure of the table that is used in the code. If you are an ebay
       developer, you should already have your own system for gathering
       the category information from ebay and putting it into a local
       format. This code relies on the information being within a MySQL
       database. If your table struture is different edit your SQL
       statements to follow your table's structure.
    
    NOTE: An API to download the category listings into the table is not
    included in this code.
    
    2. Head to the http://xajax.sourceforge.net/ and download the latest
       file. It would be named xajax.inc.php put it in either your PHP
       code path or somewhere you can include it in your code.
    
    3. Edit ebaycats.php and put in your own database connection
       information at the top. Optionally you can edit the SQL statements
       to connect to your own table.
    
    4. Upload it to your server where you can connect to a database and
       then execute!
  
KEY SECTIONS OF CODE:
    * The PHP code at the top of ebaycats.php, this contains the xajax
      object calls and the fuction which will be executed when category
      boxes are called.
    
    * With the <head> of the document, the call to the xajax javascript.
      This is crucial to the execution of ajax code.
    
    * The HTML form which has the table and the first box defined as well
      as the final category field. That is the field that will contain the
      number of the category to be passed through the XML or SOAP API for
      that ebay listing.

