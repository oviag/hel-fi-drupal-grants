*** Settings ***
Documentation       Robot test for testing application form and handling
Metadata            Examplemetadata          This is a simple browser test for ${baseurl}. Metadata is shown in the reports.
Library             Browser
Library             String
Suite Setup
Resource            ../resources/common.resource
Resource            ../resources/tunnistamo.resource
Resource            ../resources/browser-test-variables.resource
Resource            ../resources/dev-env-variables.resource

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

Go To Application Search
    Click          \#block-mainnavigation a[data-drupal-link-system-path="etsi-avustusta"]
    Get Title           ==    Application search | ${SITE_NAME}

Start New Application
    Scroll To Element    .view-application-search .views-row:nth-child(1) .views-field-view-node a
    Click      .view-application-search .views-row:nth-child(1) .views-field-view-node a
    Scroll To Element    \#block-servicepageauthblock .hds-button
    Click      \#block-servicepageauthblock .hds-button
    Get Title           ==    ${APPLICATION_TITLE} | ${SITE_NAME}
    Wait For Elements State       li[data-webform-page="1_hakijan_tiedot"].is-active  visible

Fill Step 1 Data
    Scroll To Element     \#edit-email
    Type Text          \#edit-email     ${INPUT_EMAIL}
    Type Text          \#edit-contact-person     ${INPUT_CONTACT_PERSON}
    Type Text          \#edit-contact-person-phone-number     ${INPUT_CONTACT_PERSON_PHONE_NUMBER}
    Select Options By     \#edit-community-address-community-address-select   index     ${INPUT_COMMUNITY_ADDRESS_INDEX}
    Click       \#edit-actions-wizard-next
    Wait For Elements State       \#edit-bank-account-account-number-select       focused
    Select Options By       \#edit-bank-account-account-number-select    value    ${INPUT_BANK_ACCOUNT_NUMBER}
    Click       \#edit-actions-wizard-next
    Wait For Elements State      li[data-webform-page="2_avustustiedot"].is-active   visible
