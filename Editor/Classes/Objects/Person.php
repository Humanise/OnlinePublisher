<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Person'] = [
    'table' => 'person',
    'properties' => [
      'firstname' => array('type'=>'string'),
      'middlename' => array('type'=>'string'),
      'surname' => array('type'=>'string'),
      'initials' => array('type'=>'string'),
      'nickname' => array('type'=>'string'),
      'jobtitle' => array('type'=>'string'),
      'sex' => array('type'=>'string'),
      'streetname' => array('type'=>'string'),
      'zipcode' => array('type'=>'string'),
      'city' => array('type'=>'string'),
      'country' => array('type'=>'string'),
      'webaddress' => array('type'=>'string'),
      'imageId' => array('type'=>'int','column'=>'image_id', 'relation' => ['class' => 'Image', 'property' => 'id'])
    ]
];

class Person extends Object {
  var $firstname;
  var $middlename;
  var $surname;
  var $initials;
  var $nickname;
  var $jobtitle;
  var $sex=1;
  var $email_job;
  var $email_private;
  var $phone_job;
  var $phone_private;
  var $streetname;
  var $zipcode;
  var $city;
  var $country;
  var $webaddress;
  var $imageId;

  function Person() {
    parent::Object('person');
  }

  static function load($id) {
    return Object::get($id,'person');
  }

  function setFullName($name) {
    $this->firstname='';
    $this->middlename='';
    $this->surname='';
    $names = preg_split('/\s+/', $name);
    if (count($names)>0) {
      $this->firstname = $names[0];
    }
    if (count($names)==2) {
      $this->surname = $names[1];
    }
    else if (count($names)>2) {
      $this->middlename = $names[1];
      for ($i=2; $i < count($names); $i++) {
        if (strlen($this->surname)>0) {
          $this->surname.=' ';
        }
        $this->surname.=$names[$i];
      }
    }
    $this->_updateTitle();
  }

  function setFirstname($firstname) {
    $this->firstname = $firstname;
    $this->_updateTitle();
  }

  function getFirstname() {
    return $this->firstname;
  }

  function setMiddlename($middlename) {
    $this->middlename = $middlename;
    $this->_updateTitle();
  }

  function getMiddlename() {
    return $this->middlename;
  }

  function setSurname($surname) {
    $this->surname = $surname;
    $this->_updateTitle();
  }

  function getSurname() {
    return $this->surname;
  }

  function _updateTitle() {
    $title = '';
    if ($this->firstname!='') {
      $title.= $this->firstname;
    }
    if ($this->middlename!='') {
      if ($title!='') $title.=' ';
      $title.= $this->middlename;
    }
    if ($this->surname!='') {
      if ($title!='') $title.=' ';
      $title.= $this->surname;
    }
    $this->title = $title;
  }

  function setInitials($initials) {
    $this->initials = $initials;
  }

  function getInitials() {
    return $this->initials;
  }

  function setNickname($nickname) {
    $this->nickname = $nickname;
  }

  function getNickname() {
    return $this->nickname;
  }

  function setJobtitle($jobtitle) {
    $this->jobtitle = $jobtitle;
  }

  function getJobtitle() {
    return $this->jobtitle;
  }

  function setSex($sex) {
    $this->sex = $sex;
  }

  function getSex() {
    return $this->sex;
  }

  function setEmailJob($email_job) {
    $this->email_job = $email_job;
  }

  function getEmailJob() {
    return $this->email_job;
  }

  function setEmailPrivate($email_private) {
    $this->email_private = $email_private;
  }

  function getEmailPrivate() {
    return $this->email_private;
  }

  function setPhoneJob($phone_job) {
    $this->phone_job = $phone_job;
  }

  function getPhoneJob() {
    return $this->phone_job;
  }

  function setPhonePrivate($phone_private) {
    $this->phone_private = $phone_private;
  }

  function getPhonePrivate() {
    return $this->phone_private;
  }

  function setStreetname($streetname) {
    $this->streetname = $streetname;
  }

  function getStreetname() {
    return $this->streetname;
  }

  function setZipcode($zipcode) {
    $this->zipcode = $zipcode;
  }

  function getZipcode() {
    return $this->zipcode;
  }

