<?php

namespace Application\Service;

use Application\Entity\Application;
use Zend\Stdlib\Hydrator\ClassMethods;

class ApplicationService extends AbstractBaseService
{

    protected $applications;

    /**
     * Return the list of enabled applications.
     * @return array List of applications
     */
    public function getApplications($applicationsThatUserCanAccess)
    {
        $applicationArray = array();
        $hydrator = new ClassMethods(true);

        foreach ($this->applications as $application) {
            if(!array_key_exists('name', $application)){
                continue;
            }

            $applicationEntity = $hydrator->hydrate($application, new Application());
            if (in_array(strtolower($applicationEntity->getSlug()), $applicationsThatUserCanAccess)) {
                array_push($applicationArray, $applicationEntity);
            }
        }

        return $applicationArray;
    }

    /**
     * Return the application by its keyname.
     * @param string $applicationKey: The key name for the application
     * @return Application
     */
    public function getApplication($applicationKey)
    {
        if (array_key_exists($applicationKey, $this->applications)) {
            $hydrator = new ClassMethods(true);
            return $hydrator->hydrate($this->applications[$applicationKey], new Application());
        }
    }
    
    public function vidumTranslatable()
    {
        $wordsForTranslate = $this->getTranslatableWords();
        if($wordsForTranslate) {
            $fileContent = "<?php ";
            foreach ($wordsForTranslate as $word) {
                $fileContent .= "_('" . $word['translatable'] . "');";
            }
            $fileContent .= " ?>";
            $fileName = __DIR__ . "/../Utility/translatable.phtml";
            file_put_contents($fileName, $fileContent);
            return $fileContent;
        }
    }
    
    private function getTranslatableWords()
    {
        $sql = $this->sqlForTranslatableWords($this->translatable);
        if (!empty($sql)) {
            $connection = $this->getEntityManager()->getConnection();
            $preparedStatement = $connection->prepare($sql);
            $preparedStatement->execute();
            return $preparedStatement->fetchAll();
        }
    }

    private function sqlForTranslatableWords($translatable)
    {
        $sql = "";
        foreach ($translatable as $table => $field) {
            $sql .= "SELECT `$field` AS translatable FROM `$table` UNION ";
        }
        $finalSql = preg_replace('~\s+\S+$~', '', trim($sql));
        return $finalSql;
    }

}
