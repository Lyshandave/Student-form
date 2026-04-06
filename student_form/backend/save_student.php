<?php
declare(strict_types=1);

require_once __DIR__ . '/StudentRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

$firstname  = trim($_POST['fname']  ?? '');
$lastname   = trim($_POST['lname']  ?? '');
$student_id = trim($_POST['studid'] ?? '');
$grade      = $_POST['grade'] ?? '';

// Validate
$errors = [];
if ($firstname  === '') $errors[] = 'First name is required.';
if ($lastname   === '') $errors[] = 'Last name is required.';
if ($student_id === '') $errors[] = 'Student ID is required.';
if ($grade === '' || !is_numeric($grade)) {
    $errors[] = 'A valid grade is required.';
} elseif ((int)$grade < 0 || (int)$grade > 100) {
    $errors[] = 'Grade must be between 0 and 100.';
}

if (!empty($errors)) {
    $msg = urlencode(implode(' ', $errors));
    header("Location: ../index.php?error={$msg}");
    exit();
}

// Persist
$repo = new StudentRepository();
$saved = $repo->save($firstname, $lastname, $student_id, (int)$grade);

if ($saved) {
    header('Location: ../index.php?success=1');
} else {
    header('Location: ../index.php?error=Failed+to+save+record.+Please+try+again.');
}
exit();
