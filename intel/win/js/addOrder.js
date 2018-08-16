$(document).ready(function(){
	//PRODUCT CHOSE
	//item selected //loading the details
	$("#itemCodeInput").on('change', function(){
		var productId = $(this).val();

		//checking the details
		$.post(apiLink, {action:"getProduct", productId:productId}, function(data){

			//check status
			if(data.status){
				data = data.data;
				mainUnit = data.units.main.measurementUnit
				unitsSelect = $("#itemUnitSelect")
				unitsSelect.html("<option value="+mainUnit+">"+mainUnit+"</option>");

				otherUnits = mainUnit = data.units.others
				for (var i = otherUnits.length - 1; i >= 0; i--) {
					unit = otherUnits[i]
				}
				
				console.log(data)
			}else{
				alert("Error "+data.msg)
			}
			
		})
	});

	//total price calculation triggers
	$("#productQuantityInput").keyup(function(){
		//trigger function
		calculateOrderTotal();
	})

	$("#unitPriceInput").keyup(function(){
		//trigger function
		calculateOrderTotal();
	})

	$("#orderCurrencyInput").change(function(){
		//trigger function
		calculateOrderTotal();
	});

	//add item
	$("#addItemBtn").on('click', function(){

	});

	$('.DataTable').DataTable({
	    dom: 'Bfrtip',
	    buttons: [
	        'copy', 'csv', 'excel', 'pdf', 'print'
	    ]
	});

	var orderItems = [];

	$("#addPurchaseItemBtn").on('click', function(){
		//add item to the purchase

		var item = $("#itemCodeInput").val()
		var itemName = $("#itemCodeInput option:selected").text()
		var quantity = $("#productQuantityInput").val()
		var unit = $("#itemUnitSelect").val()
		var unitPrice = $("#unitPriceInput").val()

		var totalAmt = $("#unitPriceInput").val(); //for display only



		if(!isNaN(Number.parseInt(item)) && !isNaN(Number.parseInt(quantity)) && Boolean(unit) && !isNaN(Number.parseInt(unitPrice)) ){
				orderItems.push({'itemId':item, 'name':itemName, 'quantity':quantity, 'unit':unit, 'unitPrice':unitPrice})
				//sending the data
				// $("#itemCodeInput").val("");
				$("#productQuantityInput").val("");
				$("#itemUnitSelect").html("<option>Unit</option>");
				$("#unitPriceInput").val("");
				$("#totalPriceDisplay").val("");

				if(orderItems.length ==1){
					$("#orderItemsDisplay").html("")
				}

				//adding them to the list
				$("#orderItemsDisplay").append("<tr><td>"+orderItems.length+"</td><td>"+itemName+"</td><td>"+quantity+"</td><td>"+unitPrice+"</td><td>"+totalAmt+"</td></tr/>");

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
		var supplier = $("#selectSupplier").val();
		var currency = $("#orderCurrencyInput").val();
		var warehouse = $("#orderWareHouseInput").val();
		var budgetHolder = $("#selectBudgetHolder").val();
		var shippingMode = $("#shippingOptionInput").val();
		var shipmentDate = $("#shipmentDate").val();

		if(!isNaN(parseInt(supplier))){
			$.post(apiLink, {action:'addPurchasingOrder', status:'pending', supplier:supplier, currency:currency, warehouse:warehouse, budgetHolder:budgetHolder, shippingMode:shippingMode, shipmentDate:shipmentDate, doneBy:currentUserId}, function(data){
					
					//check purchase order status
					if(data.status == true){
						$(".successCenter").append("Final purchase order created successfully<br/>")
						orderId = data['data'].id
						//add order items
						$.post(apiLink, {action:'addPurchasingOrderItems', items:orderItems, order:orderId, doneBy:currentUserId}, function(itemData){
							//returns from purchasing orders items addition
							if(itemData.status){
								$(".successCenter").append("Final items added to order successfully!");
								setTimeout(function(){
									// location = 'purchaseorders'
								}, 1000)
							}else{
								alert("Error with items")
							}
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

	function calculateOrderTotal(){
		//check the variables
		quantity = $("#productQuantityInput").val()
		unityPrice = $("#unitPriceInput").val()
		totalMoney = (quantity*unityPrice).toString()+" "+$("#orderCurrencyInput").val()
		$("#totalPriceDisplay").val(totalMoney)
	}

})

//Custom design form example
$(".tab-wizard").steps({
	headerTag: "h6",
	bodyTag: "section",
	transitionEffect: "fade",
	titleTemplate: '<span class="step">#index#</span> #title#',
	labels: {
		finish: "Submit"
	},
	onFinished: function (event, currentIndex) {
		swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

	}
});

// var form = $(".validation-wizard").show();

// $(".validation-wizard").steps({
// 	headerTag: "h6",
// 	bodyTag: "section",
// 	transitionEffect: "fade",
// 	titleTemplate: '<span class="step">#index#</span> #title#',
// 	labels: {
// 		finish: "Submit"
// 	},
// 	onStepChanging: function (event, currentIndex, newIndex) {
// 		return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
// 	},
// 	onFinishing: function (event, currentIndex) {
// 		return form.validate().settings.ignore = ":disabled", form.valid()
// 	},
// 	onFinished: function (event, currentIndex) {
// 		swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
// 	}
// }), $(".validation-wizard").validate({
// 	ignore: "input[type=hidden]",
// 	errorClass: "text-danger",
// 	successClass: "text-success",
// 	highlight: function (element, errorClass) {
// 		$(element).removeClass(errorClass)
// 	},
// 	unhighlight: function (element, errorClass) {
// 		$(element).removeClass(errorClass)
// 	},
// 	errorPlacement: function (error, element) {
// 		error.insertAfter(element)
// 	},
// 	rules: {
// 		email: {
// 			email: !0
// 		}
// 	}
// })