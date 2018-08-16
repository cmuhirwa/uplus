--view per parchase order and deliverly Note
SELECT 
T.`transactionID`, T.`itemCode`,I.`itemName`,
T.`qty`,I.`unit`,  T.`trUnityPrice`, 
T.`qty` * T.`trUnityPrice` AS Total,`operation`
FROM `transactions` T INNER JOIN `items` I 
ON T.`itemCode` = I.`itemId`

WHERE `purchaseOrder`='PO-01' AND `deliverlyNote`='DV-01'

--___________________________________________________________________________________________________________


--In out & Total Qty per Item
SELECT I.`itemId`, I.`itemName`,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) Ins,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Outs,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) -
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Balance,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId` ),0) -
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) Outstanding,

I.`unit`, I.`unityPrice`
FROM `items` I

WHERE I.`itemId` = ''

--___________________________________________________________________________________________________________

--Outstanding Store  In Value & Out Value
SELECT I.`itemId`, I.`itemName`,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) Ins_Value,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Outs_Value

FROM `items` I

WHERE I.`itemId` = ''

--___________________________________________________________________________________________________________

--Outstanding Store  In Value & Out Value (BY user)
SELECT I.`loginId`, I.`names`,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = 40 AND T.doneBy = I.`names` GROUP BY T.`itemCode`,I.`names`),0) Ins_Value,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = 40 AND T.doneBy = I.`names` GROUP BY T.`itemCode`,I.`names`),0)  Outs_Value

FROM `users` I

WHERE I.`loginId` = ''

--___________________________________________________________________________________________________________
--Outstanding Store  In Value & Out Value (BY user22) | VALUE

SELECT DISTINCT I.doneBy,I.`itemCode`,(SELECT P.itemName FROM items P WHERE P.itemId = I.`itemCode`) AS ITEM_NAME,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='In'  GROUP BY I.`itemCode`,I.doneBy),0) Ins_Value,
IFNULL((SELECT SUM(T.`qty`*T.`trUnityPrice`) FROM `transactions` T WHERE `operation`='Out' GROUP BY I.`itemCode`,I.doneBy),0) Outs_Value

FROM `transactions` I
WHERE (I.doneOn BETWEEN '2016-04-12 15:18:22' AND '2016-04-16 15:18:22')
AND TRIM(UPPER(I.doneBy)) LIKE '%RWA%'
/* AND I.`itemCode` = 40*/
ORDER BY I.doneBy,I.`itemCode`
--___________________________________________________________________________________________________________
--Outstanding Store  In Value & Out Value (BY user22) | QTY

SELECT DISTINCT I.doneBy,I.`itemCode`,(SELECT P.itemName FROM items P WHERE P.itemId = I.`itemCode`) AS ITEM_NAME,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In'  GROUP BY I.`itemCode`,I.doneBy),0) Ins_QTY,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' GROUP BY I.`itemCode`,I.doneBy),0) Outs_QTY

FROM `transactions` I
WHERE (I.doneOn BETWEEN '2016-04-12 15:18:22' AND '2016-04-16 15:18:22')
AND TRIM(UPPER(I.doneBy)) LIKE '%RWA%'
/* AND I.`itemCode` = 40*/
ORDER BY I.doneBy,I.`itemCode`

--___________________________________________________________________________________________________________

--Item Historic...in out values

SELECT 
T.`transactionID`,doneOn,`operation`, T.`itemCode`,I.`itemName`,
ROUND(T.`qty`, 2) AS Quantity,I.`unit`,  ROUND(T.`trUnityPrice`) U_Price, 
ROUND(T.`qty` * T.`trUnityPrice` , 2) AS T_Values,IFNULL(doneBy, 'Not Specified') as Done_by
FROM `transactions` T INNER JOIN `items` I 
ON T.`itemCode` = I.`itemId`

WHERE I.`itemId` = ''

ORDER BY T.`transactionID` ASC



-- Client --

SELECT DISTINCT `customerName` FROM `transactions` WHERE `operation` = 'out' AND `customerName`!= 'N/A'

-- Supplier --


SELECT DISTINCT `customerName` FROM `transactions` WHERE `operation` = 'in' AND `customerName`!= 'N/A'
