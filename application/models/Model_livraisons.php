<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_livraisons extends MY_model {

    /**
     * Utlisé pour les migrations depuis la V1
     * Model à supprimer une fois les migrations terminées.
     */
    protected $table = 'livraisons';

    const classe = 'Livraison';

    /**
     * Ajout d'un objet de la classe Livraison à la BDD
     * @param Livraison $livraison Objet de la classe Livraison
     */
    public function ajouter(Livraison $livraison) {
        $this->db
                ->set('livraisonOriginId', $livraison->getLivraisonOriginId())
                ->set('livraisonChantierId', $livraison->getLivraisonChantierId())
                ->set('livraisonFournisseurId', $livraison->getLivraisonFournisseurId() ?: null)
                ->set('livraisonDate', $livraison->getLivraisonDate())
                ->set('livraisonEtat', $livraison->getLivraisonEtat())
                ->set('livraisonRemarque', $livraison->getLivraisonRemarque())
                ->insert($this->table);
        $livraison->setLivraisonId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Livraison
     * @param Livraison $livraison Objet de la classe Livraison
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Livraison $livraison) {
        $this->db
                ->set('livraisonChantierId', $livraison->getLivraisonChantierId())
                ->set('livraisonFournisseurId', $livraison->getLivraisonFournisseurId() ?: null)
                ->set('livraisonDate', $livraison->getLivraisonDate())
                ->set('livraisonEtat', $livraison->getLivraisonEtat())
                ->set('livraisonRemarque', $livraison->getLivraisonRemarque())
                ->where('livraisonId', $livraison->getLivraisonId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Livraison Objet de la classe Livraison
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Livraison $livraison) {
        $this->db->where('livraisonId', $livraison->getLivraisonId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Livraisons correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Livraison
     */
    public function getLivraisons($where = array(), $tri = 'livraisonDate DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from('livraisons l')
                ->join('chantiers c', 'c.chantierId = l.livraisonChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLivraisonsPlanning($dateDebut, $dateFin, $tri = 'livraisonDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from('livraisons l')
                ->join('chantiers c', 'c.chantierId = l.livraisonChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('c.chantierEtat', 1)
                ->where(array('livraisonDate >=' => $dateDebut, 'livraisonDate <=' => $dateFin))
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Livraison correspondant à l'id
     * @param integer $livraisonId ID de l'raisonSociale
     * @return \Livraison|boolean
     */
    public function getLivraisonById($livraisonId, $type = 'object') {
        $query = $this->db->select('*')
                ->from('livraisons l')
                ->join('chantiers c', 'c.chantierId = l.livraisonChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('livraisonId', $livraisonId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getLivraisonsByAffectationId($affectationId, $type = 'object') {
        $query = $this->db->select('*')
                ->from('livraisons l')
                ->join('chantiers c', 'c.chantierId = l.livraisonChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->join('livraisons_affectations la', 'la.livraisonId = l.livraisonId')
                ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('la.affectationId', $affectationId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLivraisonsByChantierId($chantierId, $type = 'object') {
        $query = $this->db->select('*')
                ->from('livraisons l')
                ->join('chantiers c', 'c.chantierId = l.livraisonChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('c.chantierId', $chantierId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLivraisonsByChantierIdMigration($chantierId, $type = 'object') {
        $query = $this->db->select('*')
                ->from('livraisons l')
                ->join('chantiers c', 'c.chantierId = l.livraisonChantierId')
                ->where('c.chantierId', $chantierId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
