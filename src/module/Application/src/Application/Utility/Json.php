<?php

namespace Application\Utility;

class Json
{

    /**
     * This method add data to equipment json file
     */
    public function addEquipmentJson($file, $equipments = array())
    {
        $oldArray = json_decode(file_get_contents($file), true);
        foreach ($equipments as $equipment) {
            $addArray[] = $equipment;
        }
        $newArray = json_encode(array_merge($oldArray, $addArray));
        file_put_contents($file, $newArray);
    }

    /**
     * This method remove data from equipment json file
     * 
     * @param string $file
     * @param integer $id
     */
    public function deleteEquipmentJson($file, $id)
    {
        $oldArray = json_decode(file_get_contents($file), true);
        $counter = 0;
        foreach ($oldArray as $key => $element) {
            if ($element['equipment_id'] == $id):
                unset($oldArray[$key]);
                $counter++;
            endif;
        }
        $newArray = json_encode(array_values($oldArray));
        if ($counter > 0):
            file_put_contents($file, $newArray);
        endif;
    }

}
