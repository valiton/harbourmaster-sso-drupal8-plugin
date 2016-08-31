Harbourmaster ("HMS") SSO plugin for Drupal 8
=============================================

What does it do?
----------------

This plugin mainly contains an authentication provider that uses an
HMS SSO cookie (containing a session token) to authenticate a user
against the HMS SSO API.

It also provides configuration pages, a login page and blocks for all 
HMS User-Manager functions.

How does the authentication work?
---------------------------------
Basically, the authentication provider will look for a valid SSO cookie
on the current request. If it finds this token, it will be checked
against the HMS SSO API endpoint. On a successful check, the endpoint
returns session data for this user (this session data may then be cached
for a certain amount of time, defaulting to 60s).

The HMS session data is then used to identify an existing Drupal user
via its associated HMS user key. If none can be found, a new Drupal user
will be created (using a random pasword), associating the HMS user key
in the process. Already existing users may be updated with properties
from the HMS session data.

At this point, Drupal's current_user will be set to the looked up user
and a Drupal session will be created for this user. The SSO token is
saved in that session.
(Authentication would work without a session, but the Drupal CSRF
protection on forms does not as it requires a session to save
the CSRF token.)

The user is now logged in. On subsequent requests, the authentication
provider will compare the current SSO token cookie with the SSO token
saved in the Drupal session. Should the SSO token not match, have
expired or the token cookie be missing, the Drupal session will be
terminated and the user will be logged out.

If no token cookie is set on the request AND the current session
(if existing) has no token associated, authentication will be handed
to the standard Drupal "Cookie" authentication provider.

How does the User-Manager work?
-------------------------------
The user manager provides widgets for all HMS SSO related functions like
- login
- registration
- password reset

It is included into Drupal by rendering a container with a specific id
and including some Javascript from the User-Manager server.

When you login via the User-Manager, it will set the cookie containing
the SSO token on its configured domain, usually ".domain.tld". This
cookie will then always be sent to any subdomain of "domain.tld" and
allow the authentication provider to work.

Logging out of the user manager destroys that cookie and subsequently
the Drupal session.

Prerequisites:
--------------
- installed and properly configured HMS User-Manager on the same domain
  as the Drupal installation or any-level subdomain thereof
- installed and properly configured HMS SSO server
  (the actual "Harbourmaster")

Installation:
-------------
- enable the module
- configure at least the HMS SSO API und HMS User-Manager server urls
  on the configuration page
- rebuild the container (see known bugs)
- go to /harbourmaster/login or configure the "HMS Status" block which will
  provide links for login and logout

Available routes:
-----------------
- /harbourmaster/login: renders most of the HMS User-Manager widgets
- /harbourmaster/logout: redirects to the logout user manager url which in turn
  redirects back to the Drupal front page

Available blocks:
-----------------
- Status: Debugging block that shows the current authentication provider
  and matching login/logout links
- One block for each of the user manager widgets

Other:
------
- includes a caching policy that denies caching when a valid
  (by pattern) token is set on the request
- includes an event subscriber that can be triggered to delete the
  SSO cookie on the response (this will happen when the SSO session
  associated with that token was deemed invalid) - subsequent requests
  can then be cached

Known Bugs and ToDos:
---------------------
- conflict resolution for unique username is very basic
- Drupal user functions (password reset, profile edit, user canceling)
  still need to be blocked for HMS SSO authenticated users.
- user image update is currently very simple
