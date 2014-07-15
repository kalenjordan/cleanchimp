define(['util', 'prototype-cookies', 'query-string'], function(util, cookies, queryString) {
    return {
        run: function() {
            if (queryString.toObject().mc_cid) {
                cookies.set('cleanchimp_campaign_id', queryString.toObject().mc_cid, 30);
                util.logServer("Set mc_cid on cookie: " + queryString.toObject().mc_cid, 'cleanchimp');
            }

            if (queryString.toObject().mc_eid) {
                cookies.set('cleanchimp_email_id', queryString.toObject().mc_eid, 30);
                util.logServer("Set mc_eid on cookie: " + queryString.toObject().mc_eid, 'cleanchimp');
            }
        }
    }
});