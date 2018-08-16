// Number Format
Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 
// 1 HOME PAGE
	
// 2 SETUP PAGE



// 3 BUY AND SELL PAGE //////////////////////////////////////////////////////////
	// START 3A INJECT IN THE STOCK------------------------------------------------------
function initItem(){
	var initItem = '1';
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				initItem : initItem,
			},
			success : function(html, textStatus){
				$("#itamePlace").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		//5 Load product to Edit-->
function editItem(itemId){
	var editItem = itemId
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				editItem : editItem,
			},
			success : function(html, textStatus){
				$("#itamePlace").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		//5 Load product to Edit-->
function getItemsDet(){
	var itemIdtoGet1 = $("#itemCode1").val();	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				itemIdtoGet : itemIdtoGet1,
			},
			success : function(html, textStatus){
				$("#qtydiv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		//5 Load product to Edit-->
function checkExistance(){
	var purchaseOrder1 = document.getElementById('purchaseOrder').value;
	var deliverlyNote1 = document.getElementById('deliverlyNote').value;
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				purchaseOrder : purchaseOrder1,
				deliverlyNote : deliverlyNote1,
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		//5 Load product to Edit-->
function insertItem(){
	
	var purchaseOrder = document.getElementById('purchaseOrder').value;
	//alert(purchaseOrder);
	if (purchaseOrder == null || purchaseOrder == "") {
        alert("Purchase Order must be filled out");
        return false;
    }
	var deliverlyNote = document.getElementById('deliverlyNote').value;
	if (deliverlyNote == null || deliverlyNote == "") {
        alert("Deliverly Note must be filled out");
        return false;
    }
	var unityPrice = document.getElementById('unityPrice').value;
	if (unityPrice == null || unityPrice == "") {
        alert("Unity Price Note must be filled out");
        return false;
    }
	var itemCode1 = document.getElementById('itemCode1').value;
	var qty = document.getElementById('qty').value;
	if (qty == null || qty == "") {
        alert("Deliverly Note must be filled out");
        return false;
    }
	var docRefNumber = document.getElementById('docRefNumber').value;
	var customerName = document.getElementById('customerName').value;
	var customerRef = document.getElementById('customerRef').value;
	var operationNotes = document.getElementById('operationNotes').value;
	
	//call relevant function to check order
	checkOrder(); //essential
	
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				purchaseOrder : purchaseOrder,
				deliverlyNote : deliverlyNote,
				unityPrice : unityPrice,
				itemCode1 : itemCode1,
				qty : qty,
				docRefNumber : docRefNumber,
				customerName : customerName,
				customerRef : customerRef,
				operationNotes : operationNotes,
				
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		// 4 REMOVE AN ITEM
function removeOnPo(removeTransaction){
	//var txt;
    var r = confirm("Are you sure you want to remove this item from the list");
    if (r == true)
		{
      //  txt = "You pressed OK!";
    
	
	
	var InvoinceNo = document.getElementById('purchaseOrder').value;
	var DocNo = document.getElementById('deliverlyNote').value;
	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				removeTransaction : removeTransaction,
				purchaseOrder : InvoinceNo,
				deliverlyNote : DocNo,
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
	
	}
	else 
	{
       // txt = "You pressed Cancel!";
    }
    //document.getElementById("demo").innerHTML = txt;
	
}
	// END 3A INJECT IN THE STOCK-----------------------------------------------------------------------------


	// START 3B INJECT IN THE STOCK------------------------------------------------------
		//1 CHECK IF THE INVOICE EXISTS
function generateInvoice(){
	var generateINV = '1';
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				generateINV : generateINV,
			},
			success : function(html, textStatus){
				$("#generatedInv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		//1 CHECK IF THE INVOICE EXISTS
function checkInvoince(){
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	var DocNo = document.getElementById('DocNo').value;
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				InvoinceNo : InvoinceNo,
				DocNo : DocNo,
			},
			success : function(html, textStatus){
				$("#listInvoiceTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		// 2 SELECT ITEMS FROM DB
function getInvoiceItemsDet(){
	var thenthis = $("#itemInvoiceCode").val();	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				thenthis : thenthis,
			},
			success : function(html, textStatus){
				$("#putthetest").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}// 2 SELECT ITEMS FROM DB
		//
function getInvoiceItemsDet(){
	var invioceItemIdtoGet = $("#itemInvoiceCode").val();	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				invioceItemIdtoGet : invioceItemIdtoGet,
			},
			success : function(html, textStatus){
				$("#invioceItems").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
	// END	
	
	
	// START 3C  TAKE OUT AN ITEM
function ouItem(){
	
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	if (InvoinceNo == null || InvoinceNo == "") {
        alert("InvoinceNo must be filled out");
        return false;
    }
	var InvoiceDeliverlyNote = document.getElementById('InvoiceDeliverlyNote').value;
	if (InvoiceDeliverlyNote == null || InvoiceDeliverlyNote == "") {
        alert("InvoiceDeliverlyNote must be filled out");
        return false;
    }
	var InvoiceUnityPrice = document.getElementById('InvioceUnityPrice').value;
	if (InvoiceUnityPrice == null || InvoiceUnityPrice == "") {
        alert("InvoiceUnityPrice must be filled out");
        return false;
    }
	var itemInvoiceCode = document.getElementById('itemInvoiceCode').value;
	var InvioceQty = parseInt(document.getElementById('InvoiceQty').value);
	if (InvioceQty == null || InvioceQty == "") {
        alert("InvioceQty must be filled out");
        return false;
    }
	var DocNo = document.getElementById('DocNo').value;
	if (DocNo == null || DocNo == "") {
        alert("DocNo must be filled out");
        return false;
    }
	var InvoiceCustomerName = document.getElementById('InvoiceCustomerName').value;
	if (InvoiceCustomerName == null || InvoiceCustomerName == "") {
        alert("InvoiceCustomerName must be filled out");
        return false;
    }
	var InvioceustomerRef = document.getElementById('InvoiceCustomerRef').value;
	if (InvioceustomerRef == null || InvioceustomerRef == "") {
        alert("InvioceustomerRef must be filled out");
        return false;
    }
	var InvioceOperationNotes = document.getElementById('inOpNote').value;
	
	var limiter = parseInt(document.getElementById('limiter').value);
	if (limiter == null || limiter == "") {
        alert("nta "+itemInvoiceCode+" ufite muri stoke, wabanje ukarangura? ko ntakwemerera gucuruza ibyo udafite");
        return false;
    }
	
	
	if (DocNo == null || DocNo == "") {
        alert("Name must be filled out");
        return false;
    }
	
	if (InvioceQty > limiter)
	{
	   alert("The qty: "+InvioceQty+" must be less than: "+limiter+", change the qty and try again, ...");
        return false;	
	}
	
	//alert(InvioceOperationNotes);
	//alert('HELLO!');
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				InvoinceNo : InvoinceNo,
				DocNo : DocNo,
				InvoiceDeliverlyNote : InvoiceDeliverlyNote,
				InvoiceUnityPrice : InvoiceUnityPrice,
				itemInvoiceCode : itemInvoiceCode,
				InvioceQty : InvioceQty,
				limiter : limiter,
				InvoiceCustomerName : InvoiceCustomerName,
				InvioceustomerRef : InvioceustomerRef,
				InvioceOperationNotes : InvioceOperationNotes,
				
				
				
			},
			success : function(html, textStatus){
				$("#listInvoiceTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
		// 4 REMOVE AN ITEM
function removeOnInv(removeTransaction){
	//var txt;
    var r = confirm("Are you sure you want to remove this item from the list");
    if (r == true)
		{
      //  txt = "You pressed OK!";
    
	
	
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	var DocNo = document.getElementById('DocNo').value;
	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				removeTransaction : removeTransaction,
				InvoinceNo : InvoinceNo,
				DocNo : DocNo,
				
			},
			success : function(html, textStatus){
				$("#listInvoiceTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
	
	}
	else 
	{
       // txt = "You pressed Cancel!";
    }
    //document.getElementById("demo").innerHTML = txt;
	
}
		// 5 INVOICE ITEM TOTAL
function invoiceTotal(){
	var unityPriceToAdd = document.getElementById('InvioceUnityPrice').value;
	var invoiceQtyToAdd = document.getElementById('InvoiceQty').value;
	
	var totalPrice = unityPriceToAdd * invoiceQtyToAdd;
	document.getElementById("invoiceTotalPrice").innerHTML = '<input class="form-control" value="'+totalPrice+'"disabled/><span class="input-group-addon">F</span>';
	
}
		// 5 INVOICE ITEM TOTAL
function purchaseTotal(){
	var unityPriceToAdd = document.getElementById('unityPrice').value;
	var invoiceQtyToAdd = document.getElementById('qty').value;
	
	var totalPrice = unityPriceToAdd * invoiceQtyToAdd;
	var totalprice1 = (totalPrice).formatMoney(0);
	document.getElementById("purchaseTotalPrice").innerHTML = '<input class="form-control" value="'+totalprice1+'"disabled/><span class="input-group-addon">F</span>';
	
}

function bringPrint(){ 
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	document.getElementById("printInvoice").innerHTML = '<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button> <a href="invoices.php?invoiceNo='+InvoinceNo+'" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></button>';
}						
	// END 3B INJECT IN THE STOCK-----------------------------------------------------------------------------


	// START 3C ITEM INFO------------------------------------------------------
function itemInfo(itemInfoId){ 
$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				itemInfoId : itemInfoId,
				
				
				
			},
			success : function(html, textStatus){
				$("#itemInfoPop").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
	
// 4 USERS PAGE

// 5 CLIENTS PAGE

// 6 SUPPLIER PAGE

// 7 INVOICE PAGE

// 8 PROFORMA INVOICE PAGE

// 9 PURCHASE ORDER PAGE

// 10 GENERAL REPORTS

// 11 FASTER / SLOW ITEMS PAGE

// 12 RETURN ON INVESTIMENT PAGE