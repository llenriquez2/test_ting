</div>

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
                { width: "50px", targets: 0 },

                { width: "50px", targets: 1 }, // Width for first column
                { width: "100px", targets: 2 }, // Width for second column
                { width: "120px", targets: 3 },  // Width for third column
                { width: "100px", targets: 4 },  // Width for third column

                { width: "180px", targets: 5 },  // Width for third column
                { width: "160px", targets: 6 },

                { width: "160px", targets: 7 },  // Width for third column
                { width: "180px", targets: 8 },
                { width: "150px", targets: 9 }, // STATUS
                { width: "150px", targets: 10 }, // Width for second column
                { width: "100px", targets: 11 }, // Width for second column
                { 

                    width: "300px", 
                    targets: 12, 
                    render: function(data, type, row) {
                        return data.replace(/\n/g, "<br>"); // Ensure line breaks are rendered in the table
                    }
                 }, // Width for second column
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
            machine_id: "required",
            machine_type: "required",
            manufacturer: "required",
            model_number: "required",
            assigned_tech: "required",

            date_of_deployment: "required",
            location_department: "required",
            status: "required",
            service_due_date: "required",
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

                    var updatedByFormatted = res.data.updated_by ? res.data.updated_by.replace(/\n/g, "<br>") : ''; // Handle line breaks
                    var inventory = '<tr id="'+ res.data.id +'">';


                    // inventory += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> <a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a> <a data-id="' + res.data.id + '" class="btn btn-warning exportPDF">Export to PDF</a></td>';
                   
                    // Dropdown structure for action buttons
                    inventory += '<td>';
                    inventory += '<div class="dropdown">';
                    inventory += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton'+ res.data.id +'" data-bs-toggle="dropdown" aria-expanded="false"></button>';
                    inventory += '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'+ res.data.id +'">';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnEdit">Edit</a></li>';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnDelete">Delete</a></li>';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
                    inventory += '</ul>';
                    inventory += '</div>';
                    inventory += '</td>';
                   
                   
                    inventory += '<td>' + res.data.id+ '</td>';
                    inventory += '<td>' + res.data.machine_id+ '</td>';
                    inventory += '<td>' + res.data.machine_type+ '</td>';
                    inventory += '<td>' + res.data.manufacturer+ '</td>';
                    inventory += '<td>' + res.data.model_number+ '</td>';
                    inventory += '<td>' + res.data.assigned_tech+ '</td>';

                    inventory += '<td>' + res.data.date_of_deployment+ '</td>';
                    inventory += '<td>' + res.data.location_department+ '</td>';
                    inventory += '<td>' + res.data.status+ '</td>';
                    inventory += '<td>' + res.data.service_due_date+ '</td>';
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

                    //  location.reload(); // This will reload the entire page

                }
            });
        }
    });

    // Edit student logic...
    $('body').on('click', '.btnEdit', function () {

        var inventory_id = $(this).attr('data-id');
        $.ajax({
            url: 'inventory/edit/'+inventory_id,
            type: "GET",
            dataType: 'json',
            success: function (res) {
                $('#updateModal').modal('show');
                $('#updateInventory #hdnInventoryId').val(res.data.id);


                $('#updateInventory #machine_id').val(res.data.machine_id);
                $('#updateInventory #machine_type').val(res.data.machine_type);
                $('#updateInventory #manufacturer').val(res.data.manufacturer);
                $('#updateInventory #model_number').val(res.data.model_number);
                $('#updateInventory #assigned_tech').val(res.data.assigned_tech);

                $('#updateInventory #date_of_deployment').val(res.data.date_of_deployment);
                $('#updateInventory #location_department').val(res.data.location_department);
                $('#updateInventory #status').val(res.data.status);
                $('#updateInventory #service_due_date').val(res.data.service_due_date);
                $('#updateInventory #condition').val(res.data.condition);
                $('#updateInventory #notes').val(res.data.notes);
                // $('#updateInventory #notes').val(res.data.notes);


            }
        });
    });

    // Update student form validation and AJAX submission
    $("#updateInventory").validate({
        rules: {
          
            machine_id: "required",
            machine_type: "required",
            manufacturer: "required",
            model_number: "required",
            assigned_tech: "required",

            date_of_deployment: "required",
            location_department: "required",
            status: "required",
            service_due_date: "required",
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
                    // var inventory = '<td>' + res.data.id + '</td>';
                    // inventory += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> <a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a> <a data-id="' + res.data.id + '" class="btn btn-warning exportPDF">Export to PDF</a></td>';
                   
                      // Start with the buttons column
                    // var inventory = '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> ' +
                    //                 '<a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a> ' +
                    //                 '<a data-id="' + res.data.id + '" class="btn btn-warning exportPDF">Export to PDF</a></td>';

                    // DROP DOWN
                    var inventory = '<td>';
                    inventory += '<div class="dropdown">';
                    inventory += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton' + res.data.id + '" data-bs-toggle="dropdown" aria-expanded="false"></button>';
                    inventory += '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + res.data.id + '">';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnEdit">Edit</a></li>';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn btnDelete">Delete</a></li>';
                    inventory += '<li><a data-id="' + res.data.id + '" class="dropdown-item btn exportPDF">Export to PDF</a></li>';
                    inventory += '</ul>';
                    inventory += '</div>';
                    inventory += '</td>';

                     
                    // Followed by the ID and other fields
                    inventory += '<td>' + res.data.id + '</td>';
                   
                    inventory += '<td>' + res.data.machine_id+ '</td>';
                    inventory += '<td>' + res.data.machine_type+ '</td>';
                    inventory += '<td>' + res.data.manufacturer+ '</td>';
                    inventory += '<td>' + res.data.model_number+ '</td>';
                    inventory += '<td>' + res.data.assigned_tech+ '</td>';
                    inventory += '<td>' + res.data.date_of_deployment+ '</td>';
                    inventory += '<td>' + res.data.location_department+ '</td>';
                    inventory += '<td>' + res.data.status+ '</td>';
                    inventory += '<td>' + res.data.service_due_date+ '</td>';
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

    // Delete student logic...
    $('body').on('click', '.btnDelete', function () {
        var inventory_id = $(this).attr('data-id');
        $.get('inventory/delete/'+inventory_id, function (data) {
            $('#inventoryTable tbody #'+ inventory_id).remove();

             // Redraw the DataTable
             inventoryTable.draw();
        });
    });




  // Export inventory to PDF logic...
$('body').on('click', '.exportPDF', function () {
    var inventory_id = $(this).attr('data-id'); // Get the id of the row
    // var machine_type = "</?= $machine_type ?>";
    // Make an AJAX request to the exportPDF method with the row ID
    $.ajax({
        url: '<?= base_url('inventory/exportPDF/') ?>' + inventory_id, // Update the URL to include the row ID
        method: "GET",
        xhrFields: {
            responseType: 'blob' // Important for handling binary file data
        },
        success: function (response) {
            // Create a blob from the response
            // var blob = new Blob([response], { type: 'application/pdf' });
            // var link = document.createElement('a');
            // link.href = window.URL.createObjectURL(blob);
            // link.download = "Machine_Type_" + inventory_id + ".pdf";
            // link.click();

            // Create a blob from the response
            var blob = new Blob([response], { type: 'application/pdf' });
            var pdfUrl = window.URL.createObjectURL(blob);
            
            // Open the PDF in a new browser tab
            window.open(pdfUrl, '_blank');

        },
        error: function(xhr, status, error) {
            alert('Failed to export PDF: ' + error);
        }
    });
});



    
});
</script>
</body>
</html>
