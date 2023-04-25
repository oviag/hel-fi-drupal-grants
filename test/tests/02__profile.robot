*** Settings ***
Documentation       Robot test for testing user profile editing
Metadata            Examplemetadata          This is a simple browser test for ${baseurl}. Metadata is shown in the reports.
Library             Browser
Library             String
Suite Setup
Resource            ../resources/common.resource
Resource            ../resources/tunnistamo.resource

*** Test Cases ***

Update Company Bank Account
    Open Browser To Home Page
    Accept Cookies Banner
    Do Company Login Process With Tunnistamo
    Open Edit Form
    Add New Bank Account
    Open Edit Form
    Remove New Bank Account
    [Teardown]    Close Browser

Update Unregistered Company Bank Account
    Open Browser To Home Page
    Accept Cookies Banner
    Do Unregistered Community Login Process With Tunnistamo
    Open Edit Form
    Add New Bank Account For Unregistered Community
    Open Edit Form
    Remove New Bank Account
    [Teardown]    Close Browser

Update Private Person Bank Account
    Open Browser To Home Page
    Accept Cookies Banner
    Do Private Person Login Process With Tunnistamo
    Open Edit Form
    Add New Bank Account
    Open Edit Form
    Remove New Bank Account
    [Teardown]    Close Browser

*** Keywords ***

Open Edit Form
    Click           a[data-drupal-selector="profile-edit-link"]
    Wait Until Network Is Idle
    Get Title           ==    Muokkaa omaa profiilia | ${SITE_NAME}

Add New Bank Account
    Click           button[data-drupal-selector="edit-bankaccountwrapper-actions-add-bankaccount"]
    Sleep   2   # Have to manually wait for ajax load
    Scroll To Element   [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:first-of-type input[type="text"]
    Get Attribute    [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:first-of-type input[type="text"]      value   ==    ${Empty}
    Type Text        [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:first-of-type input[type="text"]     ${INPUT_TEMP_BANK_ACCOUNT_NUMBER}
    Upload File By Selector    [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-type-managed-file input[type="file"]    ${CURDIR}/empty.pdf
    Sleep   3   # Have to manually wait for ajax upload
    Click           \#edit-actions-submit
    Get Title           ==    Näytä oma profiili | ${SITE_NAME}
    Get Text    .grants-profile--extrainfo    *=    ${INPUT_TEMP_BANK_ACCOUNT_NUMBER}

Add New Bank Account For Unregistered Community
    Click           button[data-drupal-selector="edit-bankaccountwrapper-actions-add-bankaccount"]
    Sleep   2   # Have to manually wait for ajax load
    Scroll To Element   [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:first-of-type input[type="text"]
    Get Attribute    [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:first-of-type input[type="text"]      value   ==    ${Empty}
    Type Text        [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:first-of-type input[type="text"]     ${INPUT_TEMP_BANK_ACCOUNT_NUMBER}
    Type Text        [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:nth-of-type(2) input[type="text"]     Esa Esimerkki
    Type Text        [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-item:nth-of-type(3) input[type="text"]     010101-001R
    Upload File By Selector    [data-drupal-selector="edit-bankaccountwrapper"] fieldset:last-of-type .js-form-type-managed-file input[type="file"]    ${CURDIR}/empty.pdf
    Sleep   3   # Have to manually wait for ajax upload
    Click           \#edit-actions-submit
    Get Title           ==    Näytä oma profiili | ${SITE_NAME}
    Get Text    .grants-profile--extrainfo    *=    ${INPUT_TEMP_BANK_ACCOUNT_NUMBER}

Remove New Bank Account
    ${bank_account_input} =     Get Attribute     [data-drupal-selector="edit-bankaccountwrapper"] input[type="text"][readonly="readonly"][value="${INPUT_TEMP_BANK_ACCOUNT_NUMBER}"]     id
    ${bank_account_input} =     Get Substring     ${bank_account_input}     0     -12
    Click             button[data-drupal-selector="${bank_account_input}-deletebutton"]
    Sleep   2   # Have to manually wait for ajax load
    Wait Until Network Is Idle
    Click           \#edit-actions-submit
    Get Title           ==    Näytä oma profiili | ${SITE_NAME}
    Get Text    .grants-profile--extrainfo    not contains    ${INPUT_TEMP_BANK_ACCOUNT_NUMBER}
