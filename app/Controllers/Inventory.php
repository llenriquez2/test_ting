<?php 
  
namespace App\Controllers;
   
use CodeIgniter\Controller;
use App\Models\InventoryModel;
// use Dompdf\Dompdf;
// require_once APPPATH . 'Libraries/fpdf/fpdf.php';
require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
   
class Inventory extends Controller
{
   
    public function index()
    {    

        if (!auth()->loggedIn()){
            return redirect()->to("login")
                     ->with("message", "Please login first");
        }

        $model = new InventoryModel();
   
        // $data['inventory_detail'] = $model->orderBy('id', 'DESC')->findAll();
          
        // $data['inventory_detail'] = $model->where('active_status', 1)->orderBy('id', 'DESC')->findAll();
      
        // return view('list', $data);
        
            $data['inventory_detail'] = $model
            ->select([
                'id',
                'ref_num',
                'qty',
                'unit',
                'item_description',
                'serial_no',
                'assigned_tech',
                'date_deployed',
                'current_location',
                'status',
                'condition',
                'notes',
                'updated_by',
                'active_status',
                'tag_primary_ref'
            ])
            ->where('active_status', 1) // Filter for active items
            ->orderBy('id', 'DESC') // Order by ID descending
            ->findAll();
        


                // Get rows where `tag_primary_ref` matches `ref_num`
            // $data['related_inventory'] = $model
            // ->where('active_status', 1)
            // ->where('tag_primary_ref', 'ref_num') // This line needs to be dynamic in practice
            // ->findAll();


            return view('list', $data);

      
    }    
  
    // New method to get child details for a given parent ID
    // public function getChildSerials($refNum) {
    //     $model = new InventoryModel();
        
    //     // Retrieve all child entries related to the given parent `ref_num`
    //     $childEntries = $model->select('serial_no')
    //                           ->where('tag_primary_ref', $refNum)
    //                           ->findAll();
        
    //     return $this->response->setJSON($childEntries);
    // }


    public function getChildSerialNumbers()
    {
        $parentRefNum = $this->request->getGet('ref_num');

        // Initialize the model
        $model = new InventoryModel();
        
        // Query to fetch child serial numbers where tagged_prim_num matches the parent's ref_num
        $childRows = $model->select('serial_no')
                        ->where('tag_primary_ref', $parentRefNum)
                        ->where('active_status', 1)
                        ->findAll();

        // Extract serial numbers
        $serialNumbers = array_column($childRows, 'serial_no');

        return $this->response->setJSON($serialNumbers);
    }


    
   
    public function store()
    {  

        
        if (!auth()->loggedIn()){
            return redirect()->to("login")
                     ->with("message", "Please login first");
        }


        helper(['form', 'url']);
           
        $model = new InventoryModel();

         // Get the username from session
         $session = session();
         $username = $session->get('username');  
        //  $lastname = $session->get('last_name');  
          
        $data = [
            

            'qty' => $this->request->getVar('qty'),
            
            'ref_num' => $this->request->getVar('ref_num'),
            'unit' => $this->request->getVar('unit'),
            'item_description' => $this->request->getVar('item_description'),
            'serial_no' => $this->request->getVar('serial_no'),
            'assigned_tech' => $this->request->getVar('assigned_tech'),
            'date_deployed' => $this->request->getVar('date_deployed'),
            'current_location' => $this->request->getVar('current_location'),
            'status' => $this->request->getVar('status'),
            // 'service_due_date' => $this->request->getVar('service_due_date'),
            'condition' => $this->request->getVar('condition'),
            'notes' => $this->request->getVar('notes'),
            'tag_primary_ref' => $this->request->getVar('tag_primary_ref'),

            ];


        


         // Insert with username
        //  $save = $model->insert_data($data, $username); // Pass username to model
        //  if ($save != false) {
        //      $data = $model->where('id', $save)->first();
        //      echo json_encode(array("status" => true, 'data' => $data));
        //  } else {
        //      echo json_encode(array("status" => false, 'data' => $data));
        //  }

        $save = $model->insert_data($data, $username); // Pass username to model
        if ($save !== false) {
            echo json_encode(array("status" => true, 'data' => $save)); // Return full $save data
        } else {
            echo json_encode(array("status" => false, 'data' => $data));
        }
        
       

    }
   
