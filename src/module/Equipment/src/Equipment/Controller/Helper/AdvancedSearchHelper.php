<?php

namespace Equipment\Controller\Helper;


class AdvancedSearchHelper {
    public static function buildParametersForInstanceControlSearch(array $search)
    {
        $searchParams = array();

        if (self::hasKeyWithValue('category', $search)) {
            $searchParams['taxonomies'] = array(
                'category' => $search['category']
            );
        }
        if (self::hasKeyWithValue('sap', $search)) {
            $searchParams['equipment_equal']['sap'] = $search['sap'];
        }
        if (self::hasKeyWithValue('equipment', $search)) {
            $searchParams['equipment-instance']['equipment'] = $search['equipment'];
        }
        if (self::hasKeyWithValue('owner', $search)) {
            $searchParams['equipment-instance']['owner'] = $search['owner'];
        }
        if (self::hasKeyWithValue('location', $search)) {
            $searchParams['equipment-instance']['location'] = $search['location'];
        }
        if (self::hasKeyWithValue('usageStatus', $search)) {
            $searchParams['equipment-instance']['usageStatus'] = $search['usageStatus'];
        }
        if (self::hasKeyWithValue('serialNumber', $search)) {
            $searchParams['equipment-instance_like']['serialNumber'] = $search['serialNumber'];
        }
        if (self::hasKeyWithValue('batchNumber', $search)) {
            $searchParams['equipment-instance_like']['batchNumber'] = $search['batchNumber'];
        }
        if (self::hasKeyWithValue('regNumber', $search)) {
            $searchParams['equipment-instance_like']['regNumber'] = $search['regNumber'];
        }
        if (self::hasKeyWithValue('registeredBy', $search)) {
            $searchParams['attributes_equal']['registeredBy'] = $search['registeredBy'];
        }
        if (self::hasKeyWithValue('controlStatus', $search)) {
            $searchParams['attributes_equal']['controlStatus'] = $search['controlStatus'];
        }
        if (self::hasKeyWithValue('fromDate', $search)) {
            $searchParams['attributes_range']['fromDate'] = $search['fromDate'];
        }
        if (self::hasKeyWithValue('toDate', $search)) {
            $searchParams['attributes_range']['toDate'] = $search['toDate'];
        }
        if(self::hasKeyWithValue('controlType', $search)) {
            $searchParams['controlType'] = $search['controlType'];
            if ($search['controlType'] === 'periodic' && self::hasKeyWithValue('expertiseOrgan', $search)) {
                $searchParams['attributes_equal']['expertiseOrgan'] = $search['expertiseOrgan'];
            }
        }


        return $searchParams;
    }

    public static function buildParametersForEquipmentInstanceSearch(array $search)
    {
        $checkoutStatus = isset($search['checkoutStatus']) ? $search['checkoutStatus'] : null;
        $checkedOut = null;
        if ($checkoutStatus == "1") $checkedOut = true;
        elseif ($checkoutStatus == "2") $checkedOut = false;

        $searchParams = array();

        if (self::hasKeyWithValue('category', $search)) {
            $searchParams['taxonomies'] = array(
                'category' => $search['category']
            );
        }
        if (self::hasKeyWithValue('nsn', $search)) {
            $searchParams['equipment']['nsn'] = $search['nsn'];
        }
        if (self::hasKeyWithValue('sap', $search)) {
            $searchParams['equipment']['sap'] = $search['sap'];
        }
        if (self::hasKeyWithValue('vendor', $search)) {
            $searchParams['vendor'] = $search['vendor'];
        }
        if (self::hasKeyWithValue('serialNumber', $search)) {
            $searchParams['attributes_like']['serialNumber'] = $search['serialNumber'];
        }
        if (self::hasKeyWithValue('regNumber', $search)) {
            $searchParams['attributes_like']['regNumber'] = $search['regNumber'];
        }
        if (self::hasKeyWithValue('batchNumber', $search)) {
            $searchParams['attributes_like']['batchNumber'] = $search['batchNumber'];
        }
        if (self::hasKeyWithValue('rfid', $search)) {
            $searchParams['attributes_like']['rfid'] = $search['rfid'];
        }
        if (self::hasKeyWithValue('equipment', $search)) {
            $searchParams['attributes_equal']['equipment'] = $search['equipment'];
        }
        if (self::hasKeyWithValue('owner', $search)) {
            $searchParams['attributes_equal']['owner'] = $search['owner'];
        }
        if (self::hasKeyWithValue('usageStatus', $search)) {
            $searchParams['attributes_equal']['usageStatus'] = $search['usageStatus'];
        }
        if ($checkedOut) {
            $searchParams['attributes_equal']['checkedOut'] = $checkedOut;
        }
        if (self::hasKeyWithValue('orderNumber', $search)) {
            $searchParams['attributes_equal']['orderNumber'] = $search['orderNumber'];
        }
        if (self::hasKeyWithValue('location', $search)) {
            $searchParams['location'] = $search['location'];
        }
        if (self::hasKeyWithValue('fromDate', $search)) {
            $searchParams['fromDate'] = $search['fromDate'];
        }
        if (self::hasKeyWithValue('toDate', $search)) {
            $searchParams['toDate'] = $search['toDate'];
        }
        if(self::hasKeyWithValue('controlType', $search)) {
            $searchParams['controlType'] = $search['controlType'];
        }

        return $searchParams;
    }


    private static function hasKeyWithValue($key, $array) {
        return array_key_exists($key, $array) && !empty($array[$key]);
    }
}