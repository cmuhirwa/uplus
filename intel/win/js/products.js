//Datatables
$(function() {
    $('#myTable').DataTable();
    $(document).ready(function() {
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
    });
});

$('#example23').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});

$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary m-r-10');

//open model for input button
$(".editModalOpenBtn").on('click', function(){
    //check product details
    var prData = $(this).parents().eq(1).data()
    id = prData.product;
    prCode = prData.productcode;
    name = prData['name'];
    unit = prData.unit;

    $("#editProductIdInModal").html(prCode);
    $("#productNameEdit").val(name);
});

//open model for delete button
$(".deleteModalOpenBtn").on('click', function(){
    //check product details
    var prData = $(this).parents().eq(1).data()
    id = prData.product;
    prCode = prData.productcode;
    name = prData['name'];
    unit = prData.unit;

    $(".editProductIdInModal").html(prCode);
    $(".productNameReplace").html(name);
});

//productUpdate
$("#updateProductForm").on('submit', function(e){
    e.preventDefault()
    //checking the variables
    name = $("#productNameEdit").val();
    mainUnit = $("#mainMeasurementUnitInput").val();

    //change this
    $.post(apiLink, {action:'updateProduct', product:id, name: name, mainUnit:mainUnit, doneBy:currentUserId}, function(data){
        if(data.status){
            alert("Successfully edited product");

            setTimeout(function(){
                location.reload()
            }, 1000);
        }else{
            alert("Error "+data.msg)
        }
    })
})

//product delete
$("#deleteProductForm").on('submit', function(e){
    e.preventDefault()

    //change this
    $.post(apiLink, {action:'deleteProduct', product:id, doneBy:currentUserId}, function(data){
        if(data.status){
            alert("Successfully deleted product");

            setTimeout(function(){
                location.reload()
            }, 1000);
        }else{
            alert("Error "+data.msg)
        }
    })
});


//product addition
$("#addProductForm").on('submit', function(e){
    e.preventDefault();
    var name = $("#addProductName").val();
    var mainUnit = $("#addMainMeasurementUnitInput").val();
    var otherUnits = $("#addAlternativeMeasurementUnits").val();
    var productGroups = $("#addProductGroups").val();

    $.post(apiLink, {action:'addProduct', name:name, mainUnit:mainUnit, otherUnits:otherUnits, groups:productGroups, doneBy:currentUserId}, function(data){
        if(data.status){
            alert("Successfully added product");

            setTimeout(function(){
                location.reload()
            }, 1000);
        }else{
            alert("Error "+data.msg)
        }
    })

})