<?php
$projectsFile = __DIR__ . '/../data/projects.json';
$projects = [];

if (file_exists($projectsFile)) {
    $decoded = json_decode(file_get_contents($projectsFile), true);
    if (is_array($decoded)) {
        $projects = array_values(array_filter($decoded, function ($project) {
            return ($project['isActive'] ?? true) !== false;
        }));
    }
}

$featuredCount = count(array_filter($projects, function ($project) {
    return ($project['isFeatured'] ?? false) === true;
}));

$skills = ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'React', 'Next.js', 'Tailwind CSS', 'Bootstrap', 'Sass', 'Redux', 'Firebase', 'PHP', 'Figma', 'Git'];

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function projectStatus($project) {
    if (($project['hasIssue'] ?? false) === true) {
        return ['label' => 'Current Issue', 'class' => 'issue'];
    }
    if (($project['isInProgress'] ?? false) === true) {
        return ['label' => 'Under Work', 'class' => 'work'];
    }
    if (($project['isFeatured'] ?? false) === true) {
        return ['label' => 'Featured', 'class' => 'featured'];
    }
    return ['label' => 'Completed', 'class' => 'done'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mohamed Abdul Azeem - Portfolio PDF</title>
    <style>
        :root {
            --ink: #111827;
            --muted: #4b5563;
            --line: #d1d5db;
            --soft: #f8fafc;
            --brand: #84540f;
            --brand-soft: #f4eee7;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            margin: 0;
            background: #e5e7eb;
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.55;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            justify-content: center;
            gap: 12px;
            padding: 14px;
            background: #111827;
        }

        .toolbar button,
        .toolbar a {
            border: 0;
            background: #fff;
            color: #111827;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
        }

        .toolbar button {
            background: var(--brand);
            color: #fff;
        }

        .document {
            width: 210mm;
            min-height: 297mm;
            margin: 22px auto;
            background: #fff;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.18);
        }

        .page {
            padding: 16mm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .hero {
            display: grid;
            grid-template-columns: 1fr 58mm;
            gap: 18mm;
            align-items: center;
            padding-bottom: 12mm;
            border-bottom: 2px solid var(--line);
        }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--brand);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        h1,
        h2,
        h3,
        p {
            margin-top: 0;
        }

        h1 {
            margin-bottom: 8px;
            color: #111827;
            font-size: 42px;
            line-height: 1.05;
        }

        .role {
            margin-bottom: 16px;
            color: var(--brand);
            font-size: 18px;
            font-weight: 900;
        }

        .summary {
            margin-bottom: 0;
            color: #111827;
            font-size: 14px;
            font-weight: 500;
        }

        .profile-photo {
            width: 58mm;
            height: 72mm;
            display: block;
            object-fit: contain;
            object-position: center;
            border: 1px solid var(--line);
            background: #fff;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 16px;
            margin-top: 14mm;
        }

        .contact-item {
            color: #111827;
            font-size: 12px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .contact-item strong {
            display: block;
            color: var(--brand);
            font-size: 11px;
            text-transform: uppercase;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 14mm 0 10mm;
        }

        .stat {
            padding: 16px;
            border: 1px solid var(--line);
            background: #fff;
        }

        .stat strong {
            display: block;
            color: #111827;
            font-size: 30px;
            line-height: 1;
        }

        .stat span {
            color: var(--muted);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10mm 0 12px;
        }

        .section-title::before {
            content: "";
            width: 34px;
            height: 3px;
            background: var(--brand);
        }

        .section-title h2 {
            margin: 0;
            color: #111827;
            font-size: 23px;
            line-height: 1.1;
        }

        .skill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .skill-list li {
            padding: 7px 10px;
            border: 1px solid var(--line);
            background: var(--soft);
            color: #111827;
            font-size: 12px;
            font-weight: 900;
        }

        .timeline {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            max-width: 150mm;
        }

        .timeline-item {
            padding: 18px;
            border-left: 4px solid var(--brand);
            background: var(--soft);
        }

        .timeline-item h3 {
            margin-bottom: 6px;
            color: #111827;
            font-size: 17px;
        }

        .timeline-item p {
            margin-bottom: 0;
            color: #111827;
            font-size: 13px;
        }

        .projects-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--line);
        }

        .projects-header h2 {
            margin: 0;
            color: #111827;
            font-size: 30px;
        }

        .projects-header p {
            margin: 0;
            color: #111827;
            font-size: 12px;
            font-weight: 800;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .project-card {
            overflow: hidden;
            border: 1px solid var(--line);
            background: #fff;
            page-break-inside: avoid;
        }

        .project-image-wrap {
            position: relative;
            height: 155px;
            background: #f3f4f6;
            overflow: hidden;
        }

        .project-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: top;
        }

        .project-status {
            position: absolute;
            left: 10px;
            top: 10px;
            padding: 5px 8px;
            background: #111827;
            color: #fff;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .project-status.featured {
            background: var(--brand);
        }

        .project-status.work {
            background: #92400e;
        }

        .project-status.issue {
            background: #991b1b;
        }

        .project-body {
            padding: 13px;
        }

        .project-body h3 {
            margin-bottom: 8px;
            color: #111827;
            font-size: 16px;
            line-height: 1.2;
        }

        .techs {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 9px;
        }

        .tech {
            padding: 4px 7px;
            background: var(--brand-soft);
            color: #111827;
            font-size: 10px;
            font-weight: 900;
        }

        .project-link {
            display: block;
            color: #111827;
            font-size: 10px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .footer-note {
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid var(--line);
            color: #111827;
            font-size: 12px;
            font-weight: 700;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .document {
                width: 210mm;
                margin: 0;
                box-shadow: none;
            }

            .page {
                padding: 16mm;
            }

            h1,
            h2,
            h3,
            p,
            span,
            li,
            a,
            .summary,
            .role,
            .contact-item,
            .timeline-item p,
            .projects-header p,
            .project-link {
                color: #111827 !important;
            }

            .eyebrow,
            .contact-item strong {
                color: #84540f !important;
            }

            a {
                color: #111827 !important;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Download PDF</button>
        <a href="../index.html">Back to Portfolio</a>
    </div>

    <main class="document">
        <section class="page">
            <div class="hero">
                <div>
                    <p class="eyebrow">Frontend Portfolio</p>
                    <h1>Mohamed Abdul Azeem</h1>
                    <p class="role">Frontend Engineer | React & Next.js Specialist</p>
                    <p class="summary">Frontend Engineer specialized in building scalable, responsive, and high-performance web applications using React.js, Next.js, and modern frontend technologies. Experienced in production-level platforms across tourism, healthcare, booking systems, and e-commerce.</p>
                    <div class="contact-grid">
                        <div class="contact-item"><strong>Email</strong>123medoabdo@gmail.com</div>
                        <div class="contact-item"><strong>Phone</strong>(+20) 01028768312</div>
                        <div class="contact-item"><strong>LinkedIn</strong>linkedin.com/in/mohamed-abdul-azeem-khder</div>
                        <div class="contact-item"><strong>GitHub</strong>github.com/Mohmed-khder</div>
                    </div>
                </div>
                <img src="../assets/img/home/me.jpg" class="profile-photo" alt="Mohamed Abdul Azeem">
            </div>

            <div class="stats">
                <div class="stat">
                    <strong><?php echo count($projects); ?>+</strong>
                    <span>Visible Projects</span>
                </div>
                <div class="stat">
                    <strong><?php echo $featuredCount; ?></strong>
                    <span>Featured Builds</span>
                </div>
                <div class="stat">
                    <strong>5</strong>
                    <span>Business Markets</span>
                </div>
            </div>

            <div class="section-title">
                <h2>Core Skills</h2>
            </div>
            <ul class="skill-list">
                <?php foreach ($skills as $skill): ?>
                    <li><?php echo e($skill); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="page">
            <div class="section-title">
                <h2>Experience & Education</h2>
            </div>
            <div class="timeline">
                <div class="timeline-item">
                    <h3>Frontend Engineer - WEMISC Company</h3>
                    <p>Developed responsive frontend applications using React.js and Next.js, scalable UI systems, and modern business solutions.</p>
                </div>
                <div class="timeline-item">
                    <h3>Freelance Frontend Developer - Eyosyst Company</h3>
                    <p>Worked on project-based frontend development, REST API integrations, responsive layouts, and production UI optimization.</p>
                </div>
                <div class="timeline-item">
                    <h3>Communication & Computer Engineering - Tanta University</h3>
                    <p>Bachelor's Degree with coursework focused on frontend development and artificial intelligence.</p>
                </div>
                <div class="timeline-item">
                    <h3>React Frontend Web Developer - MCIT Digital Egypt Generation</h3>
                    <p>Diploma and internship specialization in React frontend web development.</p>
                </div>
            </div>
        </section>

        <section class="page">
            <div class="projects-header">
                <div>
                    <p class="eyebrow">Selected Work</p>
                    <h2>Projects</h2>
                </div>
                <p><?php echo count($projects); ?> projects included</p>
            </div>

            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                    <?php $status = projectStatus($project); ?>
                    <article class="project-card">
                        <div class="project-image-wrap">
                            <img src="../<?php echo e($project['image'] ?? 'assets/img/home/me.jpg'); ?>" class="project-image" alt="<?php echo e($project['title'] ?? 'Project'); ?>">
                            <span class="project-status <?php echo e($status['class']); ?>"><?php echo e($status['label']); ?></span>
                        </div>
                        <div class="project-body">
                            <h3><?php echo e($project['title'] ?? 'Untitled Project'); ?></h3>
                            <div class="techs">
                                <?php foreach (($project['technologies'] ?? []) as $technology): ?>
                                    <span class="tech"><?php echo e($technology); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php if (!empty($project['previewLink'])): ?>
                                <a class="project-link" href="<?php echo e($project['previewLink']); ?>"><?php echo e($project['previewLink']); ?></a>
                            <?php else: ?>
                                <span class="project-link">Preview link available on request</span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <p class="footer-note">Prepared for client review. Project availability may change depending on maintenance, hosting, or active development status.</p>
        </section>
    </main>
</body>
</html>
