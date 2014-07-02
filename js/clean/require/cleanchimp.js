define(['util', 'prototype-cookies', 'query-string'], function(util, cookies, queryString) {
    return {
        run: function() {
            if (queryString.toObject().mc_cid) {
                util.log("Found mc_cid: ", queryString.toObject().mc_cid);
            }
        }
    }
});