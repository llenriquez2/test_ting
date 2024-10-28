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
   
        $data['inventory_detail'] = $model->orderBy('id', 'DESC')->findAll();
          
        return view('list', $data);
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
          
        $data = [
            

            'machine_id' => $this->request->getVar('machine_id'),
            'machine_type' => $this->request->getVar('machine_type'),
            'manufacturer' => $this->request->getVar('manufacturer'),
            'model_number' => $this->request->getVar('model_number'),
            'assigned_tech' => $this->request->getVar('assigned_tech'),
            'date_of_deployment' => $this->request->getVar('date_of_deployment'),
            'location_department' => $this->request->getVar('location_department'),
            'status' => $this->request->getVar('status'),
            'service_due_date' => $this->request->getVar('service_due_date'),
            'condition' => $this->request->getVar('condition'),
            'notes' => $this->request->getVar('notes'),


            ];


        // $save = $model->insert_data($data);
        // if($save != false)
        // {
        //     $data = $model->where('id', $save)->first();
        //     echo json_encode(array("status" => true , 'data' => $data));
        // }
        // else{
        //     echo json_encode(array("status" => false , 'data' => $data));
        // }


         // Insert with username
         $save = $model->insert_data($data, $username); // Pass username to model
         if ($save != false) {
             $data = $model->where('id', $save)->first();
             echo json_encode(array("status" => true, 'data' => $data));
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

         // Get the username from session
         $session = session();
         $username = $session->get('username'); 
  
         $data = [
          


            'machine_id' => $this->request->getVar('machine_id'),
            'machine_type' => $this->request->getVar('machine_type'),
            'manufacturer' => $this->request->getVar('manufacturer'),
            'model_number' => $this->request->getVar('model_number'),
            'assigned_tech' => $this->request->getVar('assigned_tech'),
            'date_of_deployment' => $this->request->getVar('date_of_deployment'),
            'location_department' => $this->request->getVar('location_department'),
            'status' => $this->request->getVar('status'),
            'service_due_date' => $this->request->getVar('service_due_date'),
            'condition' => $this->request->getVar('condition'),
            'notes' => $this->request->getVar('notes'),


            ];
  
        // $update = $model->update($id,$data);
        // if($update != false)
        // {
        //     $data = $model->where('id', $id)->first();
        //     echo json_encode(array("status" => true , 'data' => $data));
        // }
        // else{
        //     echo json_encode(array("status" => false , 'data' => $data));
        // }

        // Update with username
        $update = $model->update_data($id, $data, $username); // Pass username to model
        if ($update != false) {
            $data = $model->where('id', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }
   
    public function delete($id = null){

        if (!auth()->loggedIn()){
            return redirect()->to("login")
                     ->with("message", "Please login first");
        }


        $model = new InventoryModel();
        $delete = $model->where('id', $id)->delete();
        if($delete)
        {
           echo json_encode(array("status" => true));
        }else{
           echo json_encode(array("status" => false));
        }
    }


    public function exportCSV()
    {
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
            "Machine ID", 
            "Machine Type", 
            "Manufacturer", 
            "Model Number", 
            "Assigned Technician", 
            "Date of Deployment", 
            "Location/Department", 
            "Status", 
            "Service Due Date", 
            "Condition", 
            "Notes", 
            "Updated By"
        );
        fputcsv($file, $header);

        // Insert selected data into CSV (you can modify to include more or fewer columns)
        foreach ($inventoryData as $row) {
            // Only add the columns you want to include in the CSV output
            $selectedData = [
                $row['id'],                      // ID
                $row['machine_id'],              // Machine ID
                $row['machine_type'],            // Machine Type
                $row['manufacturer'],            // Manufacturer
                $row['model_number'],            // Model Number
                $row['assigned_tech'],           // Assigned Technician
                $row['date_of_deployment'],      // Date of Deployment
                $row['location_department'],     // Location/Department
                $row['status'],                  // Status
                $row['service_due_date'],        // Service Due Date
                $row['condition'],               // Condition
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



    public function exportPDF($id)
    {

        // require_once APPPATH . 'Libraries/FPDF/fpdf186.php';

        // Load the inventory model
        $inventoryModel = new \App\Models\InventoryModel();
        
        // Fetch inventory data using $id
        $data = $inventoryModel->find($id);
        
        // Check if the data exists
        if (!$data) {
            return $this->response->setStatusCode(404, 'Inventory item not found.');
        }

        // Initialize FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        
        // Set the font for the PDF
        $pdf->SetFont('Arial', 'B', 16);
        
        // Title of the PDF
        $pdf->Cell(190, 10, 'Inventory Item Details', 0, 1, 'C');
        
        // Add some space
        $pdf->Ln(10);
        
        // Display the data
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Machine ID: ' . $data['machine_id'], 0, 1);
        $pdf->Cell(50, 10, 'Machine Type: ' . $data['machine_type'], 0, 1);
        $pdf->Cell(50, 10, 'Manufacturer: ' . $data['manufacturer'], 0, 1);
        $pdf->Cell(50, 10, 'Model Number: ' . $data['model_number'], 0, 1);
        $pdf->Cell(50, 10, 'Assigned Tech: ' . $data['assigned_tech'], 0, 1);
        $pdf->Cell(50, 10, 'Date of Deployment: ' . $data['date_of_deployment'], 0, 1);
        $pdf->Cell(50, 10, 'Location/Department: ' . $data['location_department'], 0, 1);
        $pdf->Cell(50, 10, 'Status: ' . $data['status'], 0, 1);
        $pdf->Cell(50, 10, 'Service Due Date: ' . $data['service_due_date'], 0, 1);
        $pdf->Cell(50, 10, 'Condition: ' . $data['condition'], 0, 1);
        // $pdf->Cell(50, 10, 'Notes: ' . $data['notes'], 0, 1);
        
         // Wrap the "Notes" field using MultiCell
        $pdf->Cell(50, 10, 'Notes:', 0, 1);
        $pdf->MultiCell(0, 10, $data['notes'], 0, 'L');  // 0 for full width, 10 for height, and 'L' for left-align

        // Output the generated PDF
        // $this->response->setHeader('Content-Type', 'application/pdf');

        // // Sanitize the machine_type for use in the filename
        // $sanitizedMachineType = preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['machine_type']); 

        // // Output the generated PDF with the sanitized machine type in the filename
        // $pdf->Output('I', 'Machine_Type_' . $sanitizedMachineType . '.pdf');  // Forces download

        // exit;

        // Output the generated PDF
        $this->response->setHeader('Content-Type', 'application/pdf');

        // Sanitize the machine_type for use in the filename
        $sanitizedMachineType = preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['machine_type']); 

        // Set the Content-Disposition header for inline display and suggested filename
        $this->response->setHeader('Content-Disposition', 'inline; filename="Machine_Type_' . $sanitizedMachineType . '.pdf"');

        // Output the generated PDF to the browser
        $pdf->Output('I');  // 'I' for inline viewing

        exit;


    }


}
  
?>