<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$dataFile = '../data/projects.json';

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// GET all projects
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!file_exists($dataFile)) {
        echo json_encode([]);
        exit;
    }
    $json = file_get_contents($dataFile);
    echo $json;
    exit;
}

// Check admin auth for POST/DELETE (very simple via header or post param, but let's just use POST for simplicity here)
// Realistically, for an admin panel, we'll verify via a secret
$secret = 'admin123'; // The default secret

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $providedSecret = $_POST['secret'] ?? '';
    if ($providedSecret !== $secret) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? 'category4';
    $githubLink = $_POST['githubLink'] ?? '';
    $previewLink = $_POST['previewLink'] ?? '';
    $isActive = isset($_POST['isActive']) && $_POST['isActive'] === 'true';
    $isFeatured = isset($_POST['isFeatured']) && $_POST['isFeatured'] === 'true';
    
    
    // Parse technologies (comma separated)
    $techs = array_map('trim', explode(',', $_POST['technologies'] ?? ''));
    $techs = array_filter($techs); // remove empty
    
    // Default colors for common techs
    $techColors = [];
    $colorMap = [
        "html" => "#e44d25",
        "css" => "#264de4",
        "javascript" => "#fed600",
        "js" => "#fed600",
        "react" => "#60d8f1",
        "vite" => "#a052f3",
        "tailwind" => "#39bcf9",
        "bootstrap" => "#7511f7",
        "php" => "#7377ad",
        ".net" => "#4e2acd",
        "spring" => "#77bc1f"
    ];
    
    foreach ($techs as $tech) {
        $lowerTech = strtolower($tech);
        $techColors[] = $colorMap[$lowerTech] ?? "#000000";
    }

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/projects/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'assets/img/projects/' . $fileName;
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to upload image"]);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Image is required"]);
        exit;
    }

    // Load existing
    $projects = [];
    if (file_exists($dataFile)) {
        $projects = json_decode(file_get_contents($dataFile), true);
        if (!is_array($projects)) $projects = [];
    }

    // Generate new ID
    $maxId = 0;
    foreach ($projects as $p) {
        if ((int)$p['id'] > $maxId) $maxId = (int)$p['id'];
    }

    $newProject = [
        "id" => (string)($maxId + 1),
        "title" => $title,
        "image" => $imagePath,
        "category" => $category,
        "technologies" => array_values($techs),
        "techColors" => $techColors,
        "githubLink" => $githubLink,
        "previewLink" => $previewLink,
        "isActive" => $isActive,
        "isFeatured" => $isFeatured
    ];

    array_unshift($projects, $newProject); // Add to beginning

    if (file_put_contents($dataFile, json_encode($projects, JSON_PRETTY_PRINT))) {
        echo json_encode(["status" => "success", "project" => $newProject]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to save project"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Read input stream
    $input = json_decode(file_get_contents("php://input"), true);
    $providedSecret = $input['secret'] ?? '';
    if ($providedSecret !== $secret) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    $id = $input['id'] ?? '';
    
    $projects = [];
    if (file_exists($dataFile)) {
        $projects = json_decode(file_get_contents($dataFile), true);
        if (!is_array($projects)) $projects = [];
    }
    
    $initialCount = count($projects);
    $projects = array_filter($projects, function($p) use ($id) {
        return $p['id'] !== $id;
    });
    
    // Re-index array
    $projects = array_values($projects);

    if (count($projects) < $initialCount) {
        file_put_contents($dataFile, json_encode($projects, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Project not found"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (($input['secret'] ?? '') !== $secret) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    $id = $input['id'] ?? '';
    $field = $input['field'] ?? '';
    $value = $input['value'] ?? false;
    
    if (!in_array($field, ['isActive', 'isFeatured'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid field"]);
        exit;
    }

    $projects = [];
    if (file_exists($dataFile)) {
        $projects = json_decode(file_get_contents($dataFile), true);
        if (!is_array($projects)) $projects = [];
    }

    $found = false;
    foreach ($projects as &$p) {
        if ($p['id'] === $id) {
            $p[$field] = $value;
            $found = true;
            break;
        }
    }

    if ($found) {
        file_put_contents($dataFile, json_encode($projects, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Project not found"]);
    }
    exit;
}
