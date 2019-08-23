<?php 
require(realpath(dirname(__DIR__)) . '/services/database.php');

class StudentClass {

    private $name;
    private $conn;

    function __construct($name = '') {
        $this->conn = connect(); 
        $this->name = $name;
    }

    public function get() {
        $sql = 'SELECT * FROM classes';
        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Error on get';
        }
    }

    public function getById($id) {
        $sql = 'SELECT * FROM  classes WHERE id = ' . $id;
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

    public function getStudentsByClassroom($id) {
        $sql = 'select student.code as code,
                       student.name as student, 
                       classes.name as classroom,
                       student.score as score
                  from classes_has_student chs 
            inner join student on student.id = chs.student_id
            inner join classes on classes.id = chs.classes_id
                where classes_id = ' . $id . '
            order by student.score desc';

        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            $sql = 'SELECT name FROM classes WHERE id = ' . $id;
            $stmt1 = $this->conn->prepare($sql);
            $stmt1->execute();

            return json_encode([
                'status' => 'SUCCESS',
                'payload' => [
                    'students' => $stmt->fetchAll(PDO::FETCH_OBJ),
                    'classroom' => $stmt1->fetch(PDO::FETCH_OBJ)
                ]
            ]);
        } else {
            return 'Error on get';
        }
    }

    public function getStudentsByClassroomInterval($id) {
        $sql = 'select student.code as code,
                       student.name as student, 
                       classes.name as classroom,
                       student.score as score
                  from classes_has_student chs 
            inner join student on student.id = chs.student_id
            inner join classes on classes.id = chs.classes_id
                where classes_id = ' . $id . '
            order by student.score desc';

        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()) {
            $maxscore = 0;
            $minscore = 1;

            $students = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach($students as $student) {
                if($student->score > $maxscore) {
                    $maxscore = (float) $student->score;
                }

                if($student->score <= $minscore) {
                    $minscore = (float) $student->score;    
                }
            }

            $sub = $maxscore - $minscore;
            $intervals = $sub / 5;

            $sqlInterval1 = 'select count(*) as value
                  from classes_has_student chs 
            inner join student on student.id = chs.student_id
            inner join classes on classes.id = chs.classes_id
                where classes_id = ' . $id . '
                  and student.score between 0 and 100
            order by student.score desc';
            $stmt = $this->conn->prepare($sqlInterval1);
            $stmt->execute();
            $interval1 = (int) $stmt->fetch(PDO::FETCH_OBJ)->value;

            $sqlInterval2 = 'select count(*) as value
                  from classes_has_student chs 
            inner join student on student.id = chs.student_id
            inner join classes on classes.id = chs.classes_id
                where classes_id = ' . $id . '
                  and student.score between 100 and 500
            order by student.score desc';
            $stmt = $this->conn->prepare($sqlInterval2);
            $stmt->execute();
            $interval2 = (int) $stmt->fetch(PDO::FETCH_OBJ)->value;

            $sqlInterval3 = 'select count(*) as value
                  from classes_has_student chs 
            inner join student on student.id = chs.student_id
            inner join classes on classes.id = chs.classes_id
                where classes_id = ' . $id . '
                  and student.score between 500 and 1000
            order by student.score desc';
            $stmt = $this->conn->prepare($sqlInterval3);
            $stmt->execute();
            $interval3 = (int) $stmt->fetch(PDO::FETCH_OBJ)->value;

            $all = 'select count(*) as value
                  from classes_has_student chs 
            inner join student on student.id = chs.student_id
            inner join classes on classes.id = chs.classes_id
                where classes_id = ' . $id . '
            order by student.score desc';
            $stmt = $this->conn->prepare($all);
            $stmt->execute();
            $all = (int) $stmt->fetch(PDO::FETCH_OBJ)->value;

            $relativaInterval1 = ($interval1 / $all) * 100;
            $relativaInterval2 = ($interval2 / $all) * 100;
            $relativaInterval3 = ($interval3 / $all) * 100;

            $json = [
                "a" => [
                    "interval" => "0-100",
                    "value" => $relativaInterval1
                ],
                "b" => [
                    "interval" => "100-500",
                    "value" => $relativaInterval2
                ],
                "c" => [
                    "interval" => "500-1000",
                    "value" => $relativaInterval3
                ]
            ];

            return json_encode([$json]);
        } else {
            return 'Error on get';
        }
    }

    public function create() {
        // create class on database
        $sql = 'INSERT INTO classes (name) VALUES (?)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $this->name);

        if($stmt->execute()) {
            return 'SUCCESS';
        } else {
            return 'ERROR';
        }
    }  

    public function update($id) {
        // update class on database
        $sql = 'UPDATE classes SET name = :name WHERE id = :id';
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
        //delete class on database
        $sql = 'DELETE FROM classes WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $id);

        if($stmt->execute()) {
            return 'SUCCESS';
        } else {
            return 'ERROR';
        }
    }

    public function addToClass($classroom, $student) {
        $sql = 'SELECT classes_id FROM classes_has_student WHERE student_id = ' . $student . ' AND classes_id = ' . $classroom;
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute()) { 
            if(!$stmt->fetchAll(PDO::FETCH_OBJ)) {
                $sql = 'INSERT INTO classes_has_student (classes_id, student_id) VALUES (?, ?)';
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(1, $classroom);
                $stmt->bindValue(2, $student);
                
                if($stmt->execute()) {
                    return 'SUCCESS';
                } else {
                    return 'ERROR';
                }
            } else {
                return 'SUCCESS';
            }
        } else {
            return 'ERROR';
        }
    }

    public function searchScore($id) {
        $sql = 'SELECT sum(student.score) / count(*) as score
                  FROM classes_has_student
            INNER JOIN classes ON classes.id = classes_has_student.classes_id
            INNER JOIN student ON student.id = classes_has_student.student_id
                 WHERE classes_has_student.classes_id = ' . $id;
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute()) { 
            return $stmt->fetch(PDO::FETCH_OBJ);
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