    public function edit($id = null)
    {
     
    
        if (!auth()->loggedIn()){
            return redirect()->to("login")
                        ->with("message", "Please login first");
        }

        $model = new InventoryModel();
        //  $username = $session->get('username');  
        
        $data = $model->where('id', $id)->first();
        
        if($data){
                echo json_encode(array("status" => true , 'data' => $data));
            }else{
                echo json_encode(array("status" => false));
            }
    }
   

    public function update()
    {  
        
        if (!auth()->loggedIn()){
            return redirect()->to("login")
                     ->with("message", "Please login first");
        }
   
        helper(['form', 'url']);
           
        $model = new InventoryModel();
  
        $id = $this->request->getVar('hdnInventoryId');
        // $parentRefNum = $this->request->getVar('ref_num'); // Assuming `ref_num` is provided as hidden field
        // $selectedSerials = json_decode($this->request->getVar('selected_serials'), true);

        // Get the username from session
         $session = session();
         $username = $session->get('username'); 
  
         $data = [
          


            'qty' => $this->request->getVar('qty'),

            // 'ref_num' => $this->request->getVar('ref_num'),
            'unit' => $this->request->getVar('unit'),
            'item_description' => $this->request->getVar('item_description'),
            'serial_no' => $this->request->getVar('serial_no'),
            'assigned_tech' => $this->request->getVar('assigned_tech'),
            'date_deployed' => $this->request->getVar('date_deployed'),
            'current_location' => $this->request->getVar('current_location'),
            'status' => $this->request->getVar('status'),
            // 'service_due_date' => $this->request->getVar('service_due_date'),
            'condition' => $this->request->getVar('condition'),
            'notes' => $this->request->getVar('notes'),

           
            ];
  



        // Update with username
        $update = $model->update_data($id, $data, $username); // Pass username to model
        if ($update != false) {
            $data = $model->where('id', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }
   

    //added new
    // public function update()
    // {  
    //     if (!auth()->loggedIn()) {
    //         return redirect()->to("login")
    //                         ->with("message", "Please login first");
    //     }

    //     helper(['form', 'url']);
    //     $model = new InventoryModel();

    //     $id = $this->request->getVar('hdnInventoryId');
    //     $parentRefNum = $this->request->getVar('ref_num'); // Assuming `ref_num` is provided as hidden field
    //     $selectedSerials = json_decode($this->request->getVar('selected_serials'), true);

    //     // Gather other data
    //     $data = [
    //         'qty' => $this->request->getVar('qty'),
    //         'unit' => $this->request->getVar('unit'),
    //         'item_description' => $this->request->getVar('item_description'),
    //         'serial_no' => $this->request->getVar('serial_no'),
    //         'assigned_tech' => $this->request->getVar('assigned_tech'),
    //         'date_deployed' => $this->request->getVar('date_deployed'),
    //         'current_location' => $this->request->getVar('current_location'),
    //         'status' => $this->request->getVar('status'),
    //         'condition' => $this->request->getVar('condition'),
    //         'notes' => $this->request->getVar('notes')
    //     ];

    //     // Update parent inventory item
    //     $model->update($id, $data);

    //     // Update tag_primary_ref for selected children
    //     $model->whereIn('serial_no', $selectedSerials)
    //         ->set(['tag_primary_ref' => $parentRefNum])
    //         ->update();

    //     // Remove tag_primary_ref for unselected children
    //     $model->where('tag_primary_ref', $parentRefNum)
    //         ->whereNotIn('serial_no', $selectedSerials)
    //         ->set(['tag_primary_ref' => null]) // Or another value to "unassign"
    //         ->update();

    //     // Return response
    //     $data = $model->where('id', $id)->first();
    //     echo json_encode(["status" => true, 'data' => $data]);
    // }

    //added new
    





    public function delete($id)
    {

        if (!auth()->loggedIn()){
            return redirect()->to("login")
                     ->with("message", "Please login first");
        }


        $session = session();
        $username = $session->get('username'); // Retrieve username from session

        $model = new InventoryModel();

        // Retrieve the full row data from the inventory table
        // $data = $model->find($id);

          // Retrieve specific columns from the inventory table
        $data = $model->select('qty,
                                unit,
                                item_description,
                                serial_no,
                                assigned_tech,
                                date_deployed,
                                current_location,
                                status,
                                condition,
                                notes,
                                updated_by,
                                active_status')
                                ->find($id);

        // Check if the item exists
        if (!$data) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found.']);
        }

        // Call deactivate_item to update `active_status` and log the action
        if ($model->deactivate_item($id, $username, $data )) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to deactivate item.']);
        }
    }

    

    public function exportCSV()
    {

        if (!auth()->loggedIn()){
            return redirect()->to("login")
                     ->with("message", "Please login first");
        }


        $filename = 'inventory_' . date('Ymd') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // Get inventory data
        $inventoryModel = new \App\Models\InventoryModel();
        $inventoryData = $inventoryModel->findAll();

        // Open file in write mode
        $file = fopen('php://output', 'w');

        // Set CSV column headers (all headers but select specific ones to export)
        $header = array(
            "ID", 
            "REF NUMBER",
            "QTY", 
            "Unit", 
            "Item Description", 
            "Serial Number", 
            "Assigned Technician", 
            "Date of Deployment", 
            "Current Location", 
            "Status", 
            "Condition", 
            "Tagged Ref Number",
            "Notes", 
            "Updated By"
        );
        fputcsv($file, $header);

        // Insert selected data into CSV (you can modify to include more or fewer columns)
        foreach ($inventoryData as $row) {
            // Only add the columns you want to include in the CSV output
            $selectedData = [
                $row['id'],  
                $row['ref_num'],  
                $row['qty'],              // Machine ID
                $row['unit'],            // Machine Type
                $row['item_description'],            // Manufacturer
                $row['serial_no'],            // Model Number
                $row['assigned_tech'],           // Assigned Technician
                $row['date_deployed'],      // Date of Deployment
                $row['current_location'],     // Location/Department
                $row['status'],                  // Status
                $row['condition'],
                $row['tag_primary_ref'],               // Condition
                $row['notes'],                   // Notes
                $row['updated_by']               // Updated By
            ];
            fputcsv($file, $selectedData);
        }

        fclose($file);
        exit;
    }






    // public function exportPDF()
    // {
    //     $dompdf = new Dompdf();
        
    //     // Get inventory data
    //     $inventoryModel = new \App\Models\InventoryModel();
    //     $inventoryData = $inventoryModel->findAll();

    //     // Build HTML content for the PDF
    //     $html = '<h2 style="text-align: center;">Inventory List</h2>';
    //     $html .= '<style>
    //                 table {
    //                     border-collapse: collapse;
    //                     width: 100%;
    //                     margin-bottom: 20px;
    //                 }
    //                 th, td {
    //                     border: 1px solid #000;
    //                     padding: 8px;
    //                     text-align: left;
    //                     font-size: 10px; /* Adjust font size as needed */
    //                 }
    //                 th {
    //                     background-color: #f2f2f2; /* Light gray background for headers */
    //                 }
    //                 @media print {
    //                     th {
    //                         position: sticky;
    //                         top: 0;
    //                         z-index: 10;
    //                     }
    //                 }
    //             </style>';
    //     $html .= '<table>';
    //     $html .= '<tr>
    //                 <th>ID</th>
    //                 <th>Machine ID</th>
    //                 <th>Machine Type</th>
    //                 <th>Manufacturer</th>
    //                 <th>Model Number</th>
    //                 <th>Assigned Technician</th>
    //                 <th>Date of Deployment</th>
    //                 <th>Location/Department</th>
    //                 <th>Status</th>
    //                 <th>Service Due Date</th>
    //                 <th>Condition</th>
    //                 <th>Notes</th>
    //                 <th>Updated By</th>
    //             </tr>';
        
    //     foreach ($inventoryData as $row) {

    //         $updatedByContent = nl2br(htmlspecialchars($row['updated_by'])); // Convert new lines and escape HTML

    //         $html .= '<tr>
    //                     <td>' . $row['id'] . '</td>
    //                     <td>' . $row['machine_id'] . '</td>
    //                     <td>' . $row['machine_type'] . '</td>
    //                     <td>' . $row['manufacturer'] . '</td>
    //                     <td>' . $row['model_number'] . '</td>
    //                     <td>' . $row['assigned_tech'] . '</td>
    //                     <td>' . $row['date_of_deployment'] . '</td>
    //                     <td>' . $row['location_department'] . '</td>
    //                     <td>' . $row['status'] . '</td>
    //                     <td>' . $row['service_due_date'] . '</td>
    //                     <td>' . $row['condition'] . '</td>
    //                     <td>' . $row['notes'] . '</td>
    //                     <td>' . $updatedByContent . '</td> <!-- Updated by content with line breaks -->
    //                 </tr>';
    //     }
        
    //     $html .= '</table>';

    //     // Load HTML content into Dompdf and generate PDF
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A4', 'landscape');
    //     $dompdf->render();

    //     // Output the generated PDF (downloadable)
    //     $dompdf->stream("inventory_" . date('Ymd') . ".pdf", array("Attachment" => 1));
    //     exit;
    // }



    // public function exportPDF($id, $ref_num)
    // {

    //     if (!auth()->loggedIn()){
    //         return redirect()->to("login")
    //                  ->with("message", "Please login first");
    //     }

    //     // require_once APPPATH . 'Libraries/FPDF/fpdf186.php';

    //     // Load the inventory model
    //     $inventoryModel = new \App\Models\InventoryModel();
        
    //     // Fetch inventory data using $id
    //     $data = $inventoryModel->find($id);
        
    //     // Check if the data exists
    //     if (!$data) {
    //         return $this->response->setStatusCode(404, 'Inventory item not found.');
    //     }

    //     // Initialize FPDF
    //     $pdf = new \FPDF();
    //     $pdf->AddPage();
        
    //     // Set the font for the PDF
    //     $pdf->SetFont('Arial', 'B', 16);
        
    //     // Title of the PDF
    //     $pdf->Cell(190, 10, 'Inventory Item Details', 0, 1, 'C');
        
    //     // Add some space
    //     $pdf->Ln(10);
        
    //     // Display the data
    //     $pdf->SetFont('Arial', '', 12);
    //     $pdf->Cell(50, 10, 'QTY: ' . $data['qty'], 0, 1);
    //     $pdf->Cell(50, 10, 'Unit: ' . $data['unit'], 0, 1);
    //     $pdf->Cell(50, 10, 'Item Description: ' . $data['item_description'], 0, 1);
    //     $pdf->Cell(50, 10, 'Serial Number: ' . $data['serial_no'], 0, 1);
    //     $pdf->Cell(50, 10, 'Assigned Tech: ' . $data['assigned_tech'], 0, 1);
    //     $pdf->Cell(50, 10, 'Date of Deployment: ' . $data['date_deployed'], 0, 1);
    //     $pdf->Cell(50, 10, 'Current Location: ' . $data['current_location'], 0, 1);
    //     $pdf->Cell(50, 10, 'Status: ' . $data['status'], 0, 1);
    //     $pdf->Cell(50, 10, 'Condition: ' . $data['condition'], 0, 1);
    //     // $pdf->Cell(50, 10, 'Notes: ' . $data['notes'], 0, 1);
        
    //      // Wrap the "Notes" field using MultiCell
    //     $pdf->Cell(50, 10, 'Notes:', 0, 1);
    //     $pdf->MultiCell(0, 10, $data['notes'], 0, 'L');  // 0 for full width, 10 for height, and 'L' for left-align

       
       

    //     // Sanitize the machine_type for use in the filename
    //     $serial_no = preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['serial_no']); 

    //     $filename = 'Inventory_' . $serial_no . '_' . $id . '.pdf';
    //     // Set the Content-Type and Content-Disposition headers for PDF viewing with a specific filename
    //     $this->response->setHeader('Content-Type', 'application/pdf');
    //     // $this->response->setHeader('Content-Disposition', 'inline; filename="Machine_Type_' . $filename . '.pdf"');
    //     $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
    //     // Output the PDF inline with the specified filename
    //     // $pdf->Output('I', 'Machine_Type_' . $filename . '.pdf'); 
    //     // Output the PDF for download
    //     $pdf->Output('D', $filename); // Use 'D' to force download with the filename

    //     exit;

    // }


    // New method for fetching child serial numbers for PDF export
    public function getChildSerialNumbersForPDF($ref_num)
    {
        $parentRefNum = $ref_num;

        // Initialize the model
        $model = new InventoryModel();

        // Query to fetch child serial numbers where tagged_prim_num matches the parent's ref_num
        $childRows = $model->select('qty, unit, serial_no')
                        ->where('tag_primary_ref', $parentRefNum)
                        ->where('active_status', 1)

                        ->findAll();

        // Extract serial numbers into an array
        // $serialNumbers = array_column($childRows, 'serial_no');

        // return $serialNumbers;  // Return as an array for PDF

        return $childRows;
    }


    // public function exportPDF($id, $ref_num)
    // {
    //     if (!auth()->loggedIn()) {
    //         return redirect()->to("login")
    //                         ->with("message", "Please login first");
    //     }

    //     // Load the inventory model
    //     $inventoryModel = new \App\Models\InventoryModel();

    //     // Fetch inventory data using $id
    //     $data = $inventoryModel->where('id', $id)->first();

    //     // Check if the data exists
    //     if (!$data) {
    //         return $this->response->setStatusCode(404, 'Inventory item not found.');
    //     }

    //     // Fetch child serial numbers for the given ref_num (use the new method for PDF)
    //     $childSerialNumbers = $this->getChildSerialNumbersForPDF($ref_num);  // Renamed method for PDF export

    //     // Initialize FPDF
    //     $pdf = new \FPDF();
    //     $pdf->AddPage();

    //     // Set the font for the PDF
    //     $pdf->SetFont('Arial', 'B', 16);

    //     // Title of the PDF
    //     $pdf->Cell(190, 10, 'Inventory Withdrawal Form', 0, 1, 'C');

    //     // Add some space
    //     $pdf->Ln(10);

    //     // Display the data
    //     $pdf->SetFont('Arial', '', 12);
    //     $pdf->Cell(50, 10, 'CLIENT: ' . $data['current_location'], 0, 1);
    //     $pdf->Cell(50, 10, 'QTY: ' . $data['qty'], 0, 1);
    //     $pdf->Cell(50, 10, 'Unit: ' . $data['unit'], 0, 1);
    //     $pdf->Cell(50, 10, 'Item Description: ' . $data['item_description'], 0, 1);
    //     $pdf->Cell(50, 10, 'Serial Number: ' . $data['serial_no'], 0, 1);
    //     $pdf->Cell(50, 10, 'Assigned Tech: ' . $data['assigned_tech'], 0, 1);
    //     $pdf->Cell(50, 10, 'Date of Deployment: ' . $data['date_deployed'], 0, 1);
    //     $pdf->Cell(50, 10, 'Current Location: ' . $data['current_location'], 0, 1);
    //     $pdf->Cell(50, 10, 'Status: ' . $data['status'], 0, 1);
    //     $pdf->Cell(50, 10, 'Condition: ' . $data['condition'], 0, 1);

    //     // Wrap the "Notes" field using MultiCell
    //     $pdf->Cell(50, 10, 'Notes:', 0, 1);
    //     $pdf->MultiCell(0, 10, $data['notes'], 0, 'L');  // 0 for full width, 10 for height, and 'L' for left-align

    //     // Display child serial numbers (if any)
    //     if (!empty($childSerialNumbers)) {
    //         $pdf->Ln(5); // Add some space
    //         $pdf->Cell(50, 10, 'Associated Child Serial Numbers:', 0, 1);
    //         foreach ($childSerialNumbers as $childSerial) {
    //             $pdf->Cell(50, 10, $childSerial, 0, 1);
    //         }
    //     } else {
    //         $pdf->Cell(50, 10, 'No associated child serial numbers.', 0, 1);
    //     }

    //     // Sanitize the serial number for use in the filename
    //     $serial_no = preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['serial_no']); 

    //     // Create a PDF filename based on the serial number and ID
    //     $filename = 'Withdrawal FORM_' . $serial_no . '_'. '.pdf';

    //     // Set the Content-Type and Content-Disposition headers for PDF viewing with a specific filename
    //     $this->response->setHeader('Content-Type', 'application/pdf');
    //     $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

    //     // Output the PDF for download
    //     $pdf->Output('D', $filename); // Use 'D' to force download with the filename

    //     exit;
    // }

    public function exportPDF($id, $ref_num, $fullname)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to("login")
                            ->with("message", "Please login first");
        }

     
    
