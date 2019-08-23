<?php 

class Institution {

    private $name;
    private $conn;

    function __construct($name = '', $conn = '') {
        if(!$conn) {
            require(realpath(dirname(__DIR__)) . '/services/database.php');
            $this->conn = connect(); 
        } else {
            $this->conn = $conn;
        }
        $this->name = $name;
    }

    public function get() {
        $sql = 'SELECT * FROM  institution ORDER BY id DESC';
        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return 'Error on get';
        }
    }

    public function getById($id) {
        $sql = 'SELECT * FROM  institution WHERE id = ' . $id;
        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            return json_encode([
                'status' => 'SUCCESS',
                'payload' => $stmt->fetch(PDO::FETCH_OBJ)
            ]);
        } else {
            return 'Error on get';
        }

        //$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        // create institution on database
        $sql = 'INSERT INTO institution (name) VALUES (?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $this->name);

        if($stmt->execute()) {
            return 'SUCCESS';
        } else {
            return 'ERROR';
        }
    }  

    public function getByName() {
        $sql = "SELECT * FROM  institution WHERE name like '%" . $this->name . "%'";
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_OBJ); 
        }
    }

    public function getByInstitution($id) {
        $sql = "select student.code as code,
                       student.name as studentname
                  from student
            inner join institution on institution.id = student.institution_id
                 where institution_id = " . $id;
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute()) {
            $sql = 'SELECT name FROM institution WHERE id = ' . $id;
            $stmt1 = $this->conn->prepare($sql);
            $stmt1->execute();

            return json_encode([
                'status' => 'SUCCESS',
                'payload' => [
                    'students' => $stmt->fetchAll(PDO::FETCH_OBJ),
                    'institution' => $stmt1->fetch(PDO::FETCH_OBJ)
                ]
            ]);
        } else {
            return 'ERROR';
        }
    }

    public function save($name) {
        if(strlen($name)) {  
            self::setName($name);
            $data = self::getByName($name);
            if(!$data) {
                self::setName($name);
                $response = self::create();
                if($response == 'SUCCESS') {
                    return json_decode(self::getByName());
                }
            } else {
                return json_decode(self::getById($data->id), true);
            }
        }
    }

    public function update($id) {
        // update institution on database
        $sql = 'UPDATE institution SET name = :name WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return 'SUCCESS';
        } else {
            return 'ERROR';
        }
    }

    public function delete($id) {
        //delete institution on database

        $sql = 'DELETE FROM institution WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $id);

        if($stmt->execute()) {
            return 'SUCCESS';
        } else {
            return 'ERROR';
        }
    }

    public function setName($name) {
        return $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}