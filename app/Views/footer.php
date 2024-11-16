</div>
<script src= "public/js/particles.js"></script>
<script src= "public/js/particles.min.js"></script>
<script src= "public/js/app.js"></script>
<script>
$(document).ready(function () {
    $('#inventoryTable').DataTable(

        { "pageLength": 10,  // Set default number of rows per page
          "lengthMenu": [5, 10, 25, 50, 100],  // Optional: Define other page length options
          "scrollY": "600px", // Enable vertical scrolling and set height
          "scrollX": true, // Enable horizontal scrolling
          "scrollCollapse": true, // Collapse the table height if fewer records
          "autoWidth": false, // Disable auto column width calculation
            columnDefs: [
                // { width: "50px", targets: 0 },

                { width: "50px", targets: 0 }, // Width for first column
                { width: "130px", targets: 1 }, // Width for second column
                { width: "120px", targets: 2 },  // Width for third column
                { width: "100px", targets: 3 },  // Width for third column

                { width: "180px", targets: 4 },  // Width for third column
                { width: "160px", targets: 5 },

                { width: "160px", targets: 6 },  // Width for third column
                { width: "180px", targets: 7 },
                { width: "150px", targets: 8 }, // STATUS
                { width: "150px", targets: 9 }, // Width for second column
                { width: "100px", targets: 10 }, // Width for second column
                { 

                    width: "300px", 
                    targets: 11, 
                    render: function(data, type, row) {
                        return data.replace(/\n/g, "<br>"); // Ensure line breaks are rendered in the table
                    }
                 }, // Width for second column
                 { width: "180px", targets: 12 }, // Width for second column

                 { 

                    width: "300px", 
                    targets: 13, 
                    render: function(data, type, row) {
                        return data.replace(/\n/g, "<br>"); // Ensure line breaks are rendered in the table
                    }
                 },


                //  "headerCallback": function(thead, data, start, end, display) {
                //     $(thead).find('th').css({
                //         'text-align': 'center',
                //         'vertical-align': 'middle'
                //     });
                //  }
                        


                // Add more as needed
            ]
        }   

       
    );

    // Add student form validation and AJAX submission
    $("#addInventory").validate({
        rules: {
            qty: "required",
            unit: "required",
            item_description: "required",
            serial_no: "required",
            assigned_tech: "required",

            // date_deployed: "required",
            current_location: "required",
            status: "required",
            // service_due_date: "required",
            condition: "required",
            notes: "required"

        },
        submitHandler: function(form) {
            var form_action = $("#addInventory").attr("action");
            $.ajax({
                data: $('#addInventory').serialize(),
                url: form_action,
                type: "POST",
                dataType: 'json',
                success: function (res) {

                      // Changed from res.success to res.status
                        var updatedByFormatted = res.data.updated_by ? res.data.updated_by.replace(/\n/g, "<br>") : ''; // Handle line breaks
                        var inventory = '<tr id="'+ res.data.id +'">';


                        // inventory += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> <a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a> <a data-id="' + res.data.id + '" class="btn btn-warning exportPDF">Export to PDF</a></td>';
                    
                        // Dropdown structure for action buttons
                        inventory += '<td>';
                        inventory += '<div class="dropdown">';
                        inventory += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton'+ res.data.id +'" data-bs-toggle="dropdown" aria-expanded="false"></button>';
                        inventory += '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'+ res.data.id +'">';
                        inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnEdit">Edit</a></li>';
                        inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
                        // inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
                       
                        inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnDelete">Delete</a></li>';

                        inventory += '</ul>';
                        inventory += '</div>';
                        inventory += '</td>';
                    
                    
                        inventory += '<td>' + res.data.ref_num+ '</td>';
                        inventory += '<td>' + res.data.qty+ '</td>';
                        inventory += '<td>' + res.data.unit+ '</td>';
                        inventory += '<td>' + res.data.item_description+ '</td>';
                        inventory += '<td>' + res.data.serial_no+ '</td>';
                        inventory += '<td>' + res.data.assigned_tech+ '</td>';

                        inventory += '<td>' + res.data.date_deployed+ '</td>';
                        inventory += '<td>' + res.data.current_location+ '</td>';
                        inventory += '<td>' + res.data.status+ '</td>';
                        // inventory += '<td>' + res.data.service_due_date+ '</td>';
                        inventory += '<td>' + res.data.condition+ '</td>';
                        inventory += '<td>' + res.data.notes+ '</td>';

                        // inventory += '<td>' + (res.data.updated_by || '') + '</td>';

                        inventory += '<td>' + updatedByFormatted + '</td>'; // Ensure formatting is applied


                        // inventory += '</tr>';
                        $('#inventoryTable tbody').prepend(inventory);
                        $('#addInventory')[0].reset();
                        $('#addModal').modal('hide');

                        // Redraw the DataTable
                        //  inventoryTable.draw();

                         location.reload(); // This will reload the entire page
                  
                }
            });
        }
    });

    // Edit student logic...
    $('body').on('click', '.btnEdit', function () {

        var inventory_id = $(this).attr('data-id');
        var parentRefNum = $(this).attr('data-ref-num'); // Get the ref_num directly from data attribute

        console.log("Parent ref_num: " + parentRefNum); 
        $.ajax({
            url: 'inventory/edit/'+inventory_id,
            type: "GET",
            dataType: 'json',
            success: function (res) {
            
            
                $('#updateModal').modal('show');
                $('#updateInventory #hdnInventoryId').val(res.data.id);


                $('#updateInventory #qty').val(res.data.qty);
                $('#updateInventory #unit').val(res.data.unit);
                $('#updateInventory #item_description').val(res.data.item_description);
                $('#updateInventory #serial_no').val(res.data.serial_no);
                $('#updateInventory #assigned_tech').val(res.data.assigned_tech);

                $('#updateInventory #date_deployed').val(res.data.date_deployed);
                $('#updateInventory #current_location').val(res.data.current_location);
                $('#updateInventory #status').val(res.data.status);
                // $('#updateInventory #service_due_date').val(res.data.service_due_date);
                $('#updateInventory #condition').val(res.data.condition);
                $('#updateInventory #notes').val(res.data.notes);
                // $('#updateInventory #notes').val(res.data.notes);



                        //added new
                       
                            $.ajax({
                                url: '<?= site_url("inventory/getChildSerialNumbers") ?>',
                                type: 'GET',
                                data: { ref_num: parentRefNum },
                                success: function (data) {
                                    // Clear previous options
                                    $('#childSerialNumbers').empty();
                                    
                                    // Populate the select with child serial numbers
                                    data.forEach(function (serial) {
                                        $('#childSerialNumbers').append(new Option(serial, serial));
                                    });
                                }
                            });
                       

                        //added new
            }
        });
    });

    // Update student form validation and AJAX submission
    $("#updateInventory").validate({
        rules: {
          
            qty: "required",
            unit: "required",
            item_description: "required",
            serial_no: "required",
            assigned_tech: "required",
            current_location: "required",
            status: "required",
            condition: "required",
            notes: "required"


        },
        submitHandler: function(form) {
            var form_action = $("#updateInventory").attr("action");
            $.ajax({
                data: $('#updateInventory').serialize(),
                url: form_action,
                type: "POST",
                dataType: 'json',
                success: function (res) {

                    var updatedByFormatted = res.data.updated_by ? res.data.updated_by.replace(/\n/g, "<br>") : ''; // Handle line breaks
          
                    // DROP DOWN
                    var inventory = '<td>';
                    inventory += '<div class="dropdown">';
                    inventory += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + res.data.id + '" data-bs-toggle="dropdown" aria-expanded="false"></button>';
                    inventory += '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + res.data.id + '">';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnEdit">Edit</a></li>';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnDelete">Delete</a></li>';
                   
                    inventory += '</ul>';
                    inventory += '</div>';
                    inventory += '</td>';

                     
                    // Followed by the ID and other fields
                    inventory += '<td>' + res.data.ref_num + '</td>';
                   
                    inventory += '<td>' + res.data.qty+ '</td>';
                    inventory += '<td>' + res.data.unit+ '</td>';
                    inventory += '<td>' + res.data.item_description+ '</td>';
                    inventory += '<td>' + res.data.serial_no+ '</td>';
                    inventory += '<td>' + res.data.assigned_tech+ '</td>';
                    inventory += '<td>' + res.data.date_deployed+ '</td>';
                    inventory += '<td>' + res.data.current_location+ '</td>';
                    inventory += '<td>' + res.data.status+ '</td>';
                    // inventory += '<td>' + res.data.service_due_date+ '</td>';
                    inventory += '<td>' + res.data.condition+ '</td>';
                    inventory += '<td>' + res.data.notes+ '</td>';

                    // inventory += '<td>' + (res.data.updated_by || '') + '</td>'; 
                    inventory += '<td>' + updatedByFormatted + '</td>'; // Ensure formatting is applied

                    // inventory += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> <a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a></td>' + res.data.id + '" class="btn btn-danger exportPDF">Export to PDF</a></td>';
                    $('#inventoryTable tbody #'+ res.data.id).html(inventory);
                    $('#updateInventory')[0].reset();
                    $('#updateModal').modal('hide');

                     // Redraw the DataTable
                    //  inventoryTable.draw();

                       // After handling the response, refresh the page
                     location.reload(); // This will reload the entire page
                }
            });
        }
    });


    //added new
    // $("#updateInventory").validate({
    //     rules: {
    //         qty: "required",
    //         unit: "required",
    //         item_description: "required",
    //         serial_no: "required",
    //         assigned_tech: "required",
    //         current_location: "required",
    //         status: "required",
    //         condition: "required",
    //         notes: "required"
    //     },
    //     submitHandler: function(form) {
    //         var form_action = $("#updateInventory").attr("action");
    //         var selectedSerials = $('#childSerialNumbers').val(); // Capture selected serial_no values
    //         var data = $('#updateInventory').serializeArray();

    //         // Add selected serials to data array
    //         data.push({ name: 'selected_serials', value: JSON.stringify(selectedSerials) });

    //         $.ajax({
    //             data: data,
    //             url: form_action,
    //             type: "POST",
    //             dataType: 'json',
    //             success: function(res) {
    //                 if (res.status) {
    //                     var updatedByFormatted = res.data.updated_by ? res.data.updated_by.replace(/\n/g, "<br>") : '';

    //                     // Construct the inventory row HTML
    //                     var inventory = '<td>';
    //                     inventory += '<div class="dropdown">';
    //                     inventory += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + res.data.id + '" data-bs-toggle="dropdown" aria-expanded="false"></button>';
    //                     inventory += '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + res.data.id + '">';
    //                     inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnEdit">Edit</a></li>';
    //                     inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnDelete">Delete</a></li>';
    //                     inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
    //                     inventory += '</ul>';
    //                     inventory += '</div>';
    //                     inventory += '</td>';

    //                     // Add the other data columns
    //                     inventory += '<td>' + res.data.ref_num + '</td>';
    //                     inventory += '<td>' + res.data.qty + '</td>';
    //                     inventory += '<td>' + res.data.unit + '</td>';
    //                     inventory += '<td>' + res.data.item_description + '</td>';
    //                     inventory += '<td>' + res.data.serial_no + '</td>';
    //                     inventory += '<td>' + res.data.assigned_tech + '</td>';
    //                     inventory += '<td>' + res.data.date_deployed + '</td>';
    //                     inventory += '<td>' + res.data.current_location + '</td>';
    //                     inventory += '<td>' + res.data.status + '</td>';
    //                     inventory += '<td>' + res.data.condition + '</td>';
    //                     inventory += '<td>' + res.data.notes + '</td>';
    //                     inventory += '<td>' + updatedByFormatted + '</td>';

    //                     // Update the row in the table with new data
    //                     $('#inventoryTable tbody #' + res.data.id).html(inventory);

    //                     // Clear the form and close the modal
    //                     $('#updateInventory')[0].reset();
    //                     $('#updateModal').modal('hide');

    //                     // Optionally reload page if needed
    //                     // location.reload();
    //                 } else {
    //                     console.log("Update failed.");
    //                 }
    //             }
    //         });
    //     }
    // });

    //added new



    //added
    $(document).on('click', '.btnDelete', function() {
    const id = $(this).data('id'); // Get the ID from the button
    $('#confirmDeleteBtn').data('id', id); // Store the ID in the confirm button
    $('#deleteModal').modal('show'); // Show the delete confirmation modal
    });

    // Handle the delete action
    $('#confirmDeleteBtn').click(function() {
        const id = $(this).data('id'); // Get the ID from the confirm button
        $.ajax({
            url: '<?= site_url("inventory/delete/") ?>' + id, // Include the ID in the URL
            type: 'POST',
            success: function(response) {
                // Handle success
                if (response.success) {
                    $('#deleteModal').modal('hide'); // Hide the modal
                    $('#' + id).css('display', 'none'); // Hide the row instead of removing
                    alert('Item deleted successfully!'); // Notify the user
                } else {
                    alert('Failed to delete item.'); // Notify the user
                }
            },
            error: function() {
                alert('Error deleting item.'); // Notify the user
            }
        });
    });

    //added


  // Export inventory to PDF logic...
// $('body').on('click', '.exportPDF', function () {
//     var inventory_id = $(this).attr('data-id'); // Get the id of the row
//     // var machine_type = "</?= $machine_type ?>";
//     // Make an AJAX request to the exportPDF method with the row ID
//     $.ajax({
//         url: '</?= base_url('inventory/exportPDF/') ?>' + inventory_id, // Update the URL to include the row ID
//         method: "GET",
//         xhrFields: {
//             responseType: 'blob' // Important for handling binary file data
//         },
//         success: function (response) {
//             // Create a blob from the response
//             // var blob = new Blob([response], { type: 'application/pdf' });
//             // var link = document.createElement('a');
//             // link.href = window.URL.createObjectURL(blob);
//             // link.download = "Machine_Type_" + inventory_id + ".pdf";
//             // link.click();

//             // Create a blob from the response
//             var blob = new Blob([response], { type: 'application/pdf' });
//             var pdfUrl = window.URL.createObjectURL(blob);
            
//             // Open the PDF in a new browser tab
//             window.open(pdfUrl, '_blank');

//         },
//         error: function(xhr, status, error) {
//             alert('Failed to export PDF: ' + error);
//         }
//     });
// });



// $('body').on('click', '.exportPDF', function () {
//     var inventory_id = $(this).attr('data-id'); // Get the ID of the row
//     var ref_num = $(this).attr('data-ref-num'); // Get the ref_num of the row

//     // Open the URL directly, so the browser handles the PDF with the correct filename
//     // window.open('</?= base_url('inventory/exportPDF/') ?>' + inventory_id, '_blank');
//     window.location.href = '</?= base_url('inventory/exportPDF/') ?>' + inventory_id;
// });


$('body').on('click', '.exportPDF', function () {
    var inventory_id = $(this).data('id'); // Get the ID of the row
    var ref_num = $(this).data('ref-num'); // Get the ref_num of the row
    // $username = session()->get('username'); 
    var last_name = "<?php echo session()->get('last_name'); ?>";
    var first_name = "<?php echo session()->get('first_name'); ?>";
    var fullname= `${first_name}  ${last_name}`;
    // Redirect to the exportPDF function with both inventory_id and ref_num as URL parameters
    // console.log(last_name);
    window.location.href = '<?= base_url('inventory/exportPDF/') ?>' + inventory_id + '/' + ref_num + '/' + fullname;
});



   // Secondary modal
    // Show secondary modal on .addsecondDevice click
$(document).on('click', '.addsecondDevice', function() {
    $('#secondaryAddModal').modal('show');
});

// Add inventory form validation and AJAX submission
$("#addInventorySecondary").validate({
    rules: {
        qty: "required",
        unit: "required",
        item_description: "required",
        serial_no: "required",
        assigned_tech: "required",
        current_location: "required",
        status: "required",
        condition: "required",
        notes: "required",
        tag_primary_ref: "required"
    },
    submitHandler: function(form) {
        var form_action = $("#addInventorySecondary").attr("action");
        $.ajax({
            data: $('#addInventorySecondary').serialize(),
            url: form_action,
            type: "POST",
            dataType: 'json',
            success: function(res) {
                var updatedByFormatted = res.data.updated_by ? res.data.updated_by.replace(/\n/g, "<br>") : ''; // Handle line breaks
                var inventory = '<tr id="' + res.data.id + '">';

                // Dropdown structure for action buttons
                inventory += '<td>';
                inventory += '<div class="dropdown">';
                inventory += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + res.data.id + '" data-bs-toggle="dropdown" aria-expanded="false"></button>';
                inventory += '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + res.data.id + '">';
                inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnEdit">Edit</a></li>';
                inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
                inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnDelete">Delete</a></li>';
                inventory += '</ul>';
                inventory += '</div>';
                inventory += '</td>';

                inventory += '<td>' + res.data.ref_num + '</td>';
                inventory += '<td>' + res.data.qty + '</td>';
                inventory += '<td>' + res.data.unit + '</td>';
                inventory += '<td>' + res.data.item_description + '</td>';
                inventory += '<td>' + res.data.serial_no + '</td>';
                inventory += '<td>' + res.data.assigned_tech + '</td>';
                inventory += '<td>' + res.data.date_deployed + '</td>';
                inventory += '<td>' + res.data.current_location + '</td>';
                inventory += '<td>' + res.data.status + '</td>';
                inventory += '<td>' + res.data.condition + '</td>';
                inventory += '<td>' + res.data.notes + '</td>';
                inventory += '<td>' + updatedByFormatted + '</td>'; // Ensure formatting is applied

                $('#inventoryTable tbody').prepend(inventory);
                $('#addInventorySecondary')[0].reset();
                $('#secondaryAddModal').modal('hide');

                // Optional: Redraw the DataTable or reload the page
                location.reload(); // This will reload the entire page
            }
        });
    }
});





    
});
</script>

    
</body>
</html>
