<?php 

require(realpath(dirname(__DIR__)) . '/class/Institution.php');

$option = $_POST['option'];
$data = $_POST['payload'] !== null ? $_POST['payload'] : null;


switch($option) {
    case 'GET':
        $institution = new Institution();
        $result = $institution->get();
        echo json_encode($result);
        break;
    case 'GET_BY_ID':
        $institution = new Institution();
        $result = $institution->getById($data['id']);
        echo json_encode($result);
        break;
    case 'GET_STUDENTS_PER_INSTITUTION':
        $institution = new Institution('', '');
        $result = $institution->getByInstitution($data['id']);
        echo json_encode($result);
        break;
    case 'POST':
        $institution = new Institution();
        $institution->setName($data['name']);
        $result = $institution->create();
        echo json_encode($result);
        break;
    case 'PUT':
        $institution = new Institution();
        $institution->setName($data['name']);
        $result = $institution->update($data['id']);
        echo json_encode($result);
        break;
    case 'DELETE':
        $institution = new Institution();
        $result = $institution->delete($data['id']);
        echo json_encode($result);
        break;
    default:
        echo 'Nothing';
        break;
}
