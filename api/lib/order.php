<?php
namespace Lib;

/**
 * Order entity data class
 */
class Order
{
    private $id;
    private $region;
    private $waterbase;
    private $quantity;
    private $address;
    private $date;

    private $dataFile = '/data.json';
    private $filesDir = '/files/';

    /**
     * Create instance
     * @param $data order entity fields
     */
    public function __construct($data) 
    {
        $this->id = $data['id'];
        $this->region = $data['region'];
        $this->waterbase = $data['waterbase'];
        $this->quantity = $data['quantity'];
        $this->address = $data['address'];
        $this->date = $data['date'];
    }

    /**
     * Validate order method
     */
    public function validate() 
    {
        return $this->region && $this->waterbase && $this->quantity && $this->address;
    }

    /**
     * Save order method
     */
    public function save() 
    {
        $result = ['status' => 'ok'];

        $filePath = $_SERVER["DOCUMENT_ROOT"] . $this->dataFile;

        $orders = [[
            "region" => $this->region,
            "waterbase" => $this->waterbase,
            "quantity" => $this->quantity,
            "address" => $this->address,
            "date" => $this->date,
        ]];

        try {

            if(file_exists($filePath)) 
            {
                $content = file_get_contents($filePath);
                $currentOrders = json_decode($content);
                $orders = array_merge($currentOrders, $orders);
            }
                
            $file = fopen($filePath, "w");
            fwrite($file, json_encode($orders));
            fclose($file);

        } catch (Exception $e) {
            $result['status'] = 'error';
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}