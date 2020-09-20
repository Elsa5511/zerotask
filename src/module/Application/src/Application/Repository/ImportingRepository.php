<?php

namespace Application\Repository;

class ImportingRepository {
    public function getPagesFromLadoc($ladocAdapter){
        return $this->getMenuBySublevelFromLadoc($ladocAdapter, 0);
    }
    
    public function getSectionsFromLadoc($ladocAdapter){
        return $this->getMenuBySublevelFromLadoc($ladocAdapter, 1);
    }
    
    public function getSections2FromLadoc($ladocAdapter, $sectionids){
        $sql = sprintf('SELECT c.id, c.title, c.section, c.description, \'ladoc\' as application
            FROM ldk_categories c
            WHERE c.section IN (%s)', implode(', ', $sectionids));
        $stmt = $ladocAdapter->createStatement($sql);
        return $stmt->execute();
    }
    
    public function getSections3FromLadoc($ladocAdapter, $sectionids){
        $sql = sprintf('SELECT s.id, s.title, s.description, \'ladoc\' as application
            FROM ldk_sections s
            WHERE s.id IN (%s)', implode(', ', $sectionids));
        $stmt = $ladocAdapter->createStatement($sql);
        return $stmt->execute();
    }
    
    public function getSubSectionsFromLadoc($ladocAdapter){
        return $this->getMenuBySublevelFromLadoc($ladocAdapter, 2);
    }
    
    public function getInlineSectionsFromLadoc($ladocAdapter){
        return $this->getMenuBySublevelFromLadoc($ladocAdapter, 3);
    }
    
    public function getContentsFromLadoc($ladocAdapter, $ids, $catids = null){
        $sql = sprintf('SELECT c.id, c.title, c.introtext, c.fulltext, c.created, c.modified,
            c.sectionid, c.catid
            FROM ldk_content c
            WHERE c.state = 1 AND c.id IN (%s)', implode(', ', $ids));
        if($catids){
            $sql = $sql . sprintf(' OR c.catid IN (%s)', implode(', ', $catids));
        }
        $stmt = $ladocAdapter->createStatement($sql);
        return $stmt->execute();
    }
    
    public function getCategoriesFromLadoc($ladocAdapter){
        $stmt = $ladocAdapter->createStatement('SELECT c.id_category, c.parent_category, c.chr_name,
            c.chr_description, c.int_indexlevel, c.int_indexorder, cc.content, \'ladoc\' as application 
            FROM ldk_cat_category c
            LEFT JOIN ldk_section_category_content cc ON c.id_category = cc.id_category
            WHERE c.bool_active = 1 and c.id_section = 1');
        return $stmt->execute();
    }
    
    public function getCategoriesFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT c.id_category, c.parent_category, c.chr_name,
            c.chr_description, c.int_indexlevel, c.int_indexorder, null AS content, \'medoc\' as application 
            FROM mdc_cat_category c
            WHERE c.bool_active = 1 and c.id_section = 1');
        return $stmt->execute();
    }
    
    public function getEquipmentsFromLadoc($ladocAdapter){
        $stmt = $ladocAdapter->createStatement('SELECT e.id_equipment, e.id_category, e.chr_name, e.txt_description, e.date_register,
            e.date_update, e.chr_code, \'ladoc\' as application, e.id_supplier FROM ldk_strg_equipment e
            WHERE e.bool_active = 1');
        return $stmt->execute();
    }
    
    public function getEquipmentsFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT e.id_equipment, e.id_category, e.chr_name, e.txt_description, e.date_register,
            e.date_update, e.chr_code, e.img_equipment, \'medoc\' as application, e.id_supplier FROM mdc_strg_equipment e
            WHERE e.bool_active = 1');
        return $stmt->execute();
    }
    //TODO: delete LIMIT in query
    public function getEquipmentsDetailsFromLadoc($ladocAdapter){
        $stmt = $ladocAdapter->createStatement('SELECT ed.id_equipment_detail, ed.id_equipment, ed.parent_equipment_detail, 
            ed.chr_title, ed.txt_description, ed.date_register, ed.date_update, ed.int_indexorder, \'ladoc\' as application
            FROM ldk_strg_equipment_detail ed
            JOIN ldk_strg_equipment eq ON eq.id_equipment = ed.id_equipment
            WHERE ed.bool_active = 1 AND eq.bool_active = 1');
        return $stmt->execute();
    }
    //TODO: delete LIMIT in query
    public function getEquipmentsDetailsFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT ed.id_equipment_detail, ed.id_equipment, ed.parent_equipment_detail, 
            ed.chr_title, ed.txt_description, ed.date_register, ed.date_update, ed.int_indexorder, \'medoc\' as application
            FROM mdc_strg_equipment_detail ed
            JOIN mdc_strg_equipment eq ON eq.id_equipment = ed.id_equipment
            WHERE ed.bool_active = 1 AND eq.bool_active = 1');
        return $stmt->execute();
    }
    //TODO: delete LIMIT in query
    public function getEquipmentsFilesFromLadoc($ladocAdapter){
        $stmt = $ladocAdapter->createStatement('SELECT f.id_equipment_file, f.id_equipment_detail, f.chr_title,
            f.date_registered, f.date_update, f.id_file_type, f.chr_title_publish, f.txt_description, \'ladoc\' as application
            FROM ldk_strg_equipment_file f
            JOIN ldk_strg_equipment_detail ed ON ed.id_equipment_detail = f.id_equipment_detail
            JOIN ldk_strg_equipment eq ON eq.id_equipment = ed.id_equipment
            WHERE f.bool_active = 1 AND ed.bool_active = 1 AND eq.bool_active = 1');
        return $stmt->execute();
    }
    //TODO: delete LIMIT in query
    public function getEquipmentsFilesFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT f.id_equipment_file, f.id_equipment_detail, f.chr_title,
            f.date_registered, f.date_update, f.id_file_type, f.chr_title_publish, f.txt_description, \'medoc\' as application
            FROM mdc_strg_equipment_file f
            JOIN mdc_strg_equipment_detail ed ON ed.id_equipment_detail = f.id_equipment_detail
            JOIN mdc_strg_equipment eq ON eq.id_equipment = ed.id_equipment
            WHERE f.bool_active = 1 AND ed.bool_active = 1 AND eq.bool_active = 1');
        return $stmt->execute();
    }
    
    public function getSuppliersFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT s.id_supplier, s.chr_name_supplier, s.date_update, s.date_register,
            s.int_phone, s.chr_email, s.url_website, s.contact_name 
            FROM mdc_strg_supplier s
            WHERE s.bool_active = 1');
        return $stmt->execute();
    }
    
    public function getExamnsFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT e.id_exam, e.id_equipment, e.chr_title, 
            e.date_register, e.date_update, \'medoc\' as application 
            FROM mdc_trn_exam e
            JOIN mdc_strg_equipment eq ON eq.id_equipment = e.id_equipment
            WHERE e.bool_active = 1
            AND eq.bool_active = 1');
        return $stmt->execute();
    }
    
    public function getQuestionsFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT q.id_question, q.id_exam, q.id_question_type, 
            q.chr_question, q.chr_url_image, q.int_weight, q.int_indexorder, q.url_resource_link, \'medoc\' as application 
            FROM mdc_trn_question q
            JOIN mdc_trn_exam e ON e.id_exam = q.id_exam
            JOIN mdc_strg_equipment eq ON eq.id_equipment = e.id_equipment
            WHERE q.bool_active = 1
            AND q.id_question_type IN (1, 2)
            AND e.bool_active = 1
            AND eq.bool_active = 1');
        return $stmt->execute();
    }
    
    public function getOptionsFromMedoc($medocAdapter){
        $stmt = $medocAdapter->createStatement('SELECT o.id_option, o.id_question, o.chr_option, 
            o.bool_is_correct
            FROM mdc_trn_option o
            JOIN mdc_trn_question q ON q.id_question = o.id_question
            JOIN mdc_trn_exam e ON e.id_exam = q.id_exam
            JOIN mdc_strg_equipment eq ON eq.id_equipment = e.id_equipment
            WHERE o.bool_active = 1
            AND q.bool_active = 1
            AND q.id_question_type IN (1, 2)
            AND e.bool_active = 1
            AND eq.bool_active = 1');
        return $stmt->execute();
    }
    
    private function getMenuBySublevelFromLadoc($ladocAdapter, $sublevel){
        $stmt = $ladocAdapter->createStatement("SELECT m.id, m.name, m.link, 
            m.parent, m.ordering, 'ladoc' as application
            FROM  ldk_menu m
            WHERE m.menutype = 'mainmenu'
            AND m.published = 1
            AND m.type LIKE '%component%'
            AND m.sublevel = ?
            ORDER BY m.parent ");
        return $stmt->execute(array($sublevel));
    }
    
}

?>
