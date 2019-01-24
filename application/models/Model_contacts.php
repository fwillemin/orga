<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_contacts extends MY_model {

    protected $table = 'contacts';

    const classe = 'Contact';

    /**
     * Ajout d'un objet de la classe Contact à la BDD
     * @param Contact $contact Objet de la classe Contact
     */
    public function ajouter(Contact $contact) {
        $this->db
                //->set('contactEtablissementId', $this->session->userdata('etablissementId'))
                ->set('contactEtablissementId', $contact->getContactEtablissementId())
                ->set('contactDate', $contact->getContactDate())
                ->set('contactMode', $contact->getContactMode())
                ->set('contactNom', $contact->getContactNom())
                ->set('contactAdresse', $contact->getContactAdresse())
                ->set('contactCp', $contact->getContactCp())
                ->set('contactVille', $contact->getContactVille())
                ->set('contactTelephone', $contact->getContactTelephone())
                ->set('contactEmail', $contact->getContactEmail())
                ->set('contactObjet', $contact->getContactObjet())
                ->set('contactCategorieId', $contact->getContactCategorieId())
                ->set('contactSource', $contact->getContactSource())
                ->set('contactCommercialId', $contact->getContactCommercialId())
                ->set('contactEtat', $contact->getContactEtat())
                ->insert($this->table);
        $contact->setContactId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Contact
     * @param Contact $contact Objet de la classe Contact
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Contact $contact) {
        $this->db
                ->set('contactDate', $contact->getContactDate())
                ->set('contactMode', $contact->getContactMode())
                ->set('contactNom', $contact->getContactNom())
                ->set('contactAdresse', $contact->getContactAdresse())
                ->set('contactCp', $contact->getContactCp())
                ->set('contactVille', $contact->getContactVille())
                ->set('contactTelephone', $contact->getContactTelephone())
                ->set('contactEmail', $contact->getContactEmail())
                ->set('contactObjet', $contact->getContactObjet())
                ->set('contactCategorieId', $contact->getContactCategorieId())
                ->set('contactSource', $contact->getContactSource())
                ->set('contactCommercialId', $contact->getContactCommercialId())
                ->set('contactEtat', $contact->getContactEtat())
                ->where('contactId', $contact->getContactId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Contact Objet de la classe Contact
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Contact $contact) {
        $this->db->where('contactId', $contact->getContactId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Contacts correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Contact
     */
    public function getContacts($where = array(), $tri = 'contactDate DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('contactEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Contact correspondant à l'id
     * @param integer $contactId ID de l'raisonSociale
     * @return \Contact|boolean
     */
    public function getContactById($contactId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('contactEtablissementId', $this->session->userdata('etablissementId'))
                ->where('contactId', $contactId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

//    public function getContactByIdMigration($contactId, $type = 'object') {
//        $query = $this->db->select('*')
//                ->from($this->table)
//                ->where('contactId', $contactId)
//                ->get();
//        return $this->retourne($query, $type, self::classe, true);
//    }
}
