<?php 
  
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

   
class InventoryModel extends Model
{
    protected $table = 'inventory';
   
    protected $allowedFields = [
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
                            
                                ];
      

 

 
    
        // Method to get child serial numbers based on the parent ref_num
        public function getChildSerialNumbers($refNum)
        {
            return $this->where('tagged_prim_num', $refNum)->findAll();
        }


    public function insert_data($data, $username) {
        $currentDateTime = date('Y-m-d H:i');
        $data['active_status'] = 1;
        $data['updated_by'] = $username . ' - ' . $currentDateTime;
        $data_logs = json_encode($data);


        if ($this->insert($data)) {
            $insertedId = $this->insertID();
            $data['id'] = $insertedId; // Include the insert ID

            

        // Log the action after successful insertion
        if (!$this->log_action($data['serial_no'], 'insert', $username, $data_logs)) {
            log_message('error', 'Failed to log insertion action for serial_no: ' . $insertedId);
        }

            return $data; // Return the full data array with updated_by included

        } else {
            log_message('error', 'Failed to insert data: ' . json_encode($data));
            return false;
        }
    }
    
    
    


    private function log_action($serial_no, $action, $username, $data_logs) {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_logs');
    
        $data = [
            'serial_no' => $serial_no,
            'action'     => $action,
            'performed_by' => $username,
            'data_logs' => $data_logs,
            'timestamp'  => date('Y-m-d H:i')
        ];
    
        // Add error handling
        if (!$builder->insert($data)) {
            log_message('error', 'Failed to log action: ' . $builder->error());
            return false; // Or throw an exception if you prefer
        }
        return true;
    }
    


    public function update_data($id, $data, $username) {
        $currentDateTime = date('Y-m-d H:i');
        // $username = session()->get('username'); 
        $data_logs = json_encode($data);

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
        // return $this->update($id, $data);
      
          // Update the row in the table
        if ($this->update($id, $data)) {
            $this->log_action($existingData['serial_no'], 'update', $username, $data_logs); // Log the action
            return true;
        }

        return false; // In case of failure
        

    }



    public function deactivate_item($id, $username, $data)
    {
        $currentDateTime = date('Y-m-d H:i');

        // Retrieve the existing `updated_by` value and append the new log entry
        $existingUpdatedBy = $data['updated_by'] ?? ''; // Fallback to empty if `updated_by` is missing
        $data['updated_by'] = !empty($existingUpdatedBy) 
            ? $existingUpdatedBy . "\n" . $username . ' - ' . $currentDateTime 
            : $username . ' - ' . $currentDateTime;

        // Set `active_status` to 0
        $data['active_status'] = 0;

        // Prepare the data to be logged in `inventory_logs`
        $data_logs = json_encode($data);

        log_message('info', 'Attempting to deactivate item ID: ' . $id . ' with data: ' . $data_logs);

        // Update the item in the `inventory` table
        if ($this->update($id, $data)) {
            log_message('info', 'Successfully updated item ID: ' . $id);

            // Log the full row data to `inventory_logs`
            $this->log_action($data['serial_no'], 'deactivate', $username, $data_logs);

            return true; // Item deactivated successfully
        } else {
            log_message('error', 'Failed to deactivate item ID: ' . $id . '. Update returned: ' . json_encode($this->errors()));
            return false;
        }
    }
    
}



?>