        // Load the inventory model
        $inventoryModel = new \App\Models\InventoryModel();
    
        // Fetch inventory data using $id
        $data = $inventoryModel->where('id', $id)->first();
    
        // Check if the data exists
        if (!$data) {
            return $this->response->setStatusCode(404, 'Inventory item not found.');
        }
    
        // Fetch child serial numbers for the given ref_num
        $childDetails = $this->getChildSerialNumbersForPDF($ref_num);
    
        // Initialize FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
    
        // Set the font for the PDF
        $pdf->SetFont('Arial', 'B', 20);
    
        // Title of the PDF
        $pdf->Cell(190, 10, 'Inventory Withdrawal Form', 0, 1, 'C');
    
        // Add some space
        $pdf->Ln(10);
    
        // Table for client info
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(95, 6, 'CLIENT: ' . $data['current_location'], 1, 0);
        $pdf->Cell(95, 6, 'ADDRESS: ' . $data['current_location'], 1, 1);
        $pdf->Cell(95, 6, 'CLIENT P.O. NUMBER:', 1, 0);
        $pdf->Cell(95, 6, 'DATE NEEDED: ' . $data['date_deployed'], 1, 1);
    
        $pdf->Ln(5); // Space before table
    
        // Column headers for inventory items
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(10, 6, 'QTY', 1, 0, 'C');
        $pdf->Cell(20, 6, 'UNIT', 1, 0, 'C');
        $pdf->Cell(90, 6, 'ITEM DESCRIPTION', 1, 0, 'C');
        $pdf->Cell(70, 6, 'SERIAL NO.', 1, 1, 'C');
    
