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
                { width: "50px", targets: 0 }, // Width for first column
                { width: "100px", targets: 1 }, // Width for second column
                { width: "120px", targets: 2 },  // Width for third column
                { width: "100px", targets: 3 },  // Width for third column

                { width: "180px", targets: 4 },  // Width for third column
                { width: "160px", targets: 5 },

                { width: "160px", targets: 6 },  // Width for third column
                { width: "180px", targets: 7 },
                { width: "100px", targets: 8 }, // Width for second column
                { width: "150px", targets: 9 }, // Width for second column
                { width: "100px", targets: 10 }, // Width for second column
                { 

                    width: "300px", 
                    targets: 11, 
                    render: function(data, type, row) {
                        return data.replace(/\n/g, "<br>"); // Ensure line breaks are rendered in the table
                    }
                 }, // Width for second column
                { 

                    width: "300px", 
                    targets: 12, 
                    render: function(data, type, row) {
                        return data.replace(/\n/g, "<br>"); // Ensure line breaks are rendered in the table
                    }
                },
                { width: "130px", targets: 13 }

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
                    var inventory = '<tr id="'+res.data.id+'">';
                
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


                    inventory += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> <a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a></td>';
                    inventory += '</tr>';
                    $('#inventoryTable tbody').prepend(inventory);
                    $('#addInventory')[0].reset();
                    $('#addModal').modal('hide');
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
                    var inventory = '<td>' + res.data.id + '</td>';
                


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

                    inventory += '<td><a data-id="' + res.data.id + '" class="btn btn-primary btnEdit">Edit</a> <a data-id="' + res.data.id + '" class="btn btn-danger btnDelete">Delete</a></td>';
                    $('#inventoryTable tbody #'+ res.data.id).html(inventory);
                    $('#updateInventory')[0].reset();
                    $('#updateModal').modal('hide');
                }
            });
        }
    });

    // Delete student logic...
    $('body').on('click', '.btnDelete', function () {
        var inventory_id = $(this).attr('data-id');
        $.get('inventory/delete/'+inventory_id, function (data) {
            $('#inventoryTable tbody #'+ inventory_id).remove();
        });
    });

    
});
</script>
</body>
</html>
