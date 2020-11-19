<?php 
class Firebase {
    protected $database;
    protected $dbname;
    public function __construct($dbname = 'users'){
        $this->dbname = $dbname;
        $factory = (new \Kreait\Firebase\Factory)->withServiceAccount('../secret/service-account.json');
        $this->database = $factory->createDatabase();
    }
    public function get(int $key = NULL){    
        if (empty($key) || !isset($key)) { return FALSE; }
        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($key)){
            return $this->database->getReference($this->dbname)->getChild($key)->getValue();
        } else {
            return FALSE;
        }
    }
    public function set(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        $this->database->getReference()->getChild($this->dbname)->set($data);
        
        return TRUE;
    }
    public function insert(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        $this->database->getReference()->getChild($this->dbname)->update($data);
        
        return TRUE;
    }
    public function delete(int $key) {
        if (empty($key) || !isset($key)) { return FALSE; }
        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($key)){
            $this->database->getReference($this->dbname)->getChild($key)->remove();
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function increment(int $key) { //Creates a new key path if not found
        if (empty($key) || !isset($key)) { return FALSE; }

        $counterRef = $this->database->getReference($this->dbname)->getChild($key);
        $this->database->runTransaction(function (\Kreait\Firebase\Database\Transaction $transaction) use ($counterRef) {
            // You have to snapshot the reference in order to change its value
            $counterSnapshot = $transaction->snapshot($counterRef);
        
            // Get the existing value from the snapshot
            $counter = $counterSnapshot->getValue() ?: 0;
            $newCounter = ++$counter;
            // If the value hasn't changed in the Realtime Database while we are
            // incrementing it, the transaction will be a success.
            $transaction->set($counterRef, $newCounter);
            return $newCounter;
        });
        return TRUE;
    }
    public function safe_delete(int $key) {
        if (empty($key) || !isset($key)) { return FALSE; }

        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($key)) { 
            $toBeDeleted = $this->database->getReference($this->dbname)->getChild($key);
            $this->database->runTransaction(function (\Kreait\Firebase\Database\Transaction $transaction) use ($toBeDeleted) {
    
                $transaction->snapshot($toBeDeleted);
            
                $transaction->remove($toBeDeleted);
            });
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function get_keypairs() {
        $arr = $this->database->getReference($this->dbname)->shallow()->getValue();
        $new_arr = array();
        foreach ($arr as $key => $val) {
            if (! is_null($val)) {
                $new_arr[$key] = $val;
            }
        }
        return $new_arr;
    }
}
?>