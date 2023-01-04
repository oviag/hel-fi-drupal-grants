*** Settings ***
Documentation       Robot test for testing authentication and editing an application
Metadata            Examplemetadata          This is a simple browser test for ${baseurl}. Metadata is shown in the reports.
Library             Browser
Suite Setup
Resource            ../resources/tunnistamo.resource
Resource           ../resources/browser-test-variables.resource
Resource           ../resources/dev-env-variables.resource

*** Test Cases ***
Fill Application Form
    Open Browser To Home Page
    Accept Cookies Banner
    Do Login Process With Tunnistamo
    Go To Application Search
    Start New Application
    Fill Step 1 Data
    [Teardown]    Close Browser

*** Keywords ***
Open Browser To Home Page
    New Browser         ${BROWSER}
    New Page            ${TEST_BASEURL}
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
    Click Choose Role
    Choose Company Profile With Tunnistamo
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

Go To Oma Asiointi
    Click             \#block-mainnavigation a[data-drupal-link-system-path="oma-asiointi"]
    Get Title           ==    Valitse asiointiroolin tyyppi | Helsingin kaupunki

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
    Click          .views-field-field-webform a
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