  function setCity($city) {
    $this->city = $city;
  }

  function getCity() {
    return $this->city;
  }

  function setCountry($country) {
    $this->country = $country;
  }

  function getCountry() {
    return $this->country;
  }

  function setWebaddress($webaddress) {
    $this->webaddress = $webaddress;
  }

  function getWebaddress() {
    return $this->webaddress;
  }

  function setImageId($imageId) {
    $this->imageId = $imageId;
  }

  function getImageId() {
    return $this->imageId;
  }

  function getMailinglistIds() {
    $sql = "select mailinglist_id as id from person_mailinglist where person_id=".$this->id;
    return Database::getIds($sql);
  }

  function updateMailinglistIds($ids) {
    $ids = ObjectService::getValidIds($ids);
    $sql = "delete from person_mailinglist where person_id=".$this->id;
    Database::delete($sql);
    foreach ($ids as $id) {
      $sql = "insert into person_mailinglist (mailinglist_id,person_id) values (".$id.",".$this->id.")";
      Database::insert($sql);
    }
  }

  function getGroupIds() {
    $sql = "select persongroup_id as id from persongroup_person where person_id=".$this->id;
    return Database::getIds($sql);
  }

  function updateGroupIds($ids) {
    $ids = ObjectService::getValidIds($ids);
    $sql = "delete from persongroup_person where person_id=".$this->id;
    Database::delete($sql);
    foreach ($ids as $id) {
      $sql = "insert into persongroup_person (persongroup_id,person_id) values (".$id.",".$this->id.")";
      Database::insert($sql);
    }
  }

  function addGroupId($id) {

    $sql = "delete from persongroup_person where person_id=".$this->id." and persongroup_id=".$id;
    Database::delete($sql);
    $sql = "insert into persongroup_person (persongroup_id,person_id) values (".$id.",".$this->id.")";
    Database::insert($sql);
  }

  function addCustomSearch($query,&$parts) {
    $custom = $query->getCustom();
    if (isset($custom['group'])) {
      $parts['tables'][] = 'persongroup_person';
      $parts['limits'][] = 'persongroup_person.person_id = object.id';
      $parts['limits'][] = 'persongroup_person.persongroup_id = '.Database::int($custom['group']);
    }
    if (isset($custom['mailinglist'])) {
      $parts['tables'][] = 'person_mailinglist';
      $parts['limits'][] = 'person_mailinglist.person_id = object.id';
      $parts['limits'][] = 'person_mailinglist.mailinglist_id = '.Database::int($custom['mailinglist']);
    }
  }

  static function loadByEmail($email) {
    $sql = "select object.id from emailaddress, object where emailaddress.containing_object_id=object.id and object.type='person' and emailaddress.address=".Database::text($email);
    $row = Database::selectFirst($sql);
    if ($row) {
      return Person::load($row['id']);
    } else {
      return null;
    }
  }

