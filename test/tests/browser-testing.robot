*** Settings ***
Documentation       Robot test for testing authentication and editing an application
Metadata            Examplemetadata          This is a simple browser test for ${baseurl}. Metadata is shown in the reports.
Library             Browser
Library             String
Suite Setup
Resource            ../resources/tunnistamo.resource
Resource           ../resources/browser-test-variables.resource
Resource           ../resources/dev-env-variables.resource

*** Test Cases ***
Fill Application Form
    Open Browser To Home Page
    Accept Cookies Banner
    Go To Application Search
    Start New Application
    Fill Step 1 Data
    [Teardown]    Close Browser

Update User Bank Account
    Open Browser To Home Page
    Accept Cookies Banner
    Do Login Process With Tunnistamo
    Open Edit Form For Company
    Add New Bank Account
    Remove New Bank Account
    [Teardown]    Close Browser

*** Keywords ***
Open Browser To Home Page
    New Browser         ${BROWSER}      headless=false
    New Page            %{TEST_BASEURL}
    Get Title           ==    Avustukset | Hel.fi Avustusasiointi

Accept Cookies Banner
    Sleep               1
    Click        .eu-cookie-compliance-default-button
    Wait For Elements State    .eu-cookie-compliance-default-button     hidden

Do Login Process With Tunnistamo
    Go To Login Page
    Go To Tunnistamo
    Login With Tunnistamo
    Logged In Home Page Should Be Open
    Go To Oma Asiointi
    ${title} =    Get Title
    IF    '${title}' == 'Valitse asiointiroolin tyyppi | Helsingin kaupunki'
      Do Company Selection
    ELSE
      Go To Omat tiedot
    END
    Logged In Company Page Should Be Open

Go To Login Page
    Click          .grants-profile--menuitem--login
    Get Title           ==    Kirjaudu sisään | Helsingin kaupunki

Go To Tunnistamo
    Click           \#edit-openid-connect-client-tunnistamo-login
    Wait Until Network Is Idle
    Get Title           ==    Suomi.fi-tunnistus

Logged In Home Page Should Be Open
    Wait Until Network Is Idle
    Get Title           ==     Avustukset | Hel.fi Avustusasiointi
    Wait For Elements State          .grants-profile--menuitem--logout .hel-icon--signout    visible

Logout In Drupal
    Click          .grants-profile--menuitem--logout .hel-icon--signout
    Wait Until Network Is Idle
    Get Title           ==    Avustukset | Hel.fi Avustusasiointi

Go To Oma Asiointi
    Click             \#block-mainnavigation a[data-drupal-link-system-path="oma-asiointi"]

Go To Omat tiedot
    Click             \#block-hdbt-subtheme-local-tasks a[data-drupal-link-system-path="oma-asiointi/hakuprofiili"]

Do Company Selection
    Click Choose Role
    Choose Company Profile With Tunnistamo

Click Choose Role
    Click             \#grants-mandate-type .form-submit
    Get Title           ==    Suomi.fi-valtuudet

Logged In Company Page Should Be Open
    Get Title           ==    Näytä oma profiili | Helsingin kaupunki
    Get Text          \#y-tunnus + div      *=    ${TUNNISTAMO_COMPANY_ID}

Go To Application Search
    Click          \#block-mainnavigation a[data-drupal-link-system-path="etsi-avustusta"]
    Get Title           ==    Application search | Helsingin kaupunki

Start New Application
    Click      .view-application-search .views-row:nth-child(1) .views-field-view-node a
    Click      \#block-servicepageauthblock .hds-button
    Get Title           ==    ${APPLICATION_TITLE} | Helsingin kaupunki
    Wait For Elements State       li[data-webform-page="1_hakijan_tiedot"].is-active  visible

Fill Step 1 Data
    Type Text          \#edit-email     ${INPUT_EMAIL}
    Type Text          \#edit-contact-person     ${INPUT_CONTACT_PERSON}
    Type Text          \#edit-contact-person-phone-number     ${INPUT_CONTACT_PERSON_PHONE_NUMBER}
    Select Options By     \#edit-community-address-community-address-select   index     ${INPUT_COMMUNITY_ADDRESS_INDEX}
    Click       \#edit-actions-wizard-next
    Wait For Elements State       \#edit-bank-account-account-number-select       focused
    Select Options By       \#edit-bank-account-account-number-select    value    ${INPUT_BANK_ACCOUNT_NUMBER}
    Click       \#edit-actions-wizard-next
    Wait For Elements State      li[data-webform-page="2_avustustiedot"].is-active   visible

Open Edit Form For Company
    Click           a[data-drupal-selector="application-edit-link"]
    Get Title           ==    Muokkaa omaa profiilia | Helsingin kaupunki

Add New Bank Account
    Click           input[data-drupal-selector="edit-bankaccounts-add-more"]
    Get Attribute    \#edit-bankaccountwrapper .draggable:last-of-type .js-form-item:first-of-type input[type="text"]      value   ==    ${Empty}
    Type Text        \#edit-bankaccountwrapper .draggable:last-of-type .js-form-item:first-of-type input[type="text"]     ${INPUT_BANK_ACCOUNT_NUMBER}
    Upload File By Selector    \#edit-bankaccountwrapper .draggable:last-of-type .js-form-type-managed-file input[type="file"]    ../resources/files/empty.pdf
    Sleep   1   # Have to manually wait for ajax upload
    Click           \#edit-submit
    Get Title           ==    Muokkaa omaa profiilia | Helsingin kaupunki
    Wait For Elements State    \#edit-bankaccountwrapper input[type="text"][readonly="readonly"][value="${INPUT_BANK_ACCOUNT_NUMBER}"]     visible

Remove New Bank Account
    ${bank_account_input} =     Get Attribute     \#edit-bankaccountwrapper input[type="text"][readonly="readonly"][value="${INPUT_BANK_ACCOUNT_NUMBER}"]     id
    ${bank_account_input} =     Get Substring     ${bank_account_input}     0     -12
    Click             a[data-drupal-selector="${bank_account_input}-deletebutton"]
    Wait For Elements State    \#drupal-modal .grants-profile-bank-account-delete-confirm-form     visible
    Click    \#drupal-modal .grants-profile-bank-account-delete-confirm-form input[type="submit"]
    Wait Until Network Is Idle
    Get Title           ==    Muokkaa omaa profiilia | Helsingin kaupunki
    Get Element Count    \#edit-bankaccountwrapper input[type="text"][readonly="readonly"][value="${INPUT_BANK_ACCOUNT_NUMBER}"]    ==      0
