# stripe-market

    Contributors: nerdfiles
    Donate link: http://nerdfiles.net
    Tags: stripe
    Requires at least: 4.x
    Tested up to: 4.x
    Stable tag: 1.0.0
    License: WTFPL
    License URI: http://www.wtfpl.net/txt/copying/
    PHP Supported: >5.5

Based on [WP-Stripe](https://github.com/humanmade/wp-stripe) which adds 
Wordpress Shortcodes to facilitate Stripe Connect Flow OAuth and Shared 
Customers.

> “Redistribution creates dependency, property pre-distribution empowers.”
> — Capitalism 3.0: A Guide to Reclaiming the Commons. John Rawls. URL: http://books.google.com/books?id=q0zdAwAAQBAJ&pg=PA105&lpg=PA105&dq=Redistribution+creates+dependency,+property+pre-distribution+empowers.&source=bl&ots=p7r-gLLyXa&sig=G8-VW9ze0PmUOTXewLhyN6ZGrtU&hl=en&sa=X&ei=NJtGVPb3M4-PNriMgIgI&ved=0CCUQ6AEwAQ#v=onepage&q=Redistribution%20creates%20dependency%2C%20property%20pre-distribution%20empowers.&f=false

Stripe Market is a set of dispatch semantics for Web Applications implemented 
with Stripe as the REST-ful architecture over Monetary Models of TCP 
interactions.

## Installation

1. ``git clone https://github.com/nerdfiles/stripe-market`` to  
   ``wp-content/plugins`` such that ``plugins/stripe-market``.

2. [Register an application](https://dashboard.stripe.com/account/applications/settings) for your Stripe account.

3. Set http://domain.com/wp-admin/ or whatever your “admin” URL is for  
   the callback. We’re assuming a unitary relation between WP User and  
   Stripe User. So ``somedomain/wp-admin/profile.php``  
   might be better for a callback redirect.

3. Under your WordPress’s installation: Settings > Stripe Market, supply  
   your Stripe API Keys and Client ID where necessary.

5. Review the Shortcodes section listed below.

### Original Installation Notes

1. Upload `` to the `/wp-content/plugins/` directory
2. Activate the plugin through the "Plugins" menu in WordPress
3. Place `<?php do_action("stripe-market_hook"); ?>` in your templates

## Shortcodes

Add the following shortcodes where you see fit:

1. ``[stripe-connect]``
   For authenticating Connected Accounts using the Stripe Connect API. Page 
   schemes could follow e-mail interaction models such that permalink hashes 
   could represent schemas of Web Page Taxonomies as per Schema.org 
   specifications (e.g., https://schema.org/WPHeader).
2. ``[stripe-market product="{{PRODUCTNAME}}" amount="{{AMOUNT}}" payouts="WPUSER2:250,WPUSER2:250"]``  
   For specifying Stripe transactions to multiple Connected Accounts.
3. ``[stripe-market product="{{PRODUCTNAME}}" amount="{{AMOUNT}}" (payouts="WPUSER2:250"|pay-parent="true")]``:  
   For specifying Stripe transactions to a single Connected Account.

### Warnings

Still doesn’t have products and “projects” implemented. So the above  
shortcodes can be used in combination to facilitate such a functionality.  
“Amount” is actually quite redundant, but it’s an easy way to set  
``@readyonly`` to the Stripe.js modal window’s Amount input.

#### Payouts

Another point is that as per [Shared Customers](https://stripe.com/docs/connect/shared-customers#making-a-charge) payouts only make charges to  
payees. The application Stripe Account does not receive a charge.

### Implementation Workflow Ideas

1. Use 1 on a ‘restricted’ page (consider Role Scoper), and send to the  
   Stripe User who is also a WP User. Maybe the shortcode [stripe-connect ]  
   should have a @key that sets up a WP ``init`` listener for a certain  
   CGI-param and you share the WP page/post URL with that ?key=yaddayaddayadda.  
   Just an idea.
2. Specify the Stripe Users’ WordPress username in the ``[stripe-market]``  
   shortcode’s @payouts in a comma delimited list with the format: USER:AMOUNT.

There’s not a lot of validation before submitting payments (yet), so if you 
attempt to disperse funds that are incongruent with the sum total, weird shit 
will occur. There’s no testing for this yet, so it’s totally not ready for 
prod.

## Screenshots

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

## TODO

1. Transactions tab under Settings only represents non Stripe Connect  
   transactions.
2. Products as Projects do not exist since Projects are unfinished. Use  
   shortcodes.
3. http://www.investopedia.com/articles/01/072501.asp
4. http://www.nationalreview.com/articles/327844/fallacy-redistribution-thomas-sowell

## Changelog

### 1.0

* Initial Commit
