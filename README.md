Introduction
------------
This is a collection of PHP scripts and supporting files made for a PHP XML processing example


Requirements
------------
These scripts expects to be installed on an environment running Apache http server version 2 (or higher) with PHP
version 7.4 (or higher)


Installation
------------
* Install all files somewhere where the environment already have Apache with PHP support installed
* If needed, create the necessary Apache virtual host configuration for the 'test' site.
* Make sure Apache's _DocumentRoot_ is set to the 'public' folder.
* Configure Apache to allow processing of the .htaccess file.
* execute 'composer install'


Running the application
-----------------------
Use either _cURL_ from the command-line or a software such as _Postman_ to send a POST http request with the raw XML content
as the only payload. Do not use either 'form-data' nor 'x-www-form-urlencoded' format.


Caveats
-------
* The code was developed and tested on a personal computer running Windows 10 with Apache 2.4.43 and PHP 7.4.11.
  Nevertheless, I expect it to also run on other Operating System (OS).


Assumptions made
----------------
* The posted (received) XML will always be relatively small in size and thus will not cause an out of memory errors.
* When the app received a request xml, the value of the &lt;recipient&gt; is ignored. 
  When sending a response xml, the app will just use its own name for the &lt;sender&gt; node.
* The app also ignored the received &lt;timestamp&gt; value, it uses its own timestamp in the responses. 
* It is okay that one or more of the _required_ leaf xml node to be empty as long as it exist. 
  For example the &lt;echo&gt; node in the &lt;body&gt; of a ping_request.
* When an XML request was received with an unknown _type_, should it be reported as '400 Bad Request' or as 
  '500 Internal Server Error'? I have chosen it to be the latter.
* I am unable to think of cases where the request xml passes the validation step in Validator::run() but has some
  other error. As a result, I have not implemented a body-&gt;error node in ping_response and reverse_response.     

How to add a new entry point
----------------------------
* Choose a name (a word) that ends with '_request', e.g. _my_request_.
* Design the xml structure based on the common structure of existing entry points (_ping_request_, _reverse_request_)
* Create the appropriate xsd file, e.g. _my_request.xsd_ and _my_response.xsd_, place it in the src/xsds folder
* Make a php class script with the new name (e.g. My.php) and place it in the src/Xml/Response folder. 
  Note that the class name (and its php file name) must be the ucfirst() of name.
* Add the 'use Assessment\Xml\Response\My;' line at the top of the file for XML\Parser class

Afterthoughts
-------------
* I am on the opinion that structurally it would be easier if it is allowed to use different endpoints for each request. 
  For example: http://sitename.test/ping_request and http://sitename.test/reverse_request
  That way, the app can just examine the URL to determine the intended type and use the correct xsd to validate against
  without having to try parsing the xml first to retrieve the header-&gt;type node.
* I was surprised that the xsd was using &lt;xs: sequence&gt; instead of &lt;xs: all&gt; which meant that all elements
  must be in order. Is this really needed?
* I have used DomDocument::schemaValidate() to validate the received xml against the appropriate xsd, this makes it
  easy for me to implement but with the downside that the error message might be a little bit too 'wordy'.
* I have used htmlspecialchars() function with ENT_XML1 flag to guard against ampersand (&) and quote (") characters
  in the strings to be placed in the xml response. Probably a cleaner way is to extend the SimpleXMLElement class and
  override the addChild() method to use CDATA tag when needed.
