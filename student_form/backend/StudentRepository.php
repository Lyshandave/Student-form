<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

class StudentRepository {

    private mysqli $db;

    public function __construct() {
        $this->db = db_connect();
    }

    /** Insert a new student record. Returns true on success. */
    public function save(string $firstname, string $lastname, string $student_id, int $grade): bool {
        $stmt = $this->db->prepare(
            'INSERT INTO ID (Firstname, Lastname, Student_Id, Grade) VALUES (?, ?, ?, ?)'
        );
        // Grade column is VARCHAR(5) in the schema — bind as string to avoid type mismatch
        $gradeStr = (string) $grade;
        $stmt->bind_param('ssss', $firstname, $lastname, $student_id, $gradeStr);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Fetch all students ordered by most recent entry. */
    public function all(): array {
        $result = $this->db->query(
            'SELECT Firstname, Lastname, Student_Id, Grade, reg_date FROM ID ORDER BY reg_date DESC'
        );
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** Compute summary statistics from a list of student rows. */
    public function stats(array $rows): array {
        $total  = count($rows);
        $passed = 0;
        $sum    = 0;

        foreach ($rows as $row) {
            $g    = (int) $row['Grade'];
            $sum += $g;
            if ($g >= 75) $passed++;
        }

        return [
            'total'   => $total,
            'passed'  => $passed,
            'failed'  => $total - $passed,
            'average' => $total > 0 ? round($sum / $total, 1) : 0,
        ];
    }

    public function __destruct() {
        $this->db->close();
    }
}
