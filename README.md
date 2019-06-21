# CiviCRM Extension: nz.co.fuzion.pageredirect

*Page Redirect option for disabled or not currently active because start or end date has either not come or has past contribution pages.*

By default CiviCRM gives a rather unpleasing screen if someone tries to visit a contribution page that has been disabled or is not currently active for some reason. This extension allows you to set a default contribution page as a redirect for all traffic to disabled pages.

## How to use

1. Install as usual for CiviCRM extensions.
2. Visit **civicrm/admin/setting/customredirect** and enter the ID of the contribution page you'd like to redirect to.

At this point anybody who visits a contribution page that is no longer enabled will be sent to the live page you specified instead.