        // Row data for inventory items
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 6, $data['qty'], 1, 0, 'C');
        $pdf->Cell(20, 6, $data['unit'], 1, 0, 'C');
        // $pdf->MultiCell(90, 8, $data['item_description'], 1, 0, 'C');
        $pdf->MultiCell(90, 6, $data['item_description'], 1, 'L'); // Wrap text within 90-width cell
        $pdf->SetXY($pdf->GetX() + 120, $pdf->GetY() - 6); 
        $pdf->Cell(70, 6, $data['serial_no'], 1, 1, 'C');
    
        // Display associated child serial numbers, if any
        if (!empty($childDetails)) {
            foreach ($childDetails as $child) {
                $pdf->Cell(10, 6, $child['qty'], 1, 0, 'C');
                $pdf->Cell(20, 6, $child['unit'], 1, 0, 'C');
                $pdf->Cell(90, 6, '', 1, 0, 'C');
                $pdf->Cell(70, 6, $child['serial_no'], 1, 1, 'C');
                $pdf->Cell(190, 6, '------------------------------Nothing Follows------------------------------', 1, 1, 'C');

            }
        } else {
            $pdf->Cell(190, 6, '------------------------------Nothing Follows------------------------------', 1, 1, 'C');
        }
    
        $pdf->Ln(10); // Additional space for other sections if needed
    

        // Add the Prepared By, Released By, Delivered By, and Received By sections
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(95, 6, 'PREPARED BY', 1, 0, 'C');
        $pdf->Cell(95, 6, 'RELEASED BY', 1, 1, 'C');
        

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(95, 6, $fullname.'                            10/02/2024', 1, 0, 'C');
        $pdf->Cell(95, 6, '', 1, 1, 'C');  // Empty cell for RELEASED BY

        $pdf->Cell(95, 6, '______________________________  ______', 1, 0, 'C');
        $pdf->Cell(95, 6, '______________________________  ______', 1, 1, 'C');
        $pdf->Cell(95, 6, 'Signature Over Printed Name   Date', 1, 0, 'C');
        $pdf->Cell(95, 6, 'Signature Over Printed Name   Date', 1, 1, 'C');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(95, 6, 'DELIVERED BY', 1, 0, 'C');
        $pdf->Cell(95, 6, 'RECEIVED BY', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(95, 6, '', 1, 0, 'L');  // Empty cell for DELIVERED BY
        $pdf->Cell(95, 6, 'RONALD ROSAS                            07/16/2024',  1, 1, 'C');

        $pdf->Cell(95, 6, '______________________________  ______',  1, 0, 'C');
        $pdf->Cell(95, 6, '______________________________  ______',  1, 1, 'C');
        $pdf->Cell(95, 6, 'Signature Over Printed Name   Date', 1, 0, 'C');
        $pdf->Cell(95, 6, 'Signature Over Printed Name   Date', 1, 1, 'C');


        // Sanitize and create filename
        $serial_no = preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['serial_no']); 
        $filename = 'Withdrawal_FORM_' . $serial_no . '.pdf';
    
        // Set headers for PDF download
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
    
        // Output PDF
        $pdf->Output('D', $filename);
        exit;
    }
    


}
  
?>