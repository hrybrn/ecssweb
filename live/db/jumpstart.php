<?php
$relPath = "../";

//if(!file_exists("helpers.csv") || !file_exists("freshers.csv")){
//	exit;
//}

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$statement = $db->query("SELECT * FROM helper;");
if($statement->fetchObject()){
	exit;
}

//count helpers
$helpers = fopen("helperdata.csv",'r');
$count = 0;
while($line = fgetcsv($helpers)){
	if($line[0] != ""){
		$count++;
	}
}
fclose($helpers);

//create groups
for($i = 1; $i <= $count; $i++){
	$sql = "INSERT INTO jumpstartGroup(groupName) VALUES('Group " . $i . "');";
	$db->query($sql);
}

//read in helpers
$helpers = fopen("helperdata.csv",'r');
while($line = fgetcsv($helpers)){
	//sometimes excel adds blank space at the bottom of the sheet for some reason
	if($line[0] != ""){
		$helper = new Helper($line[0], (integer)$line[1], $line[2], $line[3]);

		$statement = $db->prepare($helper->fresherSql());
		$statement->execute($helper->fresherInfo());
		$memberID = $db->query("SELECT last_insert_rowid();")->fetch();
		$memberID = (integer)$memberID[0];

		$helper->setMemberID($memberID);
		$statement = $db->prepare($helper->helperSql());
		$statement->execute($helper->helperInfo());
	}
}
fclose($helpers);

echo "helpers added";

$freshers = fopen($relPath . "../data/freshers.csv", "r");
while($line = fgetcsv($freshers)){
	if($line[0] != ""){
		$fresher = new Fresher($line[0], (integer)$line[1]);

		$statement = $db->prepare($fresher->fresherSql());
		$statement->execute($fresher->fresherInfo());
	}
}
fclose($freshers);

class Course{
	public $uk;
	public $eu;
	public $int;

	public $name;

	public function __construct($name){
		$this->name = $name;
		$uk = array();
		$eu = array();
		$int = array();
	}

	public function is($name){
		return $this->name === $name;
	}

	public function size(){
		return count($this->uk) + count($this->eu)+ count($this->int);
	}

	public function empty(){
		return count($this->uk) + count($this->eu)+ count($this->int) === 0;
	}

	public function uk(){
		return count($this->uk) / $this->size(); 
	}

	public function eu(){
		return count($this->eu) / $this->size(); 
	}

	public function int(){
		return count($this->int) / $this->size(); 
	}
}

class Helper extends Fresher{
	public $username;
	public $memberID;
	public $image;

	public function __construct(String $name, $groupID, String $email, String $image){
		$this->groupID = $groupID;

		$email = strtolower($email);

		$emailParts = explode("@", $email);
		$this->username = trim($emailParts[0]);

		parent::__construct($name, $groupID);
		$this->helper = 1;

		$this->image = "helpers/" . $image;
	}

	public function helperSql(){
		return "INSERT INTO helper(memberID, image, username) VALUES(:memberID, :image, :username);";
	}

	public function helperInfo(){
		return array(':memberID' => $this->memberID, ':image' => $this->image, ':username' => $this->username);
	}

	public function setMemberID($memberID){
		$this->memberID = $memberID;
	}
}

class Fresher {
	public $name;
	public $groupID;
	public $helper;

	public function __construct(String $name, $groupID){
		$this->name = $name;
		$this->groupID = $groupID;
		$this->helper = 0;
	}

	public function fresherSql(){
		return "INSERT INTO jumpstart(memberName, groupID, helper) VALUES(:name, :groupID, :helper);";
	}

	public function fresherInfo(){
		return array(':name' => $this->name, ':groupID' => $this->groupID, ':helper' => $this->helper);
	}
}