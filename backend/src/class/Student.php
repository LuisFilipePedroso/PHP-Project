<?php 
require(realpath(dirname(__DIR__)) . '/class/Minerator.php');

class Student {

    private $code;
    private $name;
    private $generalRank;
    private $institution;
    private $signedSince;
    private $score;
    private $solved;
    private $tried;
    private $submissions;
    private $conn;

    function __construct($name = '') {
        require(realpath(dirname(__DIR__)) . '/services/database.php');
        $this->conn = connect();
        $this->name = $name;
    }

    public function get() {
        $sql = 'SELECT * FROM  student';
        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Error on get';
        }
    }

    public function getById($id) {
        $sql = 'SELECT * FROM  student WHERE id = ' . $id;
        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            return json_encode([
                'status' => 'SUCCESS',
                'payload' => $stmt->fetch(PDO::FETCH_OBJ)
            ]);
        } else {
            return 'Error on get';
        }
    }

    public function getByName($all = false) {
        $sql = "SELECT student.*, 
                       institution.name as 'instituicao'   
                  FROM student 
            INNER JOIN institution on institution.id = student.institution_id
                 WHERE student.name like '%" .  $this->name . "%'";
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute()) {
            return $all == true ? $stmt->fetchAll(PDO::FETCH_OBJ)  : $stmt->fetch(PDO::FETCH_OBJ); 
        }
    }

    public function create() {
        // create user on database
        $sql = 'INSERT INTO student (name, generalrank, signedsince, score, solved, tried, submissions, institution_id, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $this->name);
        $stmt->bindValue(2, (float)$this->generalRank);
        $stmt->bindValue(3, $this->signedSince);
        $stmt->bindValue(4, (float)$this->score);
        $stmt->bindValue(5, (int)$this->solved);
        $stmt->bindValue(6, (int)$this->tried);
        $stmt->bindValue(7, (int)$this->submissions);
        $stmt->bindValue(8, $this->institution);
        $stmt->bindValue(9, (int)$this->code);

        if($stmt->execute()) {
            return 'SUCCESS';
        } else {
            return 'ERROR';
        }
    }  

    public function update($id) {
        // update user on database
    }

    public function delete($id) {
        //delete user on database
    }

    public function search($code) {
        require(realpath(dirname(__DIR__)) . '/class/Institution.php');

        $minerator = new Minerator();
        $student = $minerator->minerate($code);
        $institution = new Institution('', $this->conn);
        if($student['institution'] != "") {
            $result = $institution->save($student['institution']);
        } else {
            $result['payload']['id'] = 99;
        }

        if(strlen(trim($student['generalRank'])) > 10) {
            $student['generalRank'] = 0;   
        }

        self::setName($student['name']);
        $hasData = self::getByName();
        if(!$hasData) {
            self::setCode((int) $code);
            self::setGeneralRank((float) $student['generalRank']);
            self::setInstitution($result['payload']['id']);
            self::setSignedSince(date('Y-m-d', strtotime($student['signedSince'])));
            self::setScore((float) $student['score']);
            self::setSolved((int) $student['solved']);
            self::setTried((int) $student['tried']);
            self::setSubmissions((int) $student['submissions']);

            self::create();
        }

        return self::getByName();
    }

    public function setCode($code) {
        return $this->code = $code;
    }

    public function getCode() {
        return $this->code;
    }

    public function setName($name) {
        return $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setGeneralRank($generalRank) {
        return $this->generalRank = $generalRank;
    }

    public function getGeneralRank() {
        return $this->generalRank;
    }

    public function setInstitution($institution) {
        return $this->institution = $institution;
    }

    public function getInstitution() {
        return $this->institution;
    }
    
    public function setSignedSince($signedSince) {
        return $this->signedSince = $signedSince;
    }

    public function getSignedSince() {
        return $this->signedSince;
    }

    public function setScore($score) {
        return $this->score = $score;
    }

    public function getScore() {
        return $this->score;
    }

    public function setTried($tried) {
        return $this->tried = $tried;
    }

    public function getTrid() {
        return $this->tried;
    }

    public function setSubmissions($submissions) {
        return $this->submissions = $submissions;
    }

    public function getSubmissions() {
        return $this->submissions;
    }

    public function setSolved($solved) {
        return $this->solved = $solved;
    }

    public function getSolved() {
        return $this->solved;
    }
}