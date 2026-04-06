<?php
declare(strict_types=1);

require_once __DIR__ . '/backend/StudentRepository.php';

// ── Data ────────────────────────────────────────────────────
$repo     = new StudentRepository();
$students = $repo->all();
$stats    = $repo->stats($students);

// ── Flash messages from redirects ────────────────────────────
$success  = isset($_GET['success']);
$error    = isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error'])) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grade Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="frontend/css/style.css">
</head>
<body>

    <!-- ── Header ─────────────────────────────────────────── -->
    <header class="site-header">
        <div class="site-header__inner">
            <div class="site-header__crest" aria-hidden="true">🎓</div>
            <div class="site-header__wordmark">
                <div class="site-header__title">Student Grade Management</div>
                <div class="site-header__subtitle">Academic Records Office</div>
            </div>
            <div class="site-header__tag">AY 2024–2025</div>
        </div>
    </header>

    <!-- ── Main layout ────────────────────────────────────── -->
    <main class="layout">

        <!-- ── Left: Entry form ───────────────────────────── -->
        <aside>
            <div class="panel panel--form">
                <div class="section-label">Enroll Student</div>

                <?php if ($success): ?>
                    <div class="alert alert--success" role="alert">
                        <span class="alert__icon">✓</span>
                        <span class="alert__text">Student record saved successfully.</span>
                    </div>
                <?php elseif ($error !== ''): ?>
                    <div class="alert alert--error" role="alert">
                        <span class="alert__icon">!</span>
                        <span class="alert__text"><?= $error ?></span>
                    </div>
                <?php endif; ?>

                <form class="js-student-form" method="POST" action="backend/save_student.php" novalidate>

                    <div class="form__group">
                        <label class="form__label" for="fname">First Name</label>
                        <input class="form__input" type="text" id="fname" name="fname"
                               placeholder="e.g. Maria Clara" autocomplete="given-name" required>
                    </div>

                    <div class="form__group">
                        <label class="form__label" for="lname">Last Name</label>
                        <input class="form__input" type="text" id="lname" name="lname"
                               placeholder="e.g. Reyes" autocomplete="family-name" required>
                    </div>

                    <div class="form__group">
                        <label class="form__label" for="studid">Student ID</label>
                        <input class="form__input" type="text" id="studid" name="studid"
                               placeholder="e.g. 2024-00123" required>
                    </div>

                    <div class="form__group">
                        <label class="form__label" for="grade">
                            Grade &nbsp;<span class="form__label-note">(0 – 100)</span>
                        </label>
                        <input class="form__input" type="number" id="grade" name="grade"
                               min="0" max="100" placeholder="e.g. 92" required>
                        <div class="form__hint">Score ≥ 75 is passing &middot; &lt; 75 = 5.00 (Failed)</div>
                    </div>

                    <button class="btn--primary" type="submit">Save Record</button>

                </form>
            </div>
        </aside>

        <!-- ── Right: Records table ───────────────────────── -->
        <section>
            <div class="panel">
                <div class="section-label">Student Records</div>

                <!-- Stats -->
                <div class="stats">
                    <div class="stat">
                        <div class="stat__value"><?= $stats['total'] ?></div>
                        <div class="stat__label">Enrolled</div>
                    </div>
                    <div class="stat">
                        <div class="stat__value stat__value--passed"><?= $stats['passed'] ?></div>
                        <div class="stat__label">Passed</div>
                    </div>
                    <div class="stat">
                        <div class="stat__value stat__value--failed"><?= $stats['failed'] ?></div>
                        <div class="stat__label">Failed</div>
                    </div>
                    <div class="stat">
                        <div class="stat__value stat__value--avg"><?= $stats['average'] ?></div>
                        <div class="stat__label">Class Avg</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>ID No.</th>
                                <th>Score</th>
                                <th>Equivalent</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state__icon">📋</div>
                                        <div class="empty-state__title">No records yet</div>
                                        <p class="empty-state__body">
                                            Use the form on the left to add<br>your first student record.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $row):
                                $grade  = (int) $row['Grade'];
                                $eq     = grade_equivalent($grade);
                                $date   = format_date($row['reg_date'] ?? null);
                            ?>
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <div class="avatar">
                                            <?= initials($row['Firstname'], $row['Lastname']) ?>
                                        </div>
                                        <div>
                                            <div class="student-name">
                                                <?= h($row['Firstname']) ?> <?= h($row['Lastname']) ?>
                                            </div>
                                            <div class="student-date"><?= $date ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="student-id"><?= h($row['Student_Id']) ?></span>
                                </td>
                                <td>
                                    <span class="grade-score"><?= $grade ?></span>
                                </td>
                                <td>
                                    <div class="eq-badge <?= $eq['css'] ?>">
                                        <span class="eq-badge__value"><?= $eq['value'] ?></span>
                                        <span class="eq-badge__label"><?= $eq['label'] ?></span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>

    </main>

    <script src="frontend/js/main.js" defer></script>
</body>
</html>
