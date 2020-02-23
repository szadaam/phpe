<?php

class User {

  // other tables that contains the objectts unique id must be recorded in this array
  // !!!: table_relations 
  //private $table_relations = [];

  private $id;
  private $type;
  private $usergroups;
  private $permissions;
  private $email;
  private $username;
  private $realname;
  private $phone;
  private $lastlogin;
  private $membersince;
  private $lat;
  private $lng;
  private $county_id;
  private $town_id;
  private $postcode_id;
  private $postcode;
  private $address;
  private $updated;
  private $blocked;
  private $maxlevel;

  function __construct($user_data = null) {
    $this->createUser($user_data);
  }

  private function createUser($user_data) {

    if (isset($user_data['id'])) {
      $this->id = $user_data['id'];
    }

    if (isset($user_data['email'])) {
      $this->email = $user_data['email'];
    }

    if (isset($user_data['username'])) {
      $this->username = $user_data['username'];
    }

    if (isset($user_data['realname'])) {
      $this->realname = $user_data['realname'];
    }

    if (isset($user_data['phone'])) {
      $this->phone = $user_data['phone'];
    }

    if (isset($user_data['lastlogin'])) {
      $this->lastlogin = $user_data['lastlogin'];
    }

    if (isset($user_data['membersince'])) {
      $this->membersince = $user_data['membersince'];
    }

    if (isset($user_data['usergroups'])) {

      if ($user_data['usergroups'] == null) {
        $this->setUsergroups([]);
      } else {
        $usergroups = unserialize($user_data['usergroups']);
      }

      $this->createUserGroups($usergroups);
    }

    if (isset($user_data['lat'])) {
      $this->lat = $user_data['lat'];
    }

    if (isset($user_data['lng'])) {
      $this->lng = $user_data['lng'];
    }

    if (isset($user_data['county_id'])) {
      $this->county_id = $user_data['county_id'];
    }

    if (isset($user_data['town_id'])) {
      $this->town_id = $user_data['town_id'];
    }

    if (isset($user_data['postcode_id'])) {
      $this->postcode_id = $user_data['postcode_id'];
    }

    if (isset($user_data['postcode'])) {
      $this->postcode = $user_data['postcode'];
    }

    if (isset($user_data['address'])) {
      $this->address = $user_data['address'];
    }

    if (isset($user_data['updated'])) {
      $this->updated = $user_data['updated'];
    }

    if (isset($user_data['blocked'])) {
      $this->blocked = $user_data['blocked'];
    }

    $this->createMaxLevel();
  }

  private function createMaxLevel() {

    $levels = [];

    if (is_array($this->usergroups)) {

      foreach ($this->usergroups as $usergroup) {
        array_push($levels, $usergroup->getLevel());
      }
    }

    $this->maxlevel = !empty($levels) ? max($levels) : -1;
  }

  private function createUserGroups($usergroup_ids) {

    $usergroups = [];

    foreach ($usergroup_ids as $id) {

      $sql = "SELECT * FROM usergroups WHERE id = ?";
      $usergroup_data = Database::selectSingle($sql, [$id]);

      array_push($usergroups, new UserGroup($usergroup_data));
    }

    $this->usergroups = $usergroups;
  }

  function getId() {
    return $this->id;
  }

  function getType() {
    return $this->type;
  }

  function getUsergroups() {
    return $this->usergroups;
  }

  function getPermissions() {
    return $this->permissions;
  }

  // !!!: updated
  function isUpdated() {
    return $this->updated == 1;
  }

  function getEmail() {
    return $this->email;
  }

  function getUsername() {
    return $this->username;
  }

  function getRealname() {
    return $this->realname;
  }

  function getPhone() {
    return $this->phone;
  }

  function getLastlogin() {
    return $this->lastlogin;
  }

  function getMembersince() {
    return $this->membersince;
  }

  function getLat() {
    return $this->lat;
  }

  function getLng() {
    return $this->lng;
  }

  function getCounty_id() {
    return $this->county_id;
  }

  function getTown_id() {
    return $this->town_id;
  }

  function getPostcode_id() {
    return $this->postcode_id;
  }

  function getPostcode() {
    return $this->postcode;
  }

  function getAddress() {
    return $this->address;
  }

  function getActive() {
    return $this->active;
  }

  function getBlocked() {
    return $this->blocked;
  }

  function getName() {
    return $this->realname = '' ? $this->username : $this->realname;
  }

  function getMaxlevel() {
    return $this->maxlevel;
  }

  // SETTERS

  function setType($type) {
    $this->type = $type;
  }

  function setUsergroups($usergroups, $update = true) {

    $this->usergroups = $usergroups;

    if ($update) {

      $sql = "UPDATE user SET usergroups = ? WHERE id = ?";
      Database::update($sql, [serialize($usergroups), $this->id], true);
    }
  }

  function setPermissions($permissions) {
    $this->permissions = $permissions;
  }

  function setEmail($email) {
    $this->email = $email;
  }

  function setUsername($username) {
    $this->username = $username;
  }

  function setRealname($realname) {
    $this->realname = $realname;
  }

  function setPhone($phone) {
    $this->phone = $phone;
  }

  function setLastlogin($lastlogin) {
    $this->lastlogin = $lastlogin;
  }

  function setMembersince($membersince) {
    $this->membersince = $membersince;
  }

  function setLat($lat) {
    $this->lat = $lat;
  }

  function setLng($lng) {
    $this->lng = $lng;
  }

  function setCounty_id($county_id) {
    $this->county_id = $county_id;
  }

  function setTown_id($town_id) {
    $this->town_id = $town_id;
  }

  function setPostcode_id($postcode_id) {

    $this->postcode_id = $postcode_id;

    $sql = "UPDATE users SET postcode_id = ? WHERE id = ?";
    Database::update($sql, [$postcode_id, $this->id]);

    $this->setUpdated(1);
  }

  function setAddress($address) {
    $this->address = $address;
  }

  function setUpdated($updated) {

    $sql = "UPDATE users SET updated = ? WHERE id = ?";
    $update = Database::update($sql, [$updated, $this->id], false, false);

    $this->updated = $updated;
  }

  function setBlocked($blocked) {
    $this->blocked = $blocked;
  }

  // procedural version

  public static function get($user_id, $columns = null) {

    if ($columns == null) {
      $columns = ['username'];
    } else if ($columns == '*') {

      $columns = [
          'id',
          'email',
          'username',
          'realname',
          'phone',
          'lastlogin',
          'membersince',
          'usergroups',
          'lat',
          'lng',
          'county_id',
          'town_id',
          'postcode_id',
          'postcode',
          'address',
          'updated',
          'blocked'
      ];
    }

    // assemble SQL query

    $sql = "SELECT ";
    $count = count($columns);

    for ($i = 0; $i < $count; $i++) {
      $sql .= $columns[$i];
      if ($i != $count - 1) {
        $sql .= ', ';
      } else {
        $sql .= ' ';
      }
    }

    $sql .= "FROM users WHERE id = ?";

    return Database::selectSingle($sql, [$user_id]);
  }

}
