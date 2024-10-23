<?php 
  
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

   
class InventoryModel extends Model
{
    protected $table = 'inventory';
   
    protected $allowedFields = [
                                    'id',
                                    'machine_id',
                                    'machine_type',
                                    'manufacturer',
                                    'model_number',
                                    'assigned_tech',
                                    'date_of_deployment',
                                    'location_department',
                                    'status',
                                    'service_due_date',
                                    'condition',
                                    'notes',
                                    'updated_by'
                                ];
      
    // public function __construct() {
    //     parent::__construct();
    //     //$this->load->database();
    //     $db = \Config\Database::connect();
    //     $builder = $db->table('inventory');
    // }
      
    public function insert_data($data, $username) {

        $currentDateTime = date('Y-m-d H:i:s');
        $username = session()->get('username'); 
        
        // For a new insertion, we only set the username and current timestamp
        $data['updated_by'] = $username . ' - ' . $currentDateTime;

        // Insert the data into the table
        if ($this->insert($data)) {
            return $this->insertID(); // Return the last inserted ID
        } else {
            return false; // In case of failure
        }
    }


    public function update_data($id, $data, $username) {
        $currentDateTime = date('Y-m-d H:i:s');
        $username = session()->get('username'); 

        // Retrieve the existing value of `updated_by` from the database
        $existingData = $this->find($id);
        $existingUpdatedBy = $existingData['updated_by'];

        // Append the new update info to the existing `updated_by` field
        if (!empty($existingUpdatedBy)) {
            $data['updated_by'] = $existingUpdatedBy . "\n" . $username . ' - ' . $currentDateTime;
        } else {
            // If `updated_by` is empty for some reason, initialize it
            $data['updated_by'] = $username . ' - ' . $currentDateTime;
        }

        // Update the row in the table
        return $this->update($id, $data);

    }
}



?>