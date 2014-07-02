# Ecommerce 360 for Magento

This module sends orders over the Mailchimp API version 2.0.  

## About
It batches the orders that it handles during each cron run, so it should be able to handle a large number of orders.  The ecommerce 360 query string parameters are handled javascript-side, so it's compatible with full page caching.

At the moment it's not doing anything Mailchimp-related other than Ecommerce 360, but it may make sense to tack some things on.  

### Why
There is a very populare and feature-full extension called [MageMonkey](http://www.magentocommerce.com/magento-connect/ebizmarts-magemonkey-official-mailchimp-and-mandrill-integration.html), which also has support for Mailchimp's Ecommerce 360.  The reason I'm building this module is that I ran into some issues around handling [larger order volume](https://github.com/ebizmarts/magemonkey/issues/10) with MageMonkey.  Also, from some brief testing and code review, it appears that it's handling of the Ecommerce 360 query string parameters isn't compatible with Varnish / full page cache, b/c it handles them in PHP as opposed to being client-side.

I probably could have submitted PRs for these things, but I think it would have taken longer to do that than building this.   

## Installation

1. You can install using modman:

        modman clone git@github.com:kalenjordan/clean-mailchimp.git
        
2. To configure it, drop your API key into System > Config > Newsletter > Mailchimp Settings

3. The javascript setup is very non-trivial right now.

My apologies - this is mostly because I'm in
the process of understanding how best to use require.js.  There are a couple of javascript dependencies
that cleanchimp.js has has - require.js, domReady.js, as well as a few utility require.js modules that
I've built - util, prototype cookies, and query-string.  I really wanted to avoid baking all of these
into the module itself, but I haven't quite worked out how to make the installation process as easy
as it should be.  Perhaps a separate module that packages up these guys.

If anyone out there is interested in using this and has some ideas on how to deal with the require.js
module dependency management, let me know - I'd love to figure it out.

## To Do
1. Javascript / cookie stuff to handle campaign ID (mc_cid) and email ID (mc_eid).

    
