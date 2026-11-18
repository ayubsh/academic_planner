<?php
// Completion statistics for one student: personal tasks + assignments in enrolled courses (FR-17).
function get_completion($conn, $uid) {
    $t = db_one($conn, "SELECT COUNT(*) AS total, COALESCE(SUM(completed), 0) AS done FROM tasks WHERE user_id = ?", "i", [$uid]);
    $a = db_one($conn,
        "SELECT COUNT(*) AS total, COUNT(s.id) AS done
         FROM assignments a
         JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
         LEFT JOIN assignment_status s ON s.assignment_id = a.id AND s.student_id = ?",
        "ii", [$uid, $uid]);
    $total = (int) $t['total'] + (int) $a['total'];
    $done = (int) $t['done'] + (int) $a['done'];
    return [
        'total' => $total,
        'done' => $done,
        'pending' => $total - $done,
        'percent' => $total > 0 ? (int) round($done / $total * 100) : 0,
    ];
}
