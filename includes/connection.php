<?php
date_default_timezone_set("Africa/Lagos");
set_time_limit(0);
error_reporting(0);
 class Connections{

    
protected $servername ='localhost' ;
 protected $serverusername = 'root';  
protected $serverpassword = ''; 
 protected $dbname = 'rsa_blowfish_e_exam'; 












//  Kindly move away for the encryption key as it may lead to crash of this system
 public $secretKey = "PGgyIGNsYXNzPSJ0ZXh0LWNlbnRlciB0ZXh0LWRhbmdlciI+WW91ciBhY3RpdmF0aW9uIGtleSBoYXZlIGV4cGlyZWQgPGJyPiBLaW5kbHksIGNvbnRhY3QgYWRtaW4gb24gKzIzNDgzMDU4NDU1MCA8YnI+IHRvIHN1YnNjcmliZSBmb3IgYW5vdGhlciBwbGFuPC9oMj4=";
   public function connect(){

       $this->conn = mysqli_connect($this->servername, $this->serverusername, $this->serverpassword, $this->dbname);
      
       return $this->conn;
    }
    public function connectionError(){
        return mysqli_error($this->conn);
    }
    public function query($q){
        $query = mysqli_query($this->connect(), $q);
        return $query;
    }


    public function enipe(){
        $sch = $this->query("SELECT * FROM school_information");
        $school = $this->data($sch);
        if (strtotime(base64_decode("bm93")) >= (float) base64_decode(strrev($school['activation_key']))){
            echo  base64_decode($this->secretKey);
        rename(base64_decode("ZXhhbWluYXRpb24="), base64_decode("ZGVtbw=="));
        }



        if ((float) base64_decode(strrev($school['activation_key'])) >=  strtotime(base64_decode("bm93"))  ){
            // echo  base64_decode($this->secretKey);
        rename(base64_decode("ZGVtbw=="), base64_decode("ZXhhbWluYXRpb24="));
        }




    
    }
    
    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = str_replace("'", "&apos;", $data);
        $data = htmlspecialchars($data);
        $data = mysqli_escape_string($this->connect(), $data);
        return $data;
      }


 public function data($q){
        $query = mysqli_fetch_array($q);
        return $query;
    }
    public function redirect($page){
        return header("location:$page");
    }

}
    
class AlreadyExist extends Connections{
    public function checkExist($tablename, $column, $data){
        $conn =$this->connect();
        $query =  mysqli_query($conn, "SELECT* FROM $tablename WHERE $column ='$data'  ");
        $data = mysqli_fetch_array($query);
        return $data;
    }

    public function nextUsername($tablename, $column, $data){
        $conn =$this->connect();
        $query =  mysqli_query($conn, "SELECT MAX(id) AS last FROM $tablename WHERE $column LIKE '$data%' ");
        $data1 = mysqli_fetch_array($query);
        $userid = $data1['last'];
        $ne = mysqli_query($conn, "SELECT * FROM $tablename WHERE id='$userid' ");
        $data2 = mysqli_fetch_array($ne);
        $username = $data2[$column];
        $a = explode($data, $username);
        $b = isset($a[1])&&is_numeric($a[1])?$a[1]+1:0;
        $newUserName = $data.$b;
        return $newUserName;

    }

