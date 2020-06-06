<?php


namespace App\Services;

use App\Product;

class ProductService implements IProductService
{
    public function importProductFromFile($file)
    {
        $path = $this->getFileRealPath($file);
        $data = $this->turnFileIntoArray($path);
        $columns = $this->getProductsColumns($data);
        $data = $this->removeFirstLineFromData($data);

        $this->insertFileDataIntoDB($data, $columns);
    }

    public function getFileRealPath($file)
    {
        return $file->getRealPath();
    }

    public function turnFileIntoArray($filePath)
    {
        $file = file($filePath);
        return $file;
    }

    public function insertFileDataIntoDB($data, $columns)
    {
        foreach($data as $row)
            $this->createDbRecord($columns, $row);
    }

    public function removeFirstLineFromData($file)
    {
        return array_slice($file, 1);
    }

    public function getProductsColumns($file)
    {
        $columns = str_replace(["\r", "\n"], '', $file[0]);
        return explode(',', $columns);
    }

    public function extractDataFromFileRow($row)
    {
        $row = str_replace(["\r", "\n"], '', $row);
        return explode(',', $row);
    }

    public function createRequestArray($columns, $data)
    {
        $db = [];
        for($i=0; $i < count($data); $i++)
            $db[$columns[$i]] = $data[$i];

        return $db;
    }

    public function createDbRecord($columns, $row)
    {
        $record = $this->extractDataFromFileRow($row);
        $db = $this->createRequestArray($columns, $record);

        Product::create($db);
    }
}