  function sub_publish() {

    $data = '<person xmlns="'.parent::_buildnamespace('1.0').'">';
    if ($this->firstname!='') {
      $data.='<firstname>'.Strings::escapeEncodedXML($this->firstname).'</firstname>';
    }
    if ($this->middlename!='') {
      $data.='<middlename>'.Strings::escapeEncodedXML($this->middlename).'</middlename>';
    }
    if ($this->surname!='') {
      $data.='<surname>'.Strings::escapeEncodedXML($this->surname).'</surname>';
    }
    if ($this->initials!='') {
      $data.='<initials>'.Strings::escapeEncodedXML($this->initials).'</initials>';
    }
    if ($this->nickname!='') {
      $data.='<nickname>'.Strings::escapeEncodedXML($this->nickname).'</nickname>';
    }
    if ($this->jobtitle!='') {
      $data.='<jobtitle>'.Strings::escapeEncodedXML($this->jobtitle).'</jobtitle>';
    }
    if (isset($this->sex)) {
      $data.='<sex>'.($this->sex ? 'male' : 'female').'</sex>';
    }
    if ($this->email_job!='') {
      $data.='<email context="job">'.Strings::escapeEncodedXML($this->email_job).'</email>';
    }
    if ($this->email_private!='') {
      $data.='<email context="private">'.Strings::escapeEncodedXML($this->email_private).'</email>';
    }
    if ($this->phone_job!='') {
      $data.='<phone context="job">'.Strings::escapeEncodedXML($this->phone_job).'</phone>';
    }
    if ($this->phone_private!='') {
      $data.='<phone context="private">'.Strings::escapeEncodedXML($this->phone_private).'</phone>';
    }
    if ($this->streetname!='') {
      $data.='<streetname>'.Strings::escapeEncodedXML($this->streetname).'</streetname>';
    }
    if ($this->zipcode!='') {
      $data.='<zipcode>'.Strings::escapeEncodedXML($this->zipcode).'</zipcode>';
    }
    if ($this->city!='') {
      $data.='<city>'.Strings::escapeEncodedXML($this->city).'</city>';
    }
    if ($this->country!='') {
      $data.='<country>'.Strings::escapeEncodedXML($this->country).'</country>';
    }
    if ($this->webaddress!='') {
      $data.='<webaddress>'.Strings::escapeEncodedXML($this->webaddress).'</webaddress>';
    }
    if ($this->imageId>0) {
      $sql="select * from object where id=".$this->imageId;
      if ($img = Database::selectFirst($sql)) {
        $data.='<image>'.$img['data'].'</image>';
      }
    }


    $sql = "select address from emailaddress where containing_object_id=".$this->id;
    $result = Database::select($sql);
      while ($row = Database::next($result)) {
      $data.='<email>'.Strings::escapeEncodedXML($row['address']).'</email>';
      }
    Database::free($result);

    $sql = "select number,context from phonenumber where containing_object_id=".$this->id;
    $result = Database::select($sql);
      while ($row = Database::next($result)) {
      $data.='<phone context="'.Strings::escapeEncodedXML($row['context']).'">'.Strings::escapeEncodedXML($row['number']).'</phone>';
      }
    Database::free($result);

    $data.='</person>';
    return $data;

  }

  function removeMore() {
    $sql="delete from person_mailinglist where person_id=".$this->id;
    Database::delete($sql);
    $sql="delete from emailaddress where containing_object_id=".$this->id;
    Database::delete($sql);
    $sql="delete from phonenumber where containing_object_id=".$this->id;
    Database::delete($sql);
    $sql="delete from persongroup_person where person_id=".$this->id;
    Database::delete($sql);
  }

  /////////////////////////// GUI /////////////////////////

  function getIcon() {
    return "common/person";
  }

  function updateEmailAddresses($new) {
    $mails = Query::after('emailaddress')->withProperty('containingObjectId',$this->getId())->get();
    $foundIds = array();
    foreach ($new as $email) {
      if (isset($email->id) && $email->id>0) {
        $old = Emailaddress::load($email->id);
        if ($old==null) {
          $old = new Emailaddress();
        } else {
          $foundIds[] = $old->getId();
        }
      } else {
        $old = new Emailaddress();
      }
      if (strlen($email->address)>0) {
        $old->setAddress($email->address);
        $old->setContainingObjectId($this->getId());
        $old->save();
        $old->publish();
      } else if ($old->getId()>0) {
        $old->remove();
      }
    }
    foreach ($mails as $mail) {
      if (!in_array($mail->getId(),$foundIds)) {
        $mail->remove();
      }
    }
  }

  function updatePhoneNumbers($new) {
    $numbers = Query::after('phonenumber')->withProperty('containingObjectId',$this->getId())->get();
    $foundIds = array();
    foreach ($new as $number) {
      if (isset($number->id) && $number->id>0) {
        $old = PhoneNumber::load($number->id);
        if ($old==null) {
          $old = new PhoneNumber();
        } else {
          $foundIds[] = $old->getId();
        }
      } else {
        $old = new PhoneNumber();
      }
      if (strlen($number->number)>0 || strlen($number->context)>0) {
        $old->setNumber($number->number);
        $old->setContext($number->context);
        $old->setContainingObjectId($this->getId());
        $old->save();
        $old->publish();
      } else if ($old->getId()>0) {
        $old->remove();
      }
    }
    foreach ($numbers as $mail) {
      if (!in_array($mail->getId(),$foundIds)) {
        $mail->remove();
      }
    }
  }
}
?>