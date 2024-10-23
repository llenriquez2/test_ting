<?php echo view('header', ['title' => 'Codeigniter 4 CRUD Jquery Ajax']); ?>
<div class="container-fluid"> <!-- Use container-fluid for full width -->
    <div class="row align-items-center">
            <div class="col-lg-10">
                <h2 style="color: #FEF9F2;" >MediTrack: Inventory </h2>
            </div>
            <div class="col-lg-2 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add New Inventory
                </button>
                
            </div> 
           
    </div>
    <div class="row align-items">

        <div class="col-lg-12 text-end">
                <a href="<?= base_url('inventory/exportCSV'); ?>" class="btn btn-success">Export to CSV</a>
                <a href="<?= site_url('inventory/exportPDF'); ?>" class="btn btn-danger">Export to PDF</a>

        </div>
        

    </div>

 
    <!-- Use the full width of the screen -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped w-100" id="inventoryTable">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Machine ID</th>
                    <th>Machine Type</th>
                    <th>Manufacturer</th>
                    <th>Model Number</th>
                    <th>Assigned Technician</th>

                    <th>Date of Deployment</th>
                    <th>Location / Department</th>
                    <th>Status</th>
                    <th>Service Due Date</th>
                    <th>Condition</th>
                    <th>Notes</th>
                    <th>Updated by</th>

                    <th>Action</th>
                </tr>
            </thead>  
            <tbody>
             <?php
            foreach($inventory_detail as $row){
            ?>
            <tr id="<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['machine_id']; ?></td>
                <td><?php echo $row['machine_type']; ?></td>
                <td><?php echo $row['manufacturer']; ?></td>
                <td><?php echo $row['model_number']; ?></td>
                <td><?php echo $row['assigned_tech']; ?></td>
                <td><?php echo $row['date_of_deployment']; ?></td>
                <td><?php echo $row['location_department']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['service_due_date']; ?></td>
                <td><?php echo $row['condition']; ?></td>

                <td><?php echo $row['notes']; ?></td>
                <td><?php echo $row['updated_by']; ?></td>


                <td>
                <a data-id="<?php echo $row['id']; ?>" class="btn btn-primary btnEdit">Edit</a>
                <a data-id="<?php echo $row['id']; ?>" class="btn btn-danger btnDelete">Delete</a>
                </td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>

    </div>   

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addInventory" name="addInventory" action="<?php echo site_url('inventory/store');?>" method="post">
            <div class="modal-body">
                    <div class="form-group mb-3" >
                        <label for="machine_id">Machine ID:</label>
                        <input type="text" class="form-control" id="machine_id" placeholder="Enter Machine ID" name="machine_id">
                    </div>
                    <div class="form-group mb-3">
                        <label for="machine_type">Machine Type:</label>
                        <input type="text" class="form-control" id="machine_type" placeholder="Enter Machine Type" name="machine_type">
                    </div>
                    <div class="form-group mb-3">
                        <label for="manufacturer">Manufacturer:</label>
                        <input type="text" class="form-control" id="manufacturer" placeholder="Enter Manufacturer" name="manufacturer">
                    </div>
                    <div class="form-group mb-3">
                        <label for="model_number">Model Number:</label>
                        <input type="text" class="form-control" id="model_number" placeholder="Enter Model Number" name="model_number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="assigned_tech">Assigned Technician:</label>
                        <input type="text" class="form-control" id="assigned_tech" placeholder="Enter Assigned Technician" name="assigned_tech">
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_of_deployment">Date Of Deployment:</label>
                        <input type="datetime-local" class="form-control" id="date_of_deployment" placeholder="Enter Date Of Deployment" name="date_of_deployment">
                    </div>
                    <div class="form-group mb-3">
                        <label for="location_department">Location/Department:</label>
                        <input type="text" class="form-control" id="location_department" placeholder="Enter Location/Department" name="location_department">
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
                        </select>
                    </div>


                    <div class="form-group mb-3">
                        <label for="service_due_date">Service Due Date:</label>
                        <input type="datetime-local" class="form-control" id="service_due_date" placeholder="Enter Service Due Date" name="service_due_date">
                    </div>
                    <div class="form-group mb-3">
                        <!-- <label for="condition">Condition:</label> -->
                        <!-- <input type="text" class="form-control" id="condition" placeholder="Enter Condition" name="condition"> -->
                  
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
                        <label for="machine_id">Machine ID:</label>
                        <input type="text" class="form-control" id="machine_id" placeholder="Enter Machine ID" name="machine_id">
                    </div>
                    <div class="form-group mb-3">
                        <label for="machine_type">Machine Type:</label>
                        <input type="text" class="form-control" id="machine_type" placeholder="Enter Machine Type" name="machine_type">
                    </div>
                    <div class="form-group mb-3">
                        <label for="manufacturer">Manufacturer:</label>
                        <input type="text" class="form-control" id="manufacturer" placeholder="Enter Manufacturer" name="manufacturer">
                    </div>
                    <div class="form-group mb-3">
                        <label for="model_number">Model Number:</label>
                        <input type="text" class="form-control" id="model_number" placeholder="Enter Model Number" name="model_number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="assigned_tech">Assigned Technician:</label>
                        <input type="text" class="form-control" id="assigned_tech" placeholder="Enter Assigned Technician" name="assigned_tech">
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_of_deployment">Date Of Deployment:</label>
                        <input type="datetime-local" class="form-control" id="date_of_deployment" placeholder="Enter Date Of Deployment" name="date_of_deployment">
                    </div>
                    <div class="form-group mb-3">
                        <label for="location_department">Location/Department:</label>
                        <input type="text" class="form-control" id="location_department" placeholder="Enter Location/Department" name="location_department">
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
                        </select>
                    </div>


                    <div class="form-group mb-3">
                        <label for="service_due_date">Service Due Date:</label>
                        <input type="datetime-local" class="form-control" id="service_due_date" placeholder="Enter Service Due Date" name="service_due_date">
                    </div>
                    <div class="form-group mb-3">
                        <!-- <label for="condition">Condition:</label> -->
                        <!-- <input type="text" class="form-control" id="condition" placeholder="Enter Condition" name="condition"> -->
                  
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
    </div>
 
</div>

<?php echo view('footer'); ?>
