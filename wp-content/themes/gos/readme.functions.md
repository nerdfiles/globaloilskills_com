/*
       _       _           _       _ _     _         __  __ _
      | |     | |         | |     (_) |   | |       / _|/ _(_)
  __ _| | ___ | |__   __ _| | ___  _| |___| |_ __ _| |_| |_ _ _ __   __ _
 / _` | |/ _ \| '_ \ / _` | |/ _ \| | / __| __/ _` |  _|  _| | '_ \ / _` |
| (_| | | (_) | |_) | (_| | | (_) | | \__ \ || (_| | | | | | | | | | (_| |
 \__, |_|\___/|_.__/ \__,_|_|\___/|_|_|___/\__\__,_|_| |_| |_|_| |_|\__, |
  __/ |                                                              __/ |
 |___/                                                              |___/
                       _
                      (_)
   ___  ___ _ ____   ___  ___ ___  ___
  / __|/ _ \ '__\ \ / / |/ __/ _ \/ __|
 _\__ \  __/ |   \ V /| | (_|  __/\__ \
(_)___/\___|_|    \_/ |_|\___\___||___/


1. Job (Custom Post Type)
1.1. Jobs
1.1.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    Active (Icon: http://fortawesome.github.io/Font-Awesome/icon/bolt/)
    Unread (Icon: http://fortawesome.github.io/Font-Awesome/icon/circle/)
    Awaiting Approval ((Icon: http://fortawesome.github.io/Font-Awesome/icon/circle-o/))
    Inactive (Icon: http://fortawesome.github.io/Font-Awesome/icon/circle-thin/)
    Expiring Soon (Icon: http://fortawesome.github.io/Font-Awesome/icon/clock-o/)
    Expired (Icon: http://fortawesome.github.io/Font-Awesome/icon/dot-circle-o/)

1.1.2. FK
    Position Title
    Company Name
    Category
    Job Type
    Price
    Expires
    Applications
    Status

1.2. Corollary: Reactive Job Postings
     Correlate discrete numbers of "drag" interface to WP custom taxonomy
     posts "carousel", thus documents are interactive such that other key
     data points are cycled in accordance with WP Query constraints.)
     See http://worrydream.com/Tangle/guide.html.

1.3. Corollary: Leaflet Maps (Custom Post Type)
     Job (Postings) and Employers have GIS data.
     See http://leafletjs.com/.

1.2. Applications (Custom Post Type)

1.2.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    New (Icon: http://fortawesome.github.io/Font-Awesome/icon/bullseye/)
    Accepted (Icon: http://fortawesome.github.io/Font-Awesome/icon/check-circle/)
    Rejected (Icon: http://fortawesome.github.io/Font-Awesome/icon/ban/)

1.2.2. FK
    Applicant Name
    Applicant Email
    Job
    Files
    Posted
    Status

1.3. Employers (Custom Post Type)

1.3.1. FK
    Id
    Company Name
    Company Location
    Representative
    Jobs Posted
    Status

1.4. Candidates (Custom Post Type)

1.4.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    Active (Icon: http://fortawesome.github.io/Font-Awesome/icon/bolt/)
    Inactive (Icon: http://fortawesome.github.io/Font-Awesome/icon/circle-thin/)

1.4.2. FK
    Name
    Headline
    E-mail
    Phone
    Updated (By Owner)
    Status

1.5. Payments (Stripe, Paypal as Custom Post Types)

1.5.1. FK
    ID
    Payment For
    Created At
    User
    External Id
    To Pay
    Paid
    Message

1.6. Memberships (MemberMouse)

1.6.1.
    Membership ID
    Package
    User
    Started
    Expires
    Status

1.7. Notifications (E-mail as Custom Post Type)

1.7.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    Daily (Icon: http://fortawesome.github.io/Font-Awesome/icon/calendar-o/)
    Weekly (Icon: http://fortawesome.github.io/Font-Awesome/icon/calendar/)

1.8.2.
    Email
    Created At
    Last Run
    Frequency
    Params

2. Profile (User)

3. Settings

3.1. Configuration

3.1.1. Common Settings

3.1.2. Job Board Options

3.1.3. Resumés Options

3.1.4. SEO & Titles Options

3.1.5. Integrations

  Depends on scope of integration. There's also automation with IFTTT and
  Zapier. Don't get me started on #bitcoin.

    3.1.5.1.     Facebook
    3.1.5.2.     Twitter
    3.1.5.3.     LinkedIn
    3.1.5.4.     Google APIs
    3.1.5.5.     reCAPTCHA
    3.1.5.6.     Careerbuilder.com
    3.1.5.7.     Indeed.com
    3.1.5.8.     Payment Methods
    3.1.5.9.     PayPal
    3.1.5.10.    Stripe (Stripe Market, based on WP-Stripe, but implements
                 Stripe Connect (multi bank account WordPress sites))
    3.1.5.11.    Health Check
    3.1.5.12.    Cache (WP Super Cache)
    3.1.5.13.    Aggregators and RSS Feeds

3.2. Pricing
    Single Job Posting
    Single Resumé Access
    Employer Membership Packages

3.3. Custom Fields (Contact Form 7, a WordPress plugin)
    Add/Edit Job Form
    Apply Online Form
    Advanced Search Form
    Company Form
    Resumés Forms
    My Resumé Form
    Advanced Search Form

3.4. Promotions

3.4.1. FK

    Id
    Title
    Code
    Discount
    Expires At
    Usage
    Is Active (Icon: http://fortawesome.github.io/Font-Awesome/icon/bolt/)

3.5. Categories

3.5.1. FK

    Id
    Title
    Slug
    Total Jobs
    Total Resumés

3.6. Job Types

3.6.1. FK

    Id
    Title
    Slug
    Total Jobs
    Total Resumés

3.7. E-mail Templates

    admins (Icon: http://fortawesome.github.io/Font-Awesome/icon/child/)
    employers (Icon: http://fortawesome.github.io/Font-Awesome/icon/users/)
    candidates (Icon: http://fortawesome.github.io/Font-Awesome/icon/user/)
    other

3.8. Import (CSV)

    schedule
    csv
    json (from WP JSON API / REST :: https://github.com/cliffwoo/rest-api-cheat-sheet/blob/master/REST-API-Cheat-Sheet.md)
    schema.org microdata (arbitrary HTML)

4. Visualulz

4.1. D3 Recursive Interactive Treemap Views of Employer Differentials and

     Narratological Datatrends
     WP Query Contextualizations expressed through “drill-down” treepmaps.
     See https://github.com/mbostock/d3/wiki/Treemap-Layout.

4.2. Candidate Historical Trends over interactive time series graphs

     See http://c3js.org.

5.1. Analytics

    Mixpanel

    GoSquared

    Google Webmaster Tools

6. Tech Stack

   WANGS
       WordPress (Custom Plugin for 1-6; Hide WP Posts, Keep WP Pages, Widgets, Menus)

       AngularJS
       I've already implemented AngularJS: one controller, two services, two
       directives. WordPress Roles are being used to authorize data services,
       and we use AngularJS's internal $http service with Promises to size and
       shape payloads to clients.

       Node

       Grunt ::
           built to
               Asynchronous Module Definition for the Web via RequireJS
               package-based Cordova application, deploy iPhone first

       SASS
           Syntactically Awesome Stylesheets with Organic
           Block-Element-Modifier presentation layer
           See http://krasimir.github.io/organic-css/.

7. Testing

See https://github.com/nerdfiles/skreen.ls/blob/develop/shoot.site.coffee.

"Don't commit a line of code unless and until you have one failing test for its logical feature." But the (kind of test)[1-4] is determined by the [feature scope] which is composed of logical units or [modules of code].

1. Test-driven Development: Restricted Unit Testing (around API Requests and Responses)
1.1. frex: /api/v1/data/endpoint, /api/v2/search?query={{term}}

2. Behavior-driven Development: Behavior Testing (around Specific Interactions)

2.1. frex: Button clicks, Swipes, etc.

3. Integration Testing (around Modular Units of Programmatic Code)
3.1. frex: Search Bar, Navigation, etc.

4. Feature/Subbranch Development: End-to-End Testing (around Use Case Flows)
4.1. frex: Pages for Login Flow, Sidebars for Upload, etc.

5. UX Development: Automated Screen Testing (around Design Goals expressed as Modular Units of Presentation Code)
5.1. frex: Design Borders, Sprites, Icons, Typesetting, etc.

6. Generational Development: Regression Testing based on Client-side Error Analytics (emissions of errors from clients to a "regression API" that listens for generated errors)
6.1. frex: https://airbrake.io/blog/notifier/state-client-side-javascript-error-reporting combined with Screen Tests for Spotting errors

7. Validation:
7.1. Semantics — http://linter.structured-data.org
7.2. Markup and CSS — http://validator.w3.org/unicorn/

8. Code Linting: JSHint for Programmatic Front End syntax checking, use Preprocessors where possible to oversee correct/proper syntax production

9. Device/Browser/Responsive Testing:
9.1 Use third-party services like Sauce Labs or Browser Stack, who offer automation especially with tools like AngularJS's Protractor which can be used [outside of the Angular context]
9.2 Automated: Use Vagrant Cookbooks to build recipes for certain configurations
9.3 Manual Testing: Virtual Environments for various Use Case flows that are of interest to Users, Customers, and Stakeholders

Software Development Life Cycle configuration files should follow:

1. Unit (local, development)
2. Behavior (local, development)
3. Integration (development, staging)
4. Feature (staging, QA)
5. Screen (QA, production)

Environments:

1. Local
2. Development
3. Staging
4. QA
5. Production
*/
