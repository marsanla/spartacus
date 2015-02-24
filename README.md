The Challenge:
------------------------
Create a basic Web-Application using PHP/JavaScript/jQuery/Expensify API to, without any page refreshes:
Authenticate to an account
Download existing transactions
Create new transactions
Most of the logic and code should be in JavaScript
 
How to do it:
--------------------
Part of the challenge is dealing with an incomplete or ambiguous specification (after all, you're supposed to be a person who can figure things out without explicit instructions). But here is some general guidance:
 
1) Find some place to host a basic PHP environment. Anywhere works. If you don't have a server of your own, Amazon will give you one for free here: 
 http://aws.amazon.com/free/
 
2) Create on or more PHP files that together comprise a single ajax-ified application.
 
3) First, when loaded, if there is no "authToken" cookie set, the page should show a simple username/password form. (if it is already set, the user is already logged in; skip to (6).)
 
4. When you click "Sign In" it should, using AJAX, call the Authenticate function on the Expensify API. Details follow:
 http://expensify.com/api.html
partnerName: applicant
partnerPassword: d7c3119c6cdab02d68d9
partnerUserID: <email address>
partnerUserSecret: <password>
Feel free to test using this account (but read the value from the
form, don't hard-code it):
email: expensifytest@mailinator.com
password: hire_me
5) If Authentic Fails, it should show some meaningful error message and allow you to try again.
 
6) Upon success, use "Get" to get a list of all transactions in the account. Again, do this all via AJAX, without any page loads.
 
7) With the "Get" results, assemble and display a table showing all transactions in the account. Make it pretty enough that real live users could reasonably understand it.
 
8) Next, show a form that prompts the user for a date, merchant name, and amount. When the user clicks "Add" it calls "CreateTransaction" to create the new transaction, as well as adds it to the table.
 
9) Finally to prove that the "CreateTransaction" worked, let the user refresh the page. the authToken should be set, so it should just skip right (5), redownload the latests transactions, and show the full table.
 
10) When done, send a link to jobs@expensify.com for review, along with a blow-by-blow of how long it took, what problems you encountered, how you overcame them, etc.