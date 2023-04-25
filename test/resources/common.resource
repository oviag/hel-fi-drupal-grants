*** Settings ***
Library             Browser
Library             String
Resource            ./browser-test-variables.resource
Resource            ./dev-env-variables.resource
Resource            ./tunnistamo.resource

*** Keywords ***
Open Browser To Home Page
    Set Browser Timeout   60s
    New Browser           ${BROWSER}
    # Needed for local environment testing
    New Context           ignoreHTTPSErrors=True
    New Page              %{TEST_BASEURL}
    Get Title             ==    Avustukset | ${SITE_NAME_ALT}

Accept Cookies Banner
    Sleep               1
    Click        .eu-cookie-compliance-default-button
    Wait For Elements State    .eu-cookie-compliance-default-button     hidden

Do Company Login Process With Tunnistamo
    Go To Login Page
    Go To Tunnistamo
    Login With Tunnistamo
    Logged In Role Selector Should Be Open
    Go To Oma Asiointi
    ${title} =    Get Title
    IF    '${title}' == 'Valitse asiointiroolin tyyppi | ${SITE_NAME}'
      Do Company Selection
    ELSE
      Go To Omat tiedot
    END
    Logged In Company Page Should Be Open

Do Unregistered Community Login Process With Tunnistamo
    Go To Login Page
    Go To Tunnistamo
    Login With Tunnistamo
    Logged In Role Selector Should Be Open
    Go To Oma Asiointi
    ${title} =    Get Title
    IF    '${title}' == 'Valitse asiointiroolin tyyppi | ${SITE_NAME}'
      Do Unregistered Community Selection
    ELSE
      Go To Omat tiedot
    END
    Logged In Unregistered Community Page Should Be Open

Do Private Person Login Process With Tunnistamo
    Go To Login Page
    Go To Tunnistamo
    Login With Tunnistamo
    Logged In Role Selector Should Be Open
    Go To Oma Asiointi
    ${title} =    Get Title
    IF    '${title}' == 'Valitse asiointiroolin tyyppi | ${SITE_NAME}'
      Do Private Person Selection
    ELSE
      Go To Omat tiedot
    END
    Logged In Private Person Page Should Be Open

Go To Login Page
    Click          .profile__login-link
    Get Title           ==    Kirjaudu sisään | ${SITE_NAME}

Go To Tunnistamo
    Click           \#edit-openid-connect-client-tunnistamo-login
    Wait Until Network Is Idle
    Get Title           ==    Suomi.fi-tunnistus

Logged In Role Selector Should Be Open
    Wait Until Network Is Idle
    Get Title           ==     Valitse asiointiroolin tyyppi | ${SITE_NAME}
    Wait For Elements State          .nav-toggle--profile    visible

Logout In Drupal
    Click          .nav-toggle--profile
    Click          .profile__logout-link
    Wait Until Network Is Idle
    Get Title           ==    Avustukset | ${SITE_NAME_ALT}

Go To Oma Asiointi
    Click             \#block-mainnavigation a[data-drupal-link-system-path="oma-asiointi"]

Go To Omat tiedot
    Click             \#block-hdbt-subtheme-local-tasks a[data-drupal-link-system-path="oma-asiointi/hakuprofiili"]

Do Company Selection
    Choose Company Role
    Choose Company Profile With Tunnistamo

Do Unregistered Community Selection
    Select Options By    [data-drupal-selector="edit-unregistered-community-selection"]    index    1
    Click             \#edit-unregistered-community .form-submit

Do Private Person Selection
    Click             \#edit-private-person .form-submit

Choose Company Role
    Click             \#edit-registered-community .form-submit
    Wait For Condition    Title           ==    Suomi.fi-valtuudet

Logged In Company Page Should Be Open
    Get Title           ==    Näytä oma profiili | ${SITE_NAME}
    Get Text          .grants-profile-company-name      *=    ${TUNNISTAMO_COMPANY_NAME}

Logged In Unregistered Community Page Should Be Open
    Get Title           ==    Näytä oma profiili | ${SITE_NAME}

Logged In Private Person Page Should Be Open
    Get Title           ==    Näytä oma profiili | ${SITE_NAME}
    Get Text          h1      *=    ${TUNNISTAMO_PERSON_NAME}
