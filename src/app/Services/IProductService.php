<?php

namespace App\Services;

interface IProductService
{
    function importProductFromFile($file);
    
    function getFileRealPath($file);
    
    function turnFileIntoArray($filePath);
    
    function insertFileDataIntoDB($data, $columns);

    function removeFirstLineFromData($file);
    
    function getProductsColumns($file);
    
    function extractDataFromFileRow($row);

    function createRequestArray($columns, $data);
  
    function createDbRecord($columns, $row);
}
