*** Settings ***
Documentation       Robot test for testing application form and handling
Metadata            Examplemetadata          This is a simple browser test for ${baseurl}. Metadata is shown in the reports.
Library             Browser
Library             String
Library             DateTime
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
    Fill Step 2 Data
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

Fill Step 2 Data
    Scroll To Element     \#edit-acting-year
    ${today} = 	          Get Current Date     result_format=datetime
    ${current_year} =       Convert To String    ${today.year}
    Select Options By     \#edit-acting-year   value            ${current_year}
    Type Text             \#edit-subventions-items-0-amount     ${INPUT_SUBVENTION_AMOUNT}
    Sleep   1    # Have to manually wait for js formatter
    Get Text              \#edit-subventions-items-0-amount    ==     ${INPUT_SUBVENTION_AMOUNT_FORMATTED}
    Type Text             \#edit-compensation-purpose           ${INPUT_COMPENSATION_PURPOSE}
    Get Text              \#edit-compensation-purpose ~ .text-count-wrapper .text-count     !=    5000
    Scroll To Element     \#edit-olemme-saaneet-muita-avustuksia-ei
    Get Attribute         \#edit-olemme-saaneet-muita-avustuksia-ei    checked     ==    checked
    Scroll To Element     \#edit-compensation-boolean-false ~ label
    Get Attribute         \#edit-compensation-boolean-false     checked     !=    checked
    Get Attribute         \#edit-compensation-boolean-true    checked     !=    checked
    Click                 \#edit-compensation-boolean-true ~ label
    Wait For Elements State    \#edit-compensation-explanation    visible
    Type Text             \#edit-compensation-explanation         ${INPUT_COMPENSATION_EXPLANATION}
    Click       \#edit-actions-wizard-next
    Wait For Elements State      li[data-webform-page="3_yhteison_tiedot"].is-active   visible
