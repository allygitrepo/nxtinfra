set MYDATE=%DATE%
echo The date variable is: %MYDATE%
set DD=%MYDATE:~7,2%
set MM=%MYDATE:~4,2%
set YYYY=%MYDATE:~10,4%

set DASH_DATE=%YYYY%-%MM%-%DD%
echo Result: %DASH_DATE%

set todir=C:\p2p_backup\nxtinfra_p2p\%DASH_DATE%
set fromdir=C:\xampp\htdocs\
echo %todir%
echo %fromdir%

mkdir %todir%

mkdir %todir%\api
mkdir %todir%\approval
mkdir %todir%\approval_adjustment
mkdir %todir%\nhitSI
mkdir %todir%\budget
mkdir %todir%\budget_proposal
mkdir %todir%\dms
mkdir %todir%\goods_issue_note
mkdir %todir%\grn
mkdir %todir%\income
mkdir %todir%\ipc 
mkdir %todir%\payment
mkdir %todir%\petty
mkdir %todir%\product
mkdir %todir%\provisional_jv
mkdir %todir%\purchase_order
mkdir %todir%\purchase_requisition 
mkdir %todir%\report 
mkdir %todir%\revenue_jv
mkdir %todir%\setting
mkdir %todir%\supp_invoice
mkdir %todir%\tally_sample
mkdir %todir%\tender 
mkdir %todir%\travel_approval
mkdir %todir%\vendor
mkdir %todir%\retention
mkdir %todir%\purchase_order_ml
mkdir %todir%\advance



copy %fromdir%\api\*.php 			%todir%\api
copy %fromdir%\approval\*.php 		%todir%\approval
copy %fromdir%\approval_adjustment\*.php %todir%\approval_adjustment
copy %fromdir%\nhitSI\*.php 		%todir%\nhitSI
copy %fromdir%\budget\*.php 		%todir%\budget
copy %fromdir%\budget_proposal\*.php %todir%\budget_proposal
copy %fromdir%\dms\*.php 			%todir%\dms
copy %fromdir%\goods_issue_note\*.php %todir%\goods_issue_note
copy %fromdir%\grn\*.php 			%todir%\grn
copy %fromdir%\income\*.php 		%todir%\income
copy %fromdir%\ipc\*.php 			%todir%\ipc
copy %fromdir%\payment\*.php 		%todir%\payment
copy %fromdir%\petty\*.php 			%todir%\petty
copy %fromdir%\product\*.php 		%todir%\product
copy %fromdir%\provisional_jv\*.php %todir%\provisional_jv
copy %fromdir%\purchase_order\*.php %todir%\purchase_order
copy %fromdir%\purchase_requisition\*.php %todir%\purchase_requisition
copy %fromdir%\report\*.php 		%todir%\report
copy %fromdir%\revenue_jv\*.php 	%todir%\revenue_jv
copy %fromdir%\setting\*.php 		%todir%\setting
copy %fromdir%\supp_invoice\*.php 	%todir%\supp_invoice
copy %fromdir%\tender\*.php 		%todir%\tender
copy %fromdir%\travel_approval\*.php %todir%\travel_approval
copy %fromdir%\vendor\*.php 		%todir%\vendor
copy %fromdir%\retention\*.php 		%todir%\retention
copy %fromdir%\purchase_order_ml\*.php 		%todir%\purchase_order_ml
copy %fromdir%\advance\*.php 		%todir%\advance

