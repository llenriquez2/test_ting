<?php echo view('header', ['title' => 'Codeigniter 4 CRUD Jquery Ajax']); ?>
<div class="container-fluid"> <!-- Use container-fluid for full width -->
    <div class="row align-items-center">
            <div class="col-lg-12">
                <h2 style="color: #FEF9F2;" >MediTrack: Inventory </h2>
            </div>
          
           
    </div>
    <br>
    <div class="row align-items mb-2" >
        <div class="col-lg-6 ">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add New Inventory
            </button>
            
        </div> 
        <div class="col-lg-6 text-end">
                <a href="<?= base_url('inventory/exportCSV'); ?>" class="btn btn-success">Export to CSV</a>
                <!-- <a href="</?= base_url('inventory/exportPDF'); ?>" class="btn btn-danger">Export to PDF</a> -->

        </div>
        

    </div>
    

 
    <!-- Use the full width of the screen -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped w-100" id="inventoryTable">
            <thead>
                <tr>
                    <th>Action</th>

                    <th>Ref Number</th>
                    <th>QTY</th>
                    <th>Unit</th>
                    <th>Item Description</th>
                    <th>Serial Number</th>
                    <th>Assigned Technician</th>

                    <th>Date of Deployment</th>
                    <th>Current Location</th>
                    <th>Status</th>
                    <!-- <th>Service Due Date</th> -->
                    <th>Condition</th>
                    <th>Notes</th>
                    <th>Tagged Ref Number</th>
                    <th>Updated by</th>

                </tr>
            </thead>  
            <tbody>
             <?php
            foreach($inventory_detail as $row){
            ?>
            <tr id="<?php echo $row['id']; ?>">
                
                <!-- <td>
                <a data-id="</?php echo $row['id']; ?>" class="btn btn-primary btnEdit">Edit</a>
                <a data-id="</?php echo $row['id']; ?>" class="btn btn-danger btnDelete">Delete</a>
                <a data-id="</?php echo $row['id']; ?>" class="btn btn-warning exportPDF">Export to PDF</a>
                </td> -->
                
                

                <td>
                    <div class="dropdown-container">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php if (!empty($row['tag_primary_ref'])): ?>
                                    <!-- If tag_primary_ref has a value, disable Edit and Export -->
                                    <li><a class="dropdown-item disabled">Edit</a></li>
                                    <li><a class="dropdown-item disabled">Export to PDF</a></li>
                                    <li><a class="dropdown-item disabled">Add Secondary Device</a></li>

                                <?php else: ?>
                                    <!-- If tag_primary_ref is empty, allow Edit and Export -->
                                    <li><a data-id="<?php echo $row['id']; ?>" data-ref-num="<?php echo $row['ref_num']; ?>" class="dropdown-item btn btnEdit">Edit</a></li>
                                    <li><a data-id="<?php echo $row['id']; ?>" data-ref-num="<?php echo $row['ref_num']; ?>" class="dropdown-item btn exportPDF">Export to PDF</a></li>
                                    <li><a data-id="<?php echo $row['id']; ?>" data-ref-num="<?php echo $row['ref_num']; ?>" class="dropdown-item btn addsecondDevice">Add Secondary Device</a></li>
                                
                                <?php endif; ?>
                                <!-- Delete option is always available -->
                                <li><a data-id="<?php echo $row['id']; ?>" class="dropdown-item btn btnDelete">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </td>
               

                <td style="<?php echo empty($row['tag_primary_ref']) ? 'color: green; font-weight: bold;' : ''; ?>">
                    <?php echo $row['ref_num']; ?>
                </td>

                <td><?php echo $row['qty']; ?></td>
                <td><?php echo $row['unit']; ?></td>
                <td><?php echo $row['item_description']; ?></td>
                <td><?php echo $row['serial_no']; ?></td>
                <td><?php echo $row['assigned_tech']; ?></td>
                <td><?php echo $row['date_deployed']; ?></td>
                <td><?php echo $row['current_location']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['condition']; ?></td>
                <td><?php echo $row['notes']; ?></td>
                <td><?php echo $row['tag_primary_ref']; ?></td>

                <td><?php echo $row['updated_by']; ?></td>


               
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>

    </div>   

    <!-- <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addInventory" name="addInventory" action="</?php echo site_url('inventory/store');?>" method="post">
                <div class="modal-body">


                      

                       <?php 
                        
                            function generateReferenceNumber() {
                                // Get the current date and time in the format YYYYMMDDHHMMSS
                                $dateTime = date('mdHi');
                                
                                // Generate a random 6-digit number, padded with zeros if needed
                                $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                
                                // Concatenate the date and time with the random number
                                $referenceNumber = $dateTime . $randomNumber;
                                
                                return $referenceNumber;
                            }

                            // Generate the reference number
                            $refNum = generateReferenceNumber();
                        ?>
                       
                        <div class="form-group mb-3" >
                            <label for="qty" >QTY:</label>
                            <input type="number" class="form-control" id="qty" step="1" placeholder="Enter Quantity" name="qty" >
                        </div>


                        <div class="form-group mb-3 " hidden>
                            <label for="ref_num">Reference Number:</label>
                            <input type="text" class="form-control" id="ref_num" value ="<?php echo $refNum; ?>" placeholder="Enter Reference Number" name="ref_num" hidden>
                        </div>

                        <div class="form-group mb-3">
                            <label for="unit">Unit Type:</label>
                            <select class="form-select" id="unit" name="unit" aria-label="unit">
                                <option value= "" disabled selected>Open to select value</option>
                                <option value="PC/S">PC/S</option>
                                <option value="SET/S">SET/S</option>
                            
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="item_description">Item Description:</label>
                            <input type="text" class="form-control" id="item_description" placeholder="Item Description" name="item_description">
                        </div>
                        <div class="form-group mb-3">
                            <label for="serial_no">Enter Serial Number:</label>
                            <input type="text" class="form-control" id="serial_no" placeholder="Enter Serial Number" name="serial_no">
                        </div>
                        <div class="form-group mb-3">
                            <label for="assigned_tech">Assigned Technician:</label>
                            <input type="text" class="form-control" id="assigned_tech" placeholder="Enter Assigned Technician" name="assigned_tech">
                        </div>
                        <div class="form-group mb-3">
                            <label for="date_deployed">Date Of Deployment:</label>
                            <input type="datetime-local" class="form-control" id="date_deployed" placeholder="Enter Date Of Deployment" name="date_deployed">
                        </div>
                        <div class="form-group mb-3">
                            <label for="current_location">Current Location:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location">
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="status">Status:</label>
                            <select class="form-select" id="status" name="status" aria-label="status">
                                <option value= "" disabled selected>Open to select value</option>
                                <option value="Active">Active</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                                <option value="Deployed">Deployed</option>
                                <option value="In Stock">In Stock</option>

                            </select>
                        </div>


                      
                        <div class="form-group mb-3">
                        
                            <label for="condition">Condition:</label>
                                <select class="form-select" id="condition" name="condition" aria-label="condition">
                                    <option value= "" disabled selected>Open to select value</option>
                                    <option value="New">New</option>
                                    <option value="Used">Used</option>
                                    <option value="Repaired">Repaired</option>
                                </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">Notes:</label>
                            <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Enter Notes"></textarea>
                        </div>

                       


                        
                    
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </form>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addInventory" name="addInventory" action="<?php echo site_url('inventory/store');?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="qty">QTY:</label>
                                <input type="number" class="form-control" id="qty" step="1" placeholder="Enter Quantity" name="qty">
                            </div>

                            <div class="col-md-2 mb-3" hidden>
                                <label for="ref_num">Reference Number:</label>
                                <input type="text" class="form-control" id="ref_num" value="<?php echo $refNum; ?>" name="ref_num" hidden>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="unit">Unit Type:</label>
                                <select class="form-select" id="unit" name="unit">
                                    <option value="" disabled selected>Open to select value</option>
                                    <option value="PC/S">PC/S</option>
                                    <option value="SET/S">SET/S</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="item_description">Item Description:</label>
                                <input type="text" class="form-control" id="item_description" placeholder="Item Description" name="item_description">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="serial_no">Serial Number:</label>
                                <input type="text" class="form-control" id="serial_no" placeholder="Enter Serial Number" name="serial_no">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="assigned_tech">Assigned Technician:</label>
                                <input type="text" class="form-control" id="assigned_tech" placeholder="Enter Assigned Technician" name="assigned_tech">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="date_deployed">Deployment Date:</label>
                                <input type="datetime-local" class="form-control" id="date_deployed" name="date_deployed">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="current_location">Location:</label>
                                <input type="text" class="form-control" id="current_location" placeholder="Enter Location" name="current_location">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="status">Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="" disabled selected>Open to select value</option>
                                    <option value="Active">Active</option>
                                    <option value="Under Maintenance">Under Maintenance</option>
                                    <option value="Deployed">Deployed</option>
                                    <option value="In Stock">In Stock</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="condition">Condition:</label>
                                <select class="form-select" id="condition" name="condition">
                                    <option value="" disabled selected>Open to select value</option>
                                    <option value="New">New</option>
                                    <option value="Used">Used</option>
                                    <option value="Repaired">Repaired</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="notes">Notes:</label>
                                <textarea class="form-control" id="notes" name="notes" rows="1" placeholder="Enter Notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


 
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Update Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateInventory" name="updateInventory" action="<?php echo site_url('inventory/update');?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="hdnInventoryId" id="hdnInventoryId"/>
                       
                    <div class="form-group mb-3" >
                        <label for="qty" >QTY:</label>
                        <input type="number" class="form-control" id="qty" step="1" placeholder="Enter Quantity" name="qty" >
                    </div>
                    <!-- <div class="form-group mb-3">
                        <label for="unit">Unit Type:</label>
                        <input type="text" class="form-control" id="unit" placeholder="Enter Unit Type" name="unit">
                    </div> -->

                    <div class="form-group mb-3">
                        <label for="unit">Unit Type:</label>
                        <select class="form-select" id="unit" name="unit" aria-label="unit">
                            <option value= "" disabled selected>Open to select value</option>
                            <option value="PC/S">PC/S</option>
                            <option value="SET/S">SET/S</option>
                          
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="item_description">Item Description:</label>
                        <input type="text" class="form-control" id="item_description" placeholder="Item Description" name="item_description">
                    </div>
                    <div class="form-group mb-3">
                        <label for="serial_no">Enter Serial Number:</label>
                        <input type="text" class="form-control" id="serial_no" placeholder="Enter Serial Number" name="serial_no">
                    </div>
                    <div class="form-group mb-3">
                        <label for="assigned_tech">Assigned Technician:</label>
                        <input type="text" class="form-control" id="assigned_tech" placeholder="Enter Assigned Technician" name="assigned_tech">
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_deployed">Date Of Deployment:</label>
                        <input type="datetime-local" class="form-control" id="date_deployed" placeholder="Enter Date Of Deployment" name="date_deployed">
                    </div>
                    <div class="form-group mb-3">
                        <label for="current_location">Current Location:</label>
                        <input type="text" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location">
                    </div>

                    <!-- <div class="form-group">
                        <label for="status">Status:</label>
                        <input type="text" class="form-control" id="status" placeholder="Enter Status" name="status">
                    </div> -->
                    
                     <div class="form-group mb-3">
                        <label for="status">Status:</label>
                        <select class="form-select" id="status" name="status" aria-label="status">
                            <option value= "" disabled selected>Open to select value</option>
                            <option value="Active">Active</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                            <option value="Deployed">Deployed</option>
                            <option value="In Stock">In Stock</option>

                        </select>
                    </div>


                    <!-- <div class="form-group mb-3">
                        <label for="service_due_date">Service Due Date:</label>
                        <input type="datetime-local" class="form-control" id="service_due_date" placeholder="Enter Service Due Date" name="service_due_date">
                    </div> -->
                    <div class="form-group mb-3">
                    
                        <label for="condition">Condition:</label>
                            <select class="form-select" id="condition" name="condition" aria-label="condition">
                                <option value= "" disabled selected>Open to select value</option>
                                <option value="New">New</option>
                                <option value="Used">Used</option>
                                <option value="Repaired">Repaired</option>
                            </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="notes">Notes:</label>
                        <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Enter Notes"></textarea>
                    </div>


                    <div class="form-group mb-3">
                        <label for="childSerialNumbers">Associated Serial Numbers:</label>
                        <select class="form-select" id="childSerialNumbers" name="child_serial_numbers[]" multiple>
                            <!-- Options will be populated by AJAX -->
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            </div>
        </div>
    </div>
 

    <!-- Secondary Modal -->          
    <div class="modal fade" id="secondaryAddModal" tabindex="-1" aria-labelledby="ModalLabelSecondary" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabelSecondary">Add New Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addInventorySecondary" name="addInventorySecondary" action="<?php echo site_url('inventory/store');?>" method="post">
                <div class="modal-body">


        

                        
                        <div class="form-group mb-3" >
                            <label for="qty" >QTY:</label>
                            <input type="number" class="form-control" id="qty" step="1" placeholder="Enter Quantity" name="qty" >
                        </div>


                        <div class="form-group mb-3 " hidden>
                            <label for="ref_num">Reference Number:</label>
                            <input type="text" class="form-control" id="ref_num" value ="<?php echo $refNum; ?>" placeholder="Enter Reference Number" name="ref_num" hidden>
                        </div>

                        <div class="form-group mb-3">
                            <label for="unit">Unit Type:</label>
                            <select class="form-select" id="unit" name="unit" aria-label="unit">
                                <option value= "" disabled selected>Open to select value</option>
                                <option value="PC/S">PC/S</option>
                                <option value="SET/S">SET/S</option>
                            
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="item_description">Item Description:</label>
                            <input type="text" class="form-control" id="item_description" placeholder="Item Description" name="item_description">
                        </div>
                        <div class="form-group mb-3">
                            <label for="serial_no">Enter Serial Number:</label>
                            <input type="text" class="form-control" id="serial_no" placeholder="Enter Serial Number" name="serial_no">
                        </div>
                        <div class="form-group mb-3">
                            <label for="assigned_tech">Assigned Technician:</label>
                            <input type="text" class="form-control" id="assigned_tech" placeholder="Enter Assigned Technician" name="assigned_tech">
                        </div>
                        <div class="form-group mb-3">
                            <label for="date_deployed">Date Of Deployment:</label>
                            <input type="datetime-local" class="form-control" id="date_deployed" placeholder="Enter Date Of Deployment" name="date_deployed">
                        </div>
                        <div class="form-group mb-3">
                            <label for="current_location">Current Location:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location">
                        </div>

                        <!-- <div class="form-group">
                            <label for="status">Status:</label>
                            <input type="text" class="form-control" id="status" placeholder="Enter Status" name="status">
                        </div> -->
                        
                        <div class="form-group mb-3">
                            <label for="status">Status:</label>
                            <select class="form-select" id="status" name="status" aria-label="status">
                                <option value= "" disabled selected>Open to select value</option>
                                <option value="Active">Active</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                                <option value="Deployed">Deployed</option>
                                <option value="In Stock">In Stock</option>

                            </select>
                        </div>


                        <!-- <div class="form-group mb-3">
                            <label for="service_due_date">Service Due Date:</label>
                            <input type="datetime-local" class="form-control" id="service_due_date" placeholder="Enter Service Due Date" name="service_due_date">
                        </div> -->
                        <div class="form-group mb-3">
                        
                            <label for="condition">Condition:</label>
                                <select class="form-select" id="condition" name="condition" aria-label="condition">
                                    <option value= "" disabled selected>Open to select value</option>
                                    <option value="New">New</option>
                                    <option value="Used">Used</option>
                                    <option value="Repaired">Repaired</option>
                                </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">Notes:</label>
                            <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Enter Notes"></textarea>
                        </div>

                       

                        <div class="form-group mb-3">
                            <label for="tag_primary_ref">Tagged Primary Ref:</label>
                            <input type="tag_primary_ref" class="form-control" id="tag_primary_ref" placeholder="Enter Parent Ref#" name="tag_primary_ref">
                        
                        </div>

                        
                    
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </form>
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this inventory item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php echo view('footer'); ?>
