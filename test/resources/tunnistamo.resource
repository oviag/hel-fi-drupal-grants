*** Settings ***
Documentation       A resource file with Tunnistamo related keywords
Library             SeleniumLibrary

*** Keywords ***

Login With Tunnistamo
    Click Identification Method
    Input Demo Hetu
    Continue Identification

Choose Company Profile With Tunnistamo
    Click Company Row
    Click Choose Company And Continue

Click Identification Method
    Wait Until Element Is Visible   fakevetuma2         30s
    Click Element                   fakevetuma2
    Title Should Be                 Testitunnistaja

Input Demo Hetu
    Input Text                      hetu_input          ${TUNNISTAMO_HETU}
    Click Button                    tunnistaudu
    Title Should Be                 Suomi.fi-tunnistus

Continue Identification
    Click Button                    continue-button

Click Company Row
    Wait Until Element Is Visible   class:fi-radio-button_label     30s
    Click Element                   css:.fi-radio-button_input[value="${TUNNISTAMO_COMPANY_ID}"] ~ .fi-radio-button_label
    Element Should Be Visible       class:fi-chip--content

Click Choose Company And Continue
    Click Element                   css:form[name="authorizeForm"] button[type="submit"]
