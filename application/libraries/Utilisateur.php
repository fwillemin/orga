<?php

/**
 * Classe de gestion des Users
 * Manager : Model_Users
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Utilisateur {

    protected $id;
    protected $username;
    protected $userNom;
    protected $userPrenom;
    protected $userEtablissementId;
    protected $email;
    protected $active;
    protected $last_login;
    protected $userGroups;
    protected $userGroupsIds;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;
    }

    public function hydrateGroups() {
        $CI = & get_instance();
        $this->userGroups = $CI->ion_auth->get_users_groups($this->id)->result();
        foreach ($this->userGroups as $groupe):
            $this->userGroupsIds[] = $groupe->id;
        endforeach;
    }

    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getUserNom() {
        return $this->userNom;
    }

    function getUserPrenom() {
        return $this->userPrenom;
    }

    function getUserEtablissementId() {
        return $this->userEtablissementId;
    }

    function getEmail() {
        return $this->email;
    }

    function getActive() {
        return $this->active;
    }

    function getLast_login() {
        return $this->last_login;
    }

    function getUserGroups() {
        return $this->userGroups;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setUserNom($userNom) {
        $this->userNom = $userNom;
    }

    function setUserPrenom($userPrenom) {
        $this->userPrenom = $userPrenom;
    }

    function setUserEtablissementId($userEtablissementId) {
        $this->userEtablissementId = $userEtablissementId;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function setLast_login($last_login) {
        $this->last_login = $last_login;
    }

    function setUserGroups($userGroups) {
        $this->userGroups = $userGroups;
    }

    function getUserGroupsIds() {
        return $this->userGroupsIds;
    }

    function setUserGroupsIds($userGroupsIds) {
        $this->userGroupsIds = $userGroupsIds;
    }

}
