<?php 
  
namespace App\Controllers;
   
use CodeIgniter\Controller;
use App\Models\InventoryModel;
use Dompdf\Dompdf;

   
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

    // Set CSV column headers
    $header = array("ID", "Machine ID", "Machine Type", "Manufacturer", "Model Number", "Assigned Technician", "Date of Deployment", "Location/Department", "Status", "Service Due Date", "Condition", "Notes", "Updated By");
    fputcsv($file, $header);

    // Insert data into CSV
    foreach ($inventoryData as $row) {
        fputcsv($file, $row);
    }

    fclose($file);
    exit;
    }





    public function exportPDF()
    {
        $dompdf = new Dompdf();
        
        // Get inventory data
        $inventoryModel = new \App\Models\InventoryModel();
        $inventoryData = $inventoryModel->findAll();

        // Build HTML content for the PDF
        $html = '<h2 style="text-align: center;">Inventory List</h2>';
        $html .= '<style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                        margin-bottom: 20px;
                    }
                    th, td {
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: left;
                        font-size: 10px; /* Adjust font size as needed */
                    }
                    th {
                        background-color: #f2f2f2; /* Light gray background for headers */
                    }
                    @media print {
                        th {
                            position: sticky;
                            top: 0;
                            z-index: 10;
                        }
                    }
                </style>';
        $html .= '<table>';
        $html .= '<tr>
                    <th>ID</th>
                    <th>Machine ID</th>
                    <th>Machine Type</th>
                    <th>Manufacturer</th>
                    <th>Model Number</th>
                    <th>Assigned Technician</th>
                    <th>Date of Deployment</th>
                    <th>Location/Department</th>
                    <th>Status</th>
                    <th>Service Due Date</th>
                    <th>Condition</th>
                    <th>Notes</th>
                    <th>Updated By</th>
                </tr>';
        
        foreach ($inventoryData as $row) {

            $updatedByContent = nl2br(htmlspecialchars($row['updated_by'])); // Convert new lines and escape HTML

            $html .= '<tr>
                        <td>' . $row['id'] . '</td>
                        <td>' . $row['machine_id'] . '</td>
                        <td>' . $row['machine_type'] . '</td>
                        <td>' . $row['manufacturer'] . '</td>
                        <td>' . $row['model_number'] . '</td>
                        <td>' . $row['assigned_tech'] . '</td>
                        <td>' . $row['date_of_deployment'] . '</td>
                        <td>' . $row['location_department'] . '</td>
                        <td>' . $row['status'] . '</td>
                        <td>' . $row['service_due_date'] . '</td>
                        <td>' . $row['condition'] . '</td>
                        <td>' . $row['notes'] . '</td>
                        <td>' . $updatedByContent . '</td> <!-- Updated by content with line breaks -->
                    </tr>';
        }
        
        $html .= '</table>';

        // Load HTML content into Dompdf and generate PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output the generated PDF (downloadable)
        $dompdf->stream("inventory_" . date('Ymd') . ".pdf", array("Attachment" => 1));
        exit;
    }


}
  
?>