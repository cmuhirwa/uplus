
//variable to hold items for current order
var orderItems = [];

$("#addPurchaseItemBtn").on('click', function(){
	//add item to the purchase

	var item = $("#itemCodeInput").val()
	var itemName = $("#itemCodeInput option:selected").text()
	var quantity = $("#orderQuantityInput").val()
	var unit = $("#itemUnitSelect").val()
	var unitPrice = $("#unitPriceInput").val()

	var totalAmt = $("#totalPriceDisplay").val(); //for display only



	if(!isNaN(Number.parseInt(item)) && !isNaN(Number.parseInt(quantity)) && Boolean(unit) && !isNaN(Number.parseInt(unitPrice)) ){
			orderItems.push({'itemId':item, 'name':itemName, 'quantity':quantity, 'unit':unit, 'unitPrice':unitPrice})
			//sending the data
			$("#itemCodeInput").val("");
			$("#orderQuantityInput").val("");
			$("#itemUnitSelect").html("<option>Unit</option>");
			$("#unitPriceInput").val("");
			$("#totalPriceDisplay").val("");

			//adding them to the list
			$("#orderItemsDisplay").append("<tr><td>1</td><td>"+itemName+"</td><td>"+quantity+"</td><td>"+unitPrice+"</td><td>"+totalAmt+"</td></tr/>");

			//display the table
			$("#itemPlace").css('display', 'block')


	}else{
		alert("Add all details")
	}
});

//purchase order submission
$("#purchaseOrderForm").on('submit', function(e){
	e.preventDefault();

	//check if the organisation is selected

	//here we need to create the purchasing order
	var supplier = $("#orgInput").val();
	if(!isNaN(parseInt(supplier))){
		$.post(apiLink, {action:'addPurchasingOrder', status:'draft', supplier:supplier, doneBy:currentUserId}, function(data){
				
				//check purchase order status
				if(data.status == true){
					orderId = data['data'].id
					//add order items
					$.post(apiLink, {action:'addPurchasingOrderItems', items:orderItems, order:orderId}, function(itemData){
						//returns from purchasing orders items addition
					})
				}else{
					alert("Error adding purchase order")
				}
			});
	}else{
		alert("Please choose supplier");
		return false;
	}
})