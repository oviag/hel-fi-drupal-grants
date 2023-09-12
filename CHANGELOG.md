# CHANGELOG


## 2023.18.1
- 649ddff8 AU-1554: Fix missing files from saved form

## 2023.18
- 53db3c8f fix: AU-1437, AU-1532, AU-1533, AU-1534, AU-1535, AU-1536, AU-1537, AU-1538: Various fixes (#684)

## 2023.17
- 152450f9 fix: AU-1497: add the rest of the nuorproj translations (#682)
- 5414b892 feat: AU-1445, AU-1446, 1475: Header hierarchy and AJAX file issues with required notification (#658)
- e6118849 fix: AU-1487: appendix to attachment (#681)
- dcf9495f fix: AU-1496: Move notification outside of checkbox in preview page (#680)
- d79655d0 feat: AU-1408: Webform configuration override (#647)
- a91de320 fix: AU-1423: Copy address and budget fields properly for new applications (#651)
- c61b14ed fix: AU-1137: fix multiples for vastuuhenkilö (#667)
- 9c2d5583 fix: AU-1472: Correct format for date on application form (#668)
- bb65a329 fix: AU-1477: Fixed an issue with webfrom translation importing. (#665)
- f7801223 feat: AU-1293: Liikunta, toiminta ja tilankäyttöavustus // ID 60 (#575)
- 1e64ff07 fix: AU-1482, AU-1483: fix nuoriso projekti fields (#673)
- 585f8df2 fix: AU-1256: unregistered community always has required Community Officials and other roles don't (#675)
- 78e28f46 fix: AU-1457: Hidden fields do not need titles if they are not to be displayed on preview (#677)
- 76a935c9 fix: AU-1474 change hidden avustukset-summa to a hidden field instead of display:none (#678)
- 7b0df10c fix: AU-1288: fix preview notification -text and add new notification (#669)
- b882cb2c fix: AU-1432: translation fixes (#674)
- a0d40aed fix: AU-1475: fix the AJAX attachment thing with form_alter before the hierarchy is changed (#676)
- 8d6b4dd5 fix: AU-1391: fix liikunta tapahtuma buttons (#671)
- ffb3c7a1 fix: AU-1490: Disable custom health_check module (#672)
- 3d2825d7 feat: AU-1478: Automate form config imports safely. (#664)

## 2023.16
- 1c4344bf feat: AU-1325: Liikunta, laitosavustus // ID 57 (#592)
- f4c0e281 Restore page required variable
- a7ad224b fix: AU-1247, AU-1430, AU-1437: Kulttuuri taiteen perusopetus fixes (#661)
- 235fc37d fix: AU-1427: change notification text (#660)
- 4c0329cc fix: AU-1424: add target blank (#659)
- deb59276 Remove citySection from form config since it is not implemented in avus2.
- 729ea630 fix: AU-1458: Fix KUVA Perus realized premises mapping (#652)
- fbf4fd8e fix: AU-1456: Updates to the ForceMenuLinkService.php service. (#656)
- 6d9eb5b3 fix: AU-1429, AU-1431, AU-1432, AU-1433, AU-1434: Taiteen perusopetuksen avustukset testfixes (#650)

## 2023.15.3
- 8c692ad9 fix: AU-1444: Handle amount conversion in other compensation composite (#649)
- 829d59f7 fix: AU-1442 remove frontpageblock from conf (#646)
- 7658d94e fix: AU-1440: Summation field change event (#643)
- ae4f0fb0 fix: AU-1443: Update packages to support php8.2 without going to platta v3 (#644)
- 08d5a4aa fix: AU-1277: Handle translations for applicant details (#642)

## 2023.15.2
- 654873d2 AU-1403: Add defaults to suppress warnings (#640)
- 8ec03882 feat: AU-1412: Custom Webform config ignore and config import by application type ID (#636)
- 5e6e87d7 AU-XX: fix malformed translation string (#639)
- d08e2ad8 fix: AU-1438: Make so that continuous applications are not expired in list (#638)
- 50eb0521 feat: AU-966: Nuoriso Projekti code 62 // IBM (#417)
- 832b8025 fix: AU-1415: Install & configure Varnish modules bypassing helfi_proxy module. (#634)
- 67a88a3d fix: AU-1428: Fix TPR integration by updating to Guzzle7 (#633)
- 5452ab79 fix: AU-1416: Edit premise (#631)
- 66801cfd fix: AU-1422: add translations to applicant (#632)
- 6bf686dc fix: AU-1380: add tooltip to liikunnan tapahtuma (#630)
- 66d1d28c fix: AU-1253: fix translations (#628)
- 8c9feb2d fix: AU-1420: Swap field mappings (#627)
- 21a65f0f fix: AU-1246: Add nuorisolomaleiri translations (#625)
- 1a3d359f fix: AU-1394: font change for labels and legends (#624)
- ce88163e fix: AU-1379: remove unused attachment fields (#622)

## 2023.15.1
- e9e2a7c4 fix: Disable cache (#621)

## 2023.15
- d8fa7432 fix: AU-1377: Move description to before in Liikunta yleis (#620)
- bed6b6a9 fix: AU-1370: Use hds notification template instead of custom core in profile pages (#618)
- 894a9544 feat: AU-1414: create module for automated Preview -link (#617)
- d479c29f fix: AU-1401: remove print button and fix print styles for p… (#616)
- c34d4791 config: Update configuration (#550)
- d7d73280 fix: AU-1406: Allow submitted application to be edited when application period is closed (#614)
- ff2a1a80 fix: AU-1411: Use correct translationinterface in print page (#613)
- 1e124e32 fix: AU-1375: fix title for Nuorisotoiminta, toimnta- ja palkkausavustus ennakkohakemus (#612)
- 08fdd8a8 fix: AU-1196: fix: AU-1196: Typofix (#611)
- 44e83350 fix: AU-1368, AU-1381, AU-1386, AU-1391, AU-1402: translation fixes (#610)
- 1880be57 fix: AU-1404: Fix avustukset_summa prepopulation / calculation and some mapping fixes (#609)

## 2023.14
- 0bf0234e feat: AU-1233: Save application to ATV before sending it to integration for saving. (#595)
- d1a2ee2a fix: AU-1343: Translate attachment field labels for print view (#607)
- 90a3c368 fix: AU-1385: Change liikunta tapahtuma acting years (#606)
- 19139d33 fix: AU-1383: Remove duplicate error message from the first page of application (#605)
- caa9864a fix; AU-1363: Change disabled to readonly when doing automatic changes with compensation question.
- 9135a836 AU-xxx: Maybe fix automatic tests(?)
- 06422899 fix: AU-1286: re-style other attachment field (#599)
- c0816ed3 fix: AU-XX: Initialize applicant info when document is created. (#600)
- 0415d1ba fix: AU-1372: Fix subventions values multiplying 100 fold. (#598)
- a48bd441 fix: AU-1357: fix toiminnasta vastaava henkilö (#597)
- 4e889d47 fix: AU-1371: Add text (#596)
- 8b7438d1 fix: AU-1358: Fix community officials element visibility. (#594)
- eae0d336 fix: AU-1298: webform title translations (#590)
- 4b2069c4 Fix front page application search (#591)
- 5fec825c fix: AU-1369: Fix some toimintaryhma issues (#589)
- 255528bb fix: AU-1246: fix NUORLOMALEIR translation (#545)
- 3f0304c3 fix: AU-1248: liikunta translations (#553)
- 5f154b0f feat: AU-1289: add print button (#587)
- 9eebcea8 fix: AU-1364, AU-1365: fix nuoriso palkkaennakko (#588)
- 210c91ae fix: AU-1356: Add check for officials role for printing (#586)
- 95a92858 fix: Remove auto deploy fromo correct env.
- d03b0f10 fix: AU-1361, AU-1362: fix liikunnan yleisavustus (#584)
- 0638605b fix: AU-1359: Add translations (#585)
- 0b37b940 feat: AU-1260: Force service nodes to the main menu. (#579)
- fd873048 Remove auto deployment for TEST env
- 90e81f9e feat: AU-1272: Webform translations import (#583)
- e5958db2 fix: AU-1287 fix preview print (#581)
- 7678afb2 AU-1331: fix perusopetus (#582)
- 39bb41e9 fix: AU-1333, AU-1334, AU-1335, AU-1336, AU-1337: fixes to leiriavustus (#580)
- e4e325f0 feat: AU-960: Liikunta tapahtuma 59 // IBM (#416)
- 321da9e5 fix: AU-1300, AU-1301, AU-1302: fixes to Kasko IPLISA (#577)
- 495fe309 feat: AU-1222, AU-1227, AU-1243: Unregistered Community changes.
- 97c0ff0d Fix print link
- 5180721f fix: AU-1291: fix premise (#576)
- efcb0bd1 fix: AU-1281: Change Unregistered community to unregistered community or group (#574)
- d1aae8eb fix: AU-1290: Fix Premises States (#573)
- 68080122 feat: AU-183: Place of Opeartion Webform composite component (#568)
- f76f6803 fix: AU-1297: Add sv translations to sent application (#571)
- 7837b962 fix: AU-1298: Translate webform titles (#570)
- 3cf11cd6 fix: AU-1232: Add access checks to custom endpoints. (#569)
- f4865068 fix: AU-1132: Re-style and reorder buttons in webforms (#567)
- b764e599 feat: AU-1278: Add new roles (#566)
- b79b14a3 fix: AU-1263: remove duplicate title from drafts (#565)
- c9d7635f fix: AU-1267: Add correct traslation to kasko (#564)
- 609e4b11 feat: AU-938: KUVA taiteen perusopetus 50 (#405)
- 1de96bd5 feat: AU-984: Nuoriso toiminta palkka ennakko 66 (#523)
- 7e846e09 fix: AU-1275 & AU-1279: move pagination to bottom of application search, reorder card (#559)
- ede00b41 feat: AU-1280: add link to asiointirooli block (#560)
- ecdd4649 fix: AU-946: robot test templates (#562)
- 65375788 fix: AU-819: Robot test checks (#538)
- af1723f0 feat:  AU-1128: Subvention required/limit/starttiraha options (#555)
- 0b0e8e6d fix: AU-1276: Use relative path for tietoliikenne schema in ATV tests (#561)
- a50055f1 fix: AU-1099: Show local tasks on profile edit page (#556)
- 66a55c26 fix: AU-1212: Unified Applicant info -page in applications (#558)
- 4bdfcaa9 fix: Audit log version to 0.9.6 (#557)
- f79202bc fix: AU-1213: Missing webform print translations (#554)
- fe13470a fix: AU-1197: Fixed and issue regarding webform submissions and the "avustukset_summa" element. (#551)
- 7cd3023e feat: AU-1235, AU-1237, AU-1238: KH Yleisavustuslomake & Language changes (#552)
- c5285a77 Hotfix AU-1266
- 64336128 AU-1266: Fix missing webform error
- 145d2159 feat: AU-1261 hakuprofiili exit js (#546)
- c5d2a2d9 fix: AU-1241: change text in tabs (#548)
- 29aa4bc0 fix: AU-1243 add info in beginning of profile forms (#549)
- e7d3d2e8 fix: AU-1252: Fix translations (#544)
- 5fbd2665 fix: AU-1226: Update audit log module (#535)
- c6c15241 feat: AU-954:  Liikunnan yleisavustus 56 (#414)
- 2c9caf18 fix: AU-760: Etsi avustusta page fixes (#532)
- 15aec3b6 fix: AU-537: Add translation (#542)
- 7529063b fix: AU-1198: Add missing roles back to submission display (#541)
- 012b110b docs: Update ALL examples to latest versions. (#543)
- dcf3ce41 config: Update configuration (#540)
- d8a6c6d2 fix: Update ALL examples to latest versions. (#539)
- 01342261 fix: AU-1077: Hide fields in print (#530)
- 6ca8d7e6 feat: AU-937: Nuoriso lomaleiri 65 (#415)
- 7363c9a3 feat: AU-XX: Add & update examples + schema (#537)
- 9662d863 Add webform id
- e2c470fd feat: AU-978: Kasko Iltapäivätoiminnan harkinnanvarainen lisäavustushakemus 53 (#527)
- 20992875 config: Update configuration (#533)
- 1cb11cef feat: AU-567: Form locks (#524)
- af9df1e1 feat: AU-1192: Application dynamic year selections (#529)
- 4fd0da35 fix: AU-1230: Permissions fix to viewing an application form and draft del… (#528)
- b5f80864 fix: AU-1217: translate title (#526)
- a94f9c2b fix: AU-1195: remove one conditional logic (#525)
- 4ca32a1a fix: AU-1221: Re-style activity fieldset (#522)
- c6825177 feat: AU-1052: Remove unregistered company (#499)
- e0617c18 config: Update configuration (#521)
- a45cb489 fix: AU-1029: format date in preview (#519)
- 19b4e452 fix: AU-1134, AU-1160: Premises composite preview fix (#509)
- 46073075 fix: AU-1182, AU-1185, AU-1204, AU-1208 (#513)
- f8c69217 fix: AU-1177: KUVA Toiminta: Budget fields as required. Fixes to static budget component error labels (#517)
- e4a066cf fix: AU-1068: Handle bank account owner info metadata (#518)
- ea63a866 docs: Version bump + changelog
- 90a951be fix: AU-1169: Application acting years dynamic values (#502)
- b08ea34c config: Update configuration (#516)
- d9118c9d Merge tag 'AU-1215-missing-frontpage-things' into develop
- a6d17776 Hotfix AU-1215-missing-frontpage-things'
- 78aee8cf AU-1215: Remove check for appenv in application number.
- 90b3520d (tag: AU-1215-missing-applications-prod, tag: 2023.13.2) Hotfix AU-1215-missing-applications-prod
- 10f1c8d6 AU-1215: Restore appenv parameter for queries.
- c4dc334b AU-1215: Remove applicant type from search params with registered community
- 9dcda8f6 AU-1215: Remove appenv
- 02da240e fix: AU-1153, AU-1203, AU-1184, AU-1186, AU-1167, AU-1183, AU-1196 (#510)
- aaae6b79 feat: AU-1113 validation errors on fieldsets. (#512)
- 014b7deb fix: AU-1163: Change static budget components to non multivalue (#511)
- 

## 2023.13.1
- hotfix update.

## 2023.13
- 6045994f fix: AU-819: General robot tests improvements (#473)
- ddaef87a fix: AU-1133: Disable form actions during ajax call (#491)
- c3d8b2e8 fix: AU-1162: Fix printing label text in html elements class attribute (#490)
- c04a1171 fix: AU-1070: Add metadata to premises fields (#492)
- 0cf50557 config: Update configuration (#494)
- d5aba526 fix: AU-1109: Profile form fixes. (#465)
- 5f730245 fix: AU-1120: Hide unregistered companies without name (#489)
- 253c53d8 feat: AU-1007: Delete documents via bash for testing. (#454)
- 16b04415 feat: AU-1147: Add new compensation ids and change kuva keha to use these (#486)
- 1b056222 fix: AU-1108: Fix error displays (#488)
- 199426ce Update configuration (#487)
- 923fcda2 Revert "config: Update configuration (#479)" (#485)
- a851cb92 fix: AU-1144: Fix print link on oma asiointi pages. (#484)
- 96b55b9c fix: AU-1136: Fix block title translations (#482)
- b8580ea9 fix: AU-1120: Fix empty profile errors (#483)
- 5612fd29 fix: AU-1143: ATV module version to support new service formats. (#481)
- 4ed6f32a config: Update configuration (#479)
- 81cf39a8 fix: AU-XX: Fix unexpected error (#480)
- efa92c0b fix: AU-1111: Re-style TPR New application -block (#477)
- 9a36eb77 config: Update configuration (#478)
- c3af7e70 fix: AU-622: Sort printer pages (#476)
- e6e5b343 fix: AU-1032: Autologout uudelleenohjauksen korjaus (#466)
- 3b880624 fix: AU-XX: Fix typo in definition (#475)
- 7718cec1 fix: AU-1060, AU-917: Fix fields in Toiminta form (#472)
- 07d4fc75 fix: AU-1093 date fields no longer have default value (#468)
- 48776076 config: Update configuration (#470)
- 73b9f88c fix: AU-XX: Make sure booleans are proper booleans, and not "true" etc (#471)
- 16bbd455 config: Update configuration (#469)
- 9848bae8 fix: AU-1106: Fix profile info texts (#463)
- 01e0ad66 config: Update configuration (#464)
- 2ed85ae9 fix: AU-1094: Address component requirements (#458)
- d04d166d config: Make commands
- ee7edf15 fix: AU-1072: Fix multivalue fields error displays (#462)
- 212ebdcc feat: AU-860: kuva_toiminta robot tests (#460)
- 26d38ae7 fix: AU-1088: Hidden summation field makes title display go away, no matter the setting. (#448)
- 5c395a5d config: Update configuration (#461)
- 9ca56cd3 fix: AU-1082: Remove unused duplicate application language field (#459)
- ea4d6cc0 feat: AU-1053: Add cancel button for profile forms (#457)
- 0b3bc308 config: Update configuration (#456)
- 80fcfeb9 fix: AU-1069: Fix premises styles (#431)
- 8366d67a fix: AU-1100: Fix duplicate budget info elements being saved to ATV doc. (#453)
- 293384ce feat: AU-913: Taide ja kulttuuri kehittämisavustus robot-testit (#452)
- ae56f2e1 feat: AU-879: Create infoboxes to Oma asiointi (#404)
- e0ed15f2 fix- AU-XX: Load Select2 library in composer, autologout patches (#451)
- 7f34faf3 config: Update configuration (#449)
- 731a77b1 fix: AU-XX: Remove validation breaking definitions again. (#450)
- 853d5c9a config: Update configuration (#446)
- 96044deb fix: AU-1085: update date formats.  (#445)
- 1166d612 fix: AU-915 kuva toiminta avustus webform update (#447)
- 87c22c80 fix: AU-1089: Get budget compensation value from subventions (#444)
- d87f06d4 fix: AU-1071: Number fields, dot and comma number processing (#434)
- 53d1ce39 fix: AU-911: kehittamisavustus updates (#437)
- 6b405e15 fix: AU-1067: Return address metadata (#442)
- b6f8b131 fix: AU-1087: Disable premise type from kuva proj and keha (#441)
- 682e6046 fix: AU-XX fix minify in hdbt_subtheme to ignore Drupal and DrupalSettings to make Drupal.t() work again in theme.
- c86bfc76 config: Update configuration (#440)
- 64229ae8 fix: AU-1006: Fix tests, separate robot tests to make commands (#435)
- b4be95ff fix: AU-1073: Remove extra character from attachment description (#432)
- d72a05e5 fix: AU-1083: remove parenthesis if no role (#438)
- cf41ee65 config: Update configuration (#439)
- bb5a5400 fix: AU-1086: Update project mappings (#433)
- 50fc3862 feat: AU-828: Check for used application numbers (#424)
- dc6bca43 fix: AU-1085: Reorder budget field component order (#426)
- 1b84577f fix: AU-1038: Profile form fixes (#409)
- 7ac99beb config: Update configuration (#430)
- d3567167 feat: AU-1026: Redirect to profile form if missing (#429)
- 087cb97d fix: AU-922: Fix misconfigured file types on yleisavus applcation (#428)
- 72511199 fix: AU-1059: Add tooltips to budget component fields on KUVA PROJ&TOIM (#427)
- 495f0bc1 fix: AU-917, AU-1060, AU-1061, AU-1062, AU-1063: partial fixes to projekti and toiminta forms, tooltips in fieldsets, tooltips in first page composites, fieldset for other tila component (#422)
- be39388a config: Update configuration (#423)
- 5e29beb8 fix: AU-1057: Fix premises component (#421)
- 7f84a90b fix: AU-1049: translate status strings (#419)
- 2ffece15 fix: AU-XX: Make applicant info fields not required. (#420)
- 155785a1 fix: AU-878: Login redirect user to oma asiointi (#418)
- 733b1301 fix: AU-1044: Correct configuration to other attachment fields (#412)
- 3c37113e config: Update configuration (#413)
- 7fa8bb49 fix: AU-1028: add translations to metadata (#406)
- e3993164 fix: AU-1050, AU-1051: field fixes to kehitys form (#411)
- a9593a41 fix: AU-1036: Move remove button to inside fieldsets when there is a fieldset (#410)
- 19ce8118 config: Automatic update (#400)
- 97c7d3ea fix: AU-1045: Update premise radiobutton translations (#408)
- ecc5935a fix: AU-1008: Delete company email (#407)
- 428ca10d fix: AU-993: Add sv translations (#361)
- e9c430d2 fix: AU-1047: style budget component (#402)
- 4a357ad5 feat: AU-865: Make business purpose editable (#401)
- 8b8d894d feat: AU-911 kuva kehittamisavustus update for design (#403)
- 3d6e91e3 fix: AU-1040: Test & fix general applications (#395)
- 5db0830c fix: AU-877: fix logic and a few warnings (#399)
- b490b41a config: Automatic update (#398)
- dd8b5678 fix: AU-877:  Add block for mandate button (#383)
- 2b95df4d feat: AU-917 Webform of KUVA-toiminta-avustus to match design (#396)
- c36636f9 feat: AU-1033: Metadata to budget component generated data (#397)
- a87d2b00 fix: AU-1020: remove error message from mandate (#394)
- ac11f2c6 feat: AU-935: add translations to front banner (#392)
- c846fb6a config: Update composer (#393)
- dfb7a514 config: Update configuration (#391)
- daaa2442 fix: AU-1023 fix summation field effects on different pages (#389)
- 2c2e0bc2 feat: AU-1021: Add multi budget component mappings (#390)
- 2874f2dd fix: AU-895 design changes to the projekti form (#388)
- 035dcc2f feat: AU-1027: Import webforms with drush command (#374)
- 9be682b1 fix: AU-1022: Handle dummy boolean fields in webform (#380)
- 9a2c74d6 fix: Fix code styles in grants handler module (#387)
- a39cb59f feat: AU-910: KUVA kehitys form (#372)
- cf762d6a fix: AU-842: Fix error message indexes for profile forms (#382)
- de8a0cfb feat: AU-XX: Conditional validator (#379)
- bb5f3a02 feat: AU-1034: Add support for bank account details for unreg community. (#384)
- 128e81a7 fix: AU-1004: fix Unregistered community in mandate (#381)
- 9ab0f84b fix: AU-1030: Always Run property structure callback (Budget Info) (#376)
- 2db0f497 fix: AU-1031: Fix validations & data mappings. (#377)
- d158d9fa fix: AU-1015: style login page block (#371)
- 1d03848d fix: AU-1005: add max width to number element (#375)
- 0b47d2ac fix: AU-1014: add translations to preview warning (#373)
- 834a631d feat: AU-488: Print applications with metadata. (#237)
- b5faed4e fix: AU-950: fix accessible tooltips (#369)
- 897b1263 feat: AU-915 kuva toiminta avustus webform changes (#356) (#370)
- f2fd9ba1 fix: AU-1019: remove dot from frontpage info block title & change site name (#367)
- 394f5e14 fix: AU-XX: Kuvaprojektikorjauksia (#368)
- 4a22d10d fix: AU-997: Restore missing prints (#366)
- 83218989 fix: AU-866: Validate addresses and display errors (#365)
- 319f95c4 fix: AU-994: Service channel link modification cache settings, missing attachment description (#363)
- c7716d2a fix: AU-999: Add role check to new application buttons (#362)
- 559cd2bd fix: AU-953: Fixed cards in application search view (#360)
- ba5600d0 fix: AU-895: Add form styles for kuva projekti (#359)
- 30930206 fix: AU-847: Allow language switcher to work on edit application pages (#358)
- 5308806b fix: AU-930: Azure pipelines setup (#357)
- 2f70b1c6 feat: AU-930: Extensive robot tests (#353)
- c4e2782a Fix: AU-996: Change budget component amount fields to number (#352)
- fc7f6cb3 feat: AU-1000: Make GrantsHandler respect applicant type for all users. (#354)
- 889afebb Fix: AU-881: Fix profile forms (#351)
- c8dcd9fb fix: AU-XX: Do some checking for arrays before accessing arrays (#349)
- f6e467dc fix: AU-995 add remove button to a multiple field missing it. (#348)
- 11449265 feat: AU-889: KUVA Projektihakemus (#322)
- 2f36ae26 fix: AU-795: change link to mandate in all roles


## 2023.12
- da2ba17e fix: AU-867: Remove TPR link to current service page and replace with serv… (#318)
- 5c1b29dc fix: AU-867 AU-877: Fix delete button visibility to be only on draft versions (#323)

## 2023.12
da2ba17e fix: AU-867: Remove TPR link to current service page and replace with serv… (#318)
5c1b29dc fix: AU-867 AU-877: Fix delete button visibility to be only on draft versions (#323)

## 2023.11
- 5d0ffccc fix: Limit attachment validations to allow file deletion.
- f962b90b fix: AU-855: Re-style status tags (#306)
- f2cfb4cf feat: AU-539 Redirect after logout (#308)
- 4fd72dda feat: AU-776: Install and configure disable_messages module (#307)
- d996329a fix: AU-843: Fix account confirmation errors (#297)
- 5450acf7 fix: AU-858: Display grant attachment validation errors inline (#305)
- 15e10e2b fix: AU-826: omat tiedot error messages (#304)
- 38f26168 fix: AU-856: Add check to javascript alter to prevent errors (#303)
- b8705f93 fix: AU-826: Show warning when user is returned to Oma asiointi (#302)
- 79b400f7 feat: AU-839: Fix attachment errors (#300)
- 128f9a26 fix: AU-849: Requested KASKO form changes (#295)
- 8da239cc fix: AU-835: preview print icon fix (#301)
- 0e43c48a fix: AU-850: Redirect user from user page to my service page (#299)
- 4c44bd69 config: Update configuration (#293)
- 339d8294 Hotfix release 2023.11.1
- 9619e7c6 AU-854: hotfix: Add forms to config ingore.
- bfab7675 fix: AU- 848: Show only messages that are received from kasittelyjarjestelma in Oma asiointi -block (#294)
- 74219df4 fix: AU-XX: fix application timezones (#296)
- 2aa6bb3b fix: Rename conf file with space in name (#298)
- 9ae53112 config: Update configuration (#291)
- 90589497 config: Update configuration (#290)
- 7041a8d4 docs: version, changelog, README
- 7bc0e354 feat: AU-735: add Frontpage application info block (#287)
- fa094576 fix: AU-686: grants profile form validation (#279)
- 666ba6fc config: Update configuration (#288)

## 2023.10
- 96b77d03 fix: AU-747: Remove redirect to compilation page in case of an error. User is left on the form page with error message. (#280)
- 26bed988 config: Update ATV to 0.9.7
- b8847ab5 docs: Update changelog
- 2e7da443 fix: AU-XX: Hide UKK from menu (#284)
- c59c132f config: Update configuration (#285)
- 71e9aa40 fix: Remove DVV debug prints.
- 2a887704 fix: AU-XX: Make some changes to viestintasivut (#283)
- 8e115930 config: Update configuration (#281)
- 8fad0c54 fix: AU-835: Added page breaks before h2 to print (#282)
- e78b3b15 Add message to grants_mandate error handling.


## 2023.9

- a53fdf26 feat: AU-756: Display blocks on login page (#276)
- 11e87cb9 fix: AU-769: Disable application fields in service edit form if webform field is selected (#262)
- bd67fc62 config: Update configuration (#277)
- a2d1f750 fix: update HP version

## 2023.8

- 46f108d3 config: Update configuration (#275)
- 29d839c1 fix: AU-617: add info icon to header notification (#271)
- 65714e28 fix: AU-822: Remove extra "selected" from sorting (#273)
- 8e82fdd5 fix: AU-XX: Fix sidebar in Service page -node (#272)
- 7e564ac9 fix: AU-811 change links to supplementary buttons on oma asiointi
- 3927cb66 config: Update configuration (#270)
- a713d36c fix: AU-757 move dialog translations to module from theme (#268)
- 307446d2 config: Update configuration (#269)
- 87286a29 feat: AU-787: add en and sv translations to webforms (#263)
- bf71a5c3 fix: AU-806: fix styles in service page (#264)

## 2023.7
- fc496e49 fiX: AU-691: Omat tiedot edit translations and texts (#267)
- 660b3b59 AU-820: fix: Update ATV & GDPR versions. (#265)
- 7ade40ee refactor: AU-791: Update profile form to custom one. (#243)
- bbc4923c fix: AU-804: Fix message attachment integration (#255)
- e7095d8f fix: AU-617: Add header notification to webform print (#258)
- 2cb26ef1 refactor: AU-817: Fix sonarcloud validation (#260)
- ed3301c2 config: Update configuration (#259)
- c2a84f7a feat: AU-574: Update tests & translations (#179)
- 3552d3a3 config: Update configuration (#257)
- 59a128ba fix: AU-XX fix translation syntax in grants_handler (#256)
- 1e306b88 fix: AU-792: Add subscriber to handle guzzle errors (#252)
- fe557ce5 config: Update configuration (#254)
- c1351085 Revert "config: Update configuration (#251)" (#253)
- 3c6ea8d5 config: Update configuration (#251)
- b465464d fix: AU-744: Changes in showing messages in Oma asiointi -page & -block (#242)
- bcef3c4d feat: AU-695: Configure allowed roles (#247)
- 314327b2 config: Update configuration (#250)
- b9a8b169 config: Update configuration (#248)
- f0ebf55c fix: AU-666: Delete bank confirmation files automatically (#192)
- e7b59809 fix: AU-802: Remove avustus field from form (#246)
- 80edb468 fix: AU-601 autologout modal (#244)
- 9de25d13 fix: AU-796: Fix subvention validations. (#238)
- 2260d29e AU-574: Robot test pipeline
- a093f264 confifg: Update configuration (#241)
- 6f5dd41b fix: AU-800: Add cache contexts for ServicePageAuthBlock (#240)
- faad9993 fix: Fix cache setting. SonarCloud.
- 4a3cbcf7 config: Update configuration
- 77ef8824 fix: AU-XX: Add context to Asiointirooli-block translations (#236)
- 43ec3aca feat: AU-681: replace missing data in profile with text "unknown" (#205)


## 2023.6

- ebf0cf14 fix: Add CHANGELOG.md
- 1fccda54 config: Update configuration
- a11d1eac refactor: AU-661: Move GDPR api module to contrib
- 10ddadb3 feat: AU-761: Create block for asiointirooli (#228)
- 3da02864 fix: AU-757: fix the style of modal dialogs (#229)
- 9ba3a1e6 feat: AU-758 Create summation webform component for form fields (#214)
- 5c2b8525 build: AU-732: Update helfi_audit_log module dependency
- 12d7be93 fix: AU-775: Add error notes under each field
- cc28d31b Revert "config: Update configuration" (#231)
- e2ec5beb Revert "config: Update configuration"
- 84244dca config: Update configuration
- 1206c079 config: Update configuration
- 45b318c4 refactor: Update configuration
- a9392961 fix: AU-755: style Yhteisön tiedot in application form (#219)
- 9e7f681d fix: AU-772: style Oma asiointi -block (#216)
- 4ce1152a fix: AU-746: Change text Lisää x avustus to Lisää uusi x avustus (#211)
- e676c6d6 fix: AU-XX fix webform print thead to contain th not td (#217)
- 8c17bfff AU-734: fix: AU-734 accordion to match the current version of accordion (#220)
