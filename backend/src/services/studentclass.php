<?php 

$option = $_POST['option'];
$data = $_POST['payload'] !== null ? $_POST['payload'] : null;

switch($option) {
    case 'GET':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $result = $studentClass->get();
        echo json_encode($result);
        break;
    case 'GET_BY_ID':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $result = $studentClass->getById($data['id']);
        echo json_encode($result);
        break;
    case 'GET_STUDENTS_CLASS_BY_ID':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $result = $studentClass->getStudentsByClassroom($data['id']);
        echo json_encode($result);
        break;
    case 'GET_STUDENTS_CLASS_BY_INTERVAL':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $result = $studentClass->getStudentsByClassroomInterval($data['id']);
        echo json_encode($result);
        break;  
    case 'GET_STUDENTS_BY_NAME':
        require(realpath(dirname(__DIR__)) . '/class/Student.php');
        $student = new Student();
        $student->setName($data['name']);
        $result = $student->getByName(true);
        echo json_encode($result);
        break;
    case 'POST':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $studentClass->setName($data['name']);
        $result = $studentClass->create();
        echo json_encode($result);
        break;
    case 'PUT':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $studentClass->setName($data['name']);
        $result = $studentClass->update($data['id']);
        echo json_encode($result);
        break;
    case 'DELETE':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        $result = $studentClass->delete($data['id']);
        echo json_encode($result);
        break;
    case 'SEARCH_STUDENT':
        require(realpath(dirname(__DIR__)) . '/class/Student.php');
        $student = new Student();
        echo json_encode($student->search($data['code']));
        break;
    case 'ADD_TO_CLASS':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        echo json_encode($studentClass->addToClass($data['classroom'], $data['studentId']));
        break;
    case 'SEARCH_SCORE_PER_CLASSROOM':
        require(realpath(dirname(__DIR__)) . '/class/StudentClass.php');
        $studentClass = new StudentClass();
        echo json_encode($studentClass->searchScore($data['classroom']));
        break;    
    default:
        echo 'Nothing';
        break;
}
