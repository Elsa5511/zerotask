<?php

namespace Equipment\Controller\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;


class ConfigFieldsHelper {
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get all fields to show by an specific application. This fields are given by equipment.local.php configuration
     * @param string $application
     * @return array
     */
    public function getEquipmentFieldsByApplication($application = "")
    {
        $application = strtolower($application);

        $config = $this->serviceLocator->get('Config');
        $allFields = $config['equipment'];
        $fieldsToShow = array();

        foreach($allFields as $fieldName => $options) {
            if(isset($options['mandatory'])) {

                if ($options['mandatory'])
                    $fieldsToShow[] = $fieldName;
                elseif (isset($options['applications'])) {

                    if($options['applications'] === 'all')
                        $fieldsToShow[] = $fieldName;
                    elseif(is_array($options['applications']) && in_array($application, $options['applications']))
                        $fieldsToShow[] = $fieldName;

                }
            }
        }

        return $fieldsToShow;
    }
}