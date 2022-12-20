*** Settings ***
Documentation       Robot test for testing authentication and editing an application
Metadata            Examplemetadata          This is a simple browser test for ${baseurl}. Metadata is shown in the reports.
Library             SeleniumLibrary
Suite Setup
Resource            ../resources/tunnistamo.resource
Variables           ../variables/browser-test-variables.yaml
Variables           ../variables/dev-env.yaml

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
    Open Browser        ${TEST_BASEURL}    ${BROWSER}
    Title Should Be     Avustukset | Hel.fi Avustusasiointi

Accept Cookies Banner
    Sleep     1
    Click Button        class:eu-cookie-compliance-default-button
    Wait Until Element Is Not Visible    class:eu-cookie-compliance-default-button

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
    Click Link          class:grants-profile--menuitem--login
    Title Should Be     Kirjaudu sisään | Helsingin kaupunki

Go To Tunnistamo
    Click Element       edit-openid-connect-client-tunnistamo-login
    Title Should Be     Suomi.fi-tunnistus

Logged In Home Page Should Be Open
    Title Should Be                 Avustukset | Hel.fi Avustusasiointi
    Element Should Be Visible       class:grants-profile--menuitem--logout

Go To Oma Asiointi
    Click Link          css:#block-mainnavigation a[data-drupal-link-system-path="oma-asiointi"]
    Title Should Be     Valitse asiointiroolin tyyppi | Helsingin kaupunki

Click Choose Role
    Click Element       css:#grants-mandate-type .form-submit
    Title Should Be     Suomi.fi-valtuudet

Logged In Company Page Should Be Open
    Title Should Be                 Näytä oma profiili | Helsingin kaupunki
    Element Should Contain          css:#y-tunnus + div     ${TUNNISTAMO_COMPANY_ID}

Go To Application Search
    Click Link          css:#block-mainnavigation a[data-drupal-link-system-path="etsi-avustusta"]
    Title Should Be     Application search | Helsingin kaupunki

Start New Application
    Click Link          css:.views-field-field-webform a
    Title Should Be     ${APPLICATION_TITLE} | Helsingin kaupunki
    Element Should Be Visible       css:li[data-webform-page="1_hakijan_tiedot"].is-active

Fill Step 1 Data
    Input Text          edit-email     ${INPUT_EMAIL}
    Input Text          edit-contact-person     ${INPUT_CONTACT_PERSON}
    Input Text          edit-contact-person-phone-number     ${INPUT_CONTACT_PERSON_PHONE_NUMBER}
    Select From List By Value       edit-community-address-community-address-select     ${INPUT_COMMUNITY_ADDRESS_INDEX}
    Click Element       edit-actions-wizard-next
    Element Should Be Focused       edit-bank-account-account-number-select
    Select From List By Value       edit-bank-account-account-number-select    ${INPUT_BANK_ACCOUNT_NUMBER}
    Click Element       edit-actions-wizard-next
    Element Should Be Visible       css:li[data-webform-page="2_avustustiedot"].is-active