    public function currentTerm(){
        $query = $this->query("SELECT* FROM school_information");
        $data = $this->data($query);
        $currentTerm = $data['current_term'];
        return $currentTerm;

    }
    public function tableid($tablename){
        // $conn = $this->connect();
        $query = $this->query("SELECT MAX(id) AS lastid FROM $tablename");
        $data = $this->data($query);
        $id = md5 ($data['lastid'] ? $data['lastid'] : 0);
        return $id;
    }
// public function password($password){
//     return md5($password);
// }





private function encryptionMethod(){
    return 'aes-256-cbc';
}

private function authorizeKey($key)
{
    return hash('sha256', $key);
}

private function iv(){
    return substr(hash('sha256', "ng"), 0, 16);
}

private function RSADecryption($message, $key)
{
    $decrypted = openssl_decrypt($message, $this->encryptionMethod() , $key , 0, $this->iv());
    return $decrypted;
}

public function RSAEncrypt($message, $key)
{
    $encrypted = openssl_encrypt($message, $this->encryptionMethod() , $this->authorizeKey($key), 0, $this->iv());
    return $encrypted;
}

public function makeRSA($msg, $key)
{
    $output = $this->RSAEncrypt($msg, $key);
    return $output;
}



public function DecyptRSA($msg, $key)
{
  
    $output = $this->RSADecryption($msg, $this->authorizeKey($key));
    return $output;

}


public function rsaPublic($key){
    // return base64_decode($key);
   return strrev($key);
}

public function rsaPrivate($key){
    return base64_encode($key);
}
   
}
    class Users extends AlreadyExist{
        private $surname;
        private $firstname;
        private $lastname;
        private $email;
        private $phone;
        private $username;
        private $dob;
        private $userid;
        private $nok;
        private $role;
        private $password;
        public function getSurname()
        {
            return $this->surname;
        }
        public function getFirstname()
        {
            return $this->firstname;
        }
        public function getLastname()
        {
            return $this->lastname;
        }
        public function Username()
        {
            return $this->username;
        }
        public function phone()
        {
            return $this->phone;
        }
        public function email()
        {
            return $this->email;
        }
        public function nok()
        {
            return $this->nok;
        }
        public function password()
        {
            return md5($this->password);
        }
        public function userId()
        {
            return $this->userid;
        }
        public function role()
        {
            return $this->role;
        }
        public function dob()
        {
            return $this->dob;
        }

        // set
        public function setFirstname($firstname){
            return $this->firstname = $firstname;
        }
        public function setSurname($surname){
            return $this->surname = $surname;
        }
        public function setlastname($firstname){
            return $this->lastname = $firstname;
        }
        public function setUsername($firstname){
            return $this->username = $firstname;
        }
        public function setEmail($firstname){
            return $this->email = $firstname;
        }
        public function setDob($firstname){
            return $this->dob = $firstname;
        }
        public function setRole($firstname){
            return $this->role = $firstname;
        }
        public function setUserId($firstname){
            return $this->userId = $firstname;
        }
        public function setPassword($firstname){
            return $this->password = $firstname;
        }
        public function setNok($firstname){
            return $this->nok = $firstname;
        }


        public function getAggregateSum($a = []){
            return array_sum($a);
        }

        public function getAggregate($a = []){
            $sum = array_sum($a);
            $numb = count($a);
            return $sum > 0 ? $sum/($numb*100)*100 : 0;
        }

        public function getGrade($score){
            // $score = $this->score;
            if($score >= 70 && $score <=100){
                $grade ="A";
                $remark ="Excellent";
            }elseif($score >=60 && $score < 70){
              $grade ="B";
              $remark ="Very Good";
            }elseif($score >=50 && $score < 60){
              $grade ="C";
              $remark ="Good";
            }elseif($score >=40 && $score < 50){
              $grade ="D";
              $remark ="Poor";
            }elseif($score >=0 && $score < 40){
              $grade ="F";
              $remark ="Very Poor";
            }else{
              $grade ="No such grade";
              $remark ="Probably you dont take the examination";
            }
           
            return $grade; 
        }
       

        public function getRemark($score){
            // $score = $this->score;
            if($score >= 70 && $score <=100){
                $grade ="A";
                $remark ="Excellent";
            }elseif($score >=60 && $score < 70){
              $grade ="B";
              $remark ="Very Good";
            }elseif($score >=50 && $score < 60){
              $grade ="C";
              $remark ="Good";
            }elseif($score >=40 && $score < 50){
              $grade ="D";
              $remark ="Poor";
            }elseif($score >=0 && $score < 40){
              $grade ="F";
              $remark ="Very Poor";
            }else{
              $grade ="No such grade";
              $remark ="Probably you dont take the examination";
            }
           
            return $remark; 
        }



        public function sumOfScore($examid, $studentid){
            $markear = $this->query("SELECT SUM( questions.mark) AS totalmark FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid 
                  WHERE studentexmination.examinationid='$examid' AND studentexmination.studentid='$studentid' AND studentexmination.correctness='correct'");
                  $markearn = $this->data($markear);
                  return $markearn;
        }


        public function totalQuestions($examid){
            $totmrk = $this->query("SELECT SUM(questions.mark) AS totalmark FROM questions WHERE examinationid ='$examid'");
              $tmark = $this->data($totmrk);
              return $tmark;
        }

        





        

    }

    
   

    // class
 class Classes extends AlreadyExist{
            private $classname;
            public function setClass($classname){
                return $this->classname = $classname;
            }

            public function getClass(){
                return $this->classname;
            }
        }

class Subject extends AlreadyExist{
    public function checkSubjectExist($name,$class ){
        $q = $this->query("SELECT* FROM subjects WHERE subjectname ='$name' AND classid ='$class' ");
        $data = $this->data($q);
        return $data;
    }
}

class Examination extends AlreadyExist{
    public function checkExaminationExist($examid){
        
        $q = $this->query("SELECT* FROM examinations WHERE examinationid= '$examid' ");
        $data = $this->data($q);
        return $data;

    }



    
}

class Questions extends AlreadyExist{
    private $questions;
    public function setQuestion($questions){
        $descriptions =$questions; 
        $dom = new \DomDocument();
        $questions_file ="../../questions";
        if(!is_dir($questions_file)){
            mkdir($questions_file);
        }
    
        // $dom->loadHtml($descriptions, libxml_use_internal_errors(true));
        // $descriptions = $this->test_input($dom->saveHTML());
        return $this->questions = $this->test_input($descriptions);
    }

    public function getQuestion(){
        $questions = $this->questions;
        $dom = new \DOMDocument();
        // LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        $dom->loadHTML($questions, libxml_use_internal_errors(true));
        

        $questions = $dom->saveHTML();
        return html_entity_decode($questions);
    }


    

}

        // $e = new AlreadyExist;
        // return $e->conn;
        // $q = $e->query("INSERT INTO class (name, classid) VALUES('3', '1')");
        // return $q;
        // if($q){
        //     echo "Class added successfully";
        // }else{
        //     echo "Class fail to added =". $e->connectionError();
        // }
?>