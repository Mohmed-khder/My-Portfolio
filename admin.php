<?php
session_start();
$secret = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['password'] === $secret) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Invalid password";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin Dashboard</title>
    <link rel="stylesheet" href="dist/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .toggle-checkbox:checked {
            right: 0;
            border-color: #3b82f6;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #3b82f6;
        }
        .toggle-checkbox {
            right: 0;
            z-index: 1;
            border-color: #e5e7eb;
            transition: all 0.3s;
        }
        .toggle-label {
            width: 3rem;
            height: 1.5rem;
            background-color: #e5e7eb;
            border-radius: 9999px;
            transition: all 0.3s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 <?php echo $isLoggedIn ? 'h-screen overflow-hidden flex' : ''; ?>">

<?php if (!$isLoggedIn): ?>
    <!-- Premium Login Page -->
    <div class="relative flex items-center justify-center min-h-screen bg-gray-900 overflow-hidden w-full">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 flex items-center justify-center">
            <div class="absolute w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob translate-x-[-100px] translate-y-[-50px]"></div>
            <div class="absolute w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob animation-delay-2000 translate-x-[100px] translate-y-[50px]"></div>
            <div class="absolute w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob animation-delay-4000 translate-y-[100px]"></div>
        </div>

        <div class="relative z-10 bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-[2rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] w-96 max-w-[90%] overflow-hidden">
            <div class="text-center mb-8">
                <!-- User Profile Image -->
                <div class="relative inline-block mb-4 group">
                    <div class="absolute inset-0 rounded-full border-[3px] border-blue-400 border-dashed animate-[spin_10s_linear_infinite] opacity-50 scale-110 group-hover:border-purple-400 transition-colors"></div>
                    <img src="assets/img/home/me.jpg" alt="Mohamed Abdul Azeem" class="w-24 h-24 rounded-full object-cover shadow-2xl mx-auto border-4 border-white/10 relative z-10">
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight">Welcome Back!</h2>
                <p class="text-gray-300 mt-2 text-sm">Please verify your identity to continue.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-3 rounded-xl mb-6 text-sm text-center backdrop-blur-sm animate-pulse">
                    <i class="ri-error-warning-line mr-1"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="admin.php">
                <div class="mb-8">
                    <div class="relative group">
                        <i class="ri-lock-password-line absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-400 transition-colors text-lg"></i>
                        <input type="password" id="password" name="password" placeholder="Enter Secret Password" class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:bg-white/10 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 outline-none transition-all shadow-inner" required autocomplete="off">
                    </div>
                </div>
                <button type="submit" name="login" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-4 px-4 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(59,130,246,0.3)] flex items-center justify-center gap-3">
                    Unlock Dashboard <i class="ri-arrow-right-line text-xl"></i>
                </button>
            </form>
            
            <div class="mt-8 text-center text-xs text-gray-400 flex items-center justify-center gap-2">
                <i class="ri-shield-check-fill text-green-400"></i> Secure Admin Portal
            </div>
        </div>
    </div>
<?php else: ?>

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex-col h-full hidden md:flex shrink-0">
        <div class="p-6 flex items-center gap-3 border-b border-gray-800">
            <div class="p-2 bg-blue-600 rounded-lg">
                <i class="ri-dashboard-2-fill text-white text-xl"></i>
            </div>
            <h1 class="text-xl font-bold">Admin Panel</h1>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-xl font-medium transition-colors">
                <i class="ri-folder-open-fill text-lg"></i> Projects
            </a>
            <a href="/" target="_blank" class="flex items-center gap-3 text-gray-400 hover:bg-gray-800 hover:text-white px-4 py-3 rounded-xl font-medium transition-colors">
                <i class="ri-global-line text-lg"></i> View Site
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 px-4 py-2">
                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold">A</div>
                <div>
                    <p class="text-sm font-bold">Azeem Khder</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-100 px-6 py-4 flex justify-between items-center z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-500 hover:text-gray-900 text-2xl">
                    <i class="ri-menu-line"></i>
                </button>
                <h2 class="text-xl font-bold text-gray-800">Manage Projects</h2>
            </div>
            <div class="flex gap-3">
                <button onclick="loadProjects()" class="text-gray-500 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-all" title="Refresh">
                    <i class="ri-refresh-line text-xl"></i>
                </button>
                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold flex items-center gap-2 transition-all shadow-md hover:shadow-lg">
                    <i class="ri-add-line text-lg"></i> <span class="hidden sm:inline">New Project</span>
                </button>
                <a href="?logout=1" class="text-gray-500 hover:text-red-600 px-4 py-2 rounded-xl hover:bg-red-50 transition-all flex items-center gap-2 font-semibold">
                    <i class="ri-logout-circle-r-line text-lg"></i> <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div id="projectsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- Projects injected here -->
                <div class="col-span-full text-center py-20 text-gray-400">
                    <i class="ri-loader-4-line animate-spin text-4xl"></i>
                    <p class="mt-4 font-medium">Loading projects...</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Form -->
    <div id="projectModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl relative transform scale-95 transition-transform duration-300" id="modalContent">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center shrink-0">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2" id="modalTitle">
                    <i class="ri-folder-add-fill text-blue-600"></i> Create New Project
                </h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                <form id="addProjectForm" class="space-y-5">
                    <input type="hidden" name="id" id="projectId" value="">
                    <input type="hidden" name="secret" value="<?php echo $secret; ?>">
                    <input type="hidden" name="technologies" id="hiddenTechnologies" value="">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Project Title</label>
                            <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all font-medium text-gray-800" placeholder="e.g., E-Commerce App">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all bg-white font-medium text-gray-800">
                                <option value="category4">React / Advanced</option>
                                <option value="category3">Bootstrap</option>
                                <option value="category2">JavaScript</option>
                                <option value="category1">HTML & CSS</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Country / Region</label>
                            <select name="country" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all bg-white font-medium text-gray-800">
                                <option value="">🌍 Global / None</option>
                                <option value="assets/flag/egypt.png">🇪🇬 Egypt</option>
                                <option value="assets/flag/sar.jpg">🇸🇦 Saudi Arabia</option>
                                <option value="assets/flag/aed.jpg">🇦🇪 UAE</option>
                                <option value="assets/flag/kwd.jpg">🇰🇼 Kuwait</option>
                                <option value="assets/flag/qar.jpg">🇶🇦 Qatar</option>
                                <option value="assets/flag/bhd.jpg">🇧🇭 Bahrain</option>
                            </select>
                        </div>
                    </div>

                    <!-- Visual Tech Selector -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Technologies Used</label>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                            <div class="flex flex-wrap gap-2" id="techContainer">
                                <!-- Tech pills injected via JS -->
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2"><i class="ri-information-line"></i> Click a technology to select/deselect it.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">GitHub Link</label>
                            <div class="relative">
                                <i class="ri-github-fill absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="url" name="githubLink" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all font-medium text-gray-800" placeholder="https://github.com/...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Live Preview Link</label>
                            <div class="relative">
                                <i class="ri-link absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="url" name="previewLink" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all font-medium text-gray-800" placeholder="https://...">
                            </div>
                        </div>
                    </div>

                    <!-- Toggles -->
                    <div class="grid grid-cols-2 gap-5 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-gray-700 cursor-pointer" for="isActiveToggle">
                                <i class="ri-eye-line text-blue-600 mr-1"></i> Active (Visible)
                            </label>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="isActive" id="isActiveToggle" value="true" checked class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="isActiveToggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-gray-700 cursor-pointer" for="isFeaturedToggle">
                                <i class="ri-star-fill text-yellow-500 mr-1"></i> Featured Project
                            </label>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="isFeatured" id="isFeaturedToggle" value="true" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="isFeaturedToggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Project Image <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-6 pb-8 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all bg-gray-50 relative group cursor-pointer overflow-hidden" id="dropZone" onclick="document.getElementById('imageUpload').click()">
                            <img id="imagePreview" src="" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">
                            <div id="uploadUi" class="space-y-2 text-center relative z-10 bg-white/80 p-4 rounded-xl backdrop-blur-sm group-hover:bg-white transition-all">
                                <i class="ri-image-add-fill text-4xl text-blue-500"></i>
                                <div class="flex text-sm text-gray-600 justify-center font-medium">
                                    <span class="text-blue-600 hover:text-blue-500">Click to upload image</span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 5MB</p>
                            </div>
                            <input id="imageUpload" name="image" type="file" class="sr-only" accept="image/*" onchange="handleImage(this)">
                        </div>
                    </div>

                    <div id="statusMsg" class="hidden rounded-lg p-4 text-sm font-bold text-center"></div>

                </form>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 shrink-0 rounded-b-2xl">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl font-bold text-gray-600 hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" form="addProjectForm" id="submitBtn" class="bg-gray-900 hover:bg-black text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                    <i class="ri-save-3-line"></i> Save Project
                </button>
            </div>
            
        </div>
    </div>

    <script>
        const secret = "<?php echo $secret; ?>";
        
        // Visual Tech Selector Logic
        const techList = [
            { name: 'HTML', color: '#e44d25' },
            { name: 'CSS', color: '#264de4' },
            { name: 'JavaScript', color: '#fed600' },
            { name: 'React', color: '#60d8f1' },
            { name: 'Next.js', color: '#000000' },
            { name: 'Tailwind', color: '#39bcf9' },
            { name: 'Bootstrap', color: '#7511f7' },
            { name: 'Vite', color: '#a052f3' },
            { name: 'Redux', color: '#764abc' },
            { name: 'TypeScript', color: '#3178c6' },
            { name: 'Firebase', color: '#ffca28' },
            { name: 'Figma', color: '#f24e1e' },
            { name: 'Postman', color: '#ff6c37' },
            { name: 'Sass', color: '#cc6699' },
            { name: 'PHP', color: '#7377ad' }
        ];

        let selectedTechs = new Set();

        function renderTechSelector() {
            const container = document.getElementById('techContainer');
            container.innerHTML = '';
            document.getElementById('hiddenTechnologies').value = Array.from(selectedTechs).join(', ');
            
            techList.forEach(tech => {
                const btn = document.createElement('button');
                btn.type = 'button';
                const isSelected = selectedTechs.has(tech.name);
                
                btn.className = `px-3 py-1.5 rounded-full text-sm font-bold border-2 transition-all flex items-center gap-1.5 ${isSelected ? 'shadow-md scale-105' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300'}`;
                
                if (isSelected) {
                    btn.style.backgroundColor = tech.color;
                    btn.style.borderColor = tech.color;
                    btn.style.color = (tech.name === 'JavaScript' || tech.name === 'Firebase') ? '#000' : '#fff';
                    btn.innerHTML = `<i class="ri-check-line"></i> ${tech.name}`;
                } else {
                    btn.innerHTML = `<span class="w-2.5 h-2.5 rounded-full" style="background-color: ${tech.color}"></span> ${tech.name}`;
                }

                btn.onclick = () => {
                    if (isSelected) selectedTechs.delete(tech.name);
                    else selectedTechs.add(tech.name);
                    renderTechSelector();
                };
                
                container.appendChild(btn);
            });
        }
        renderTechSelector();

        // Modal Logic
        const modal = document.getElementById('projectModal');
        const modalContent = document.getElementById('modalContent');
        
        function openModal(isEdit = false) {
            if (!isEdit) {
                document.getElementById('addProjectForm').reset();
                document.getElementById('projectId').value = '';
                document.getElementById('modalTitle').innerHTML = '<i class="ri-folder-add-fill text-blue-600"></i> Create New Project';
                selectedTechs.clear();
                renderTechSelector();
                document.getElementById('imagePreview').classList.add('hidden');
                document.getElementById('uploadUi').classList.remove('bg-white/90');
                document.getElementById('uploadUi').classList.add('bg-white/80');
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Trigger reflow
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function editProject(id) {
            const p = allProjects.find(proj => proj.id === id);
            if (!p) return;

            document.getElementById('projectId').value = p.id;
            const form = document.getElementById('addProjectForm');
            form.elements['title'].value = p.title || '';
            form.elements['category'].value = p.category || '';
            if (form.elements['country']) form.elements['country'].value = p.country || '';
            form.elements['githubLink'].value = p.githubLink || '';
            form.elements['previewLink'].value = p.previewLink || '';

            selectedTechs.clear();
            if (p.technologies) {
                p.technologies.forEach(t => selectedTechs.add(t));
            }
            renderTechSelector();

            document.getElementById('modalTitle').innerHTML = '<i class="ri-edit-fill text-blue-600"></i> Edit Project';
            
            // Show existing image in preview
            const preview = document.getElementById('imagePreview');
            const ui = document.getElementById('uploadUi');
            if (p.image) {
                preview.src = p.image;
                preview.classList.remove('hidden');
                ui.classList.remove('bg-white/80');
                ui.classList.add('bg-white/90');
            }
            
            openModal(true);
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function handleImage(input) {
            const preview = document.getElementById('imagePreview');
            const ui = document.getElementById('uploadUi');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    ui.classList.remove('bg-white/80');
                    ui.classList.add('bg-white/90');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                ui.classList.add('bg-white/80');
            }
        }

        async function toggleStatus(id, field, currentValue) {
            try {
                const res = await fetch('api/projects.php', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        secret: secret,
                        id: id,
                        field: field,
                        value: !currentValue
                    })
                });
                if (res.ok) {
                    loadProjects(); // reload to reflect changes
                } else {
                    alert('Error updating status');
                }
            } catch (err) {
                console.error(err);
                alert('Network error');
            }
        }

        let allProjects = [];

        async function loadProjects() {
            const grid = document.getElementById('projectsGrid');
            try {
                const res = await fetch('api/projects.php');
                allProjects = await res.json();
                const projects = allProjects;
                
                grid.innerHTML = '';
                
                if (projects.length === 0) {
                    grid.innerHTML = `<div class="col-span-full text-center py-20 text-gray-500 font-medium bg-white rounded-2xl border border-dashed border-gray-300">No projects found. Create your first one!</div>`;
                    return;
                }

                projects.forEach(p => {
                    const card = document.createElement('div');
                    card.className = 'bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all relative group flex flex-col h-full';
                    
                    const techs = p.technologies.map((t, i) => {
                        const color = p.techColors && p.techColors[i] ? p.techColors[i] : '#333';
                        const textColor = (t.toLowerCase() === 'javascript' || t.toLowerCase() === 'firebase') ? '#000' : '#fff';
                        return `<span class="inline-block text-xs px-2.5 py-1 rounded-full font-bold mr-1 mb-1 shadow-sm" style="background-color: ${color}; color: ${textColor};">${t}</span>`;
                    }).join('');
                    
                    const isActive = p.isActive !== false; // default true
                    const isFeatured = p.isFeatured === true;

                    const countryBadge = p.country && p.country !== '' ? `<img src="${p.country}" class="absolute top-3 right-3 w-8 rounded-sm shadow-md z-10 bg-white" alt="Flag">` : '';

                    card.innerHTML = `
                        <div class="h-48 overflow-hidden bg-gray-100 relative shrink-0">
                            ${countryBadge}
                            <img src="${p.image}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            ${!isActive ? `<div class="absolute inset-0 bg-gray-900/60 backdrop-blur-[2px] flex items-center justify-center"><span class="bg-gray-800 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest"><i class="ri-eye-off-line"></i> Hidden</span></div>` : ''}
                            ${isFeatured ? `<div class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold shadow-md"><i class="ri-star-fill"></i> Featured</div>` : ''}
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4 z-20">
                                <div class="flex gap-2">
                                    ${p.githubLink ? `<a href="${p.githubLink}" target="_blank" class="bg-white hover:bg-gray-200 text-black w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors"><i class="ri-github-fill"></i></a>` : ''}
                                    ${p.previewLink ? `<a href="${p.previewLink}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors"><i class="ri-external-link-line"></i></a>` : ''}
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="editProject('${p.id}')" class="bg-indigo-600 hover:bg-indigo-700 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors" title="Edit Project">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button onclick="deleteProject('${p.id}')" class="bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors" title="Delete Project">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="font-black text-lg text-gray-900 mb-3 truncate" title="${p.title}">${p.title}</h3>
                            <div class="flex flex-wrap mb-4 flex-1 content-start">
                                ${techs}
                            </div>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100 grid grid-cols-2 gap-2">
                                <button onclick="toggleStatus('${p.id}', 'isActive', ${isActive})" class="flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs font-bold transition-colors ${isActive ? 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200'}">
                                    <i class="${isActive ? 'ri-eye-line' : 'ri-eye-off-line'}"></i> ${isActive ? 'Active' : 'Hidden'}
                                </button>
                                <button onclick="toggleStatus('${p.id}', 'isFeatured', ${isFeatured})" class="flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs font-bold transition-colors ${isFeatured ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100 border border-yellow-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200'}">
                                    <i class="${isFeatured ? 'ri-star-fill' : 'ri-star-line'}"></i> Featured
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(card);
                });
            } catch (err) {
                grid.innerHTML = `<div class="col-span-full text-center py-10 text-red-500 font-bold">Error loading projects</div>`;
            }
        }

        async function deleteProject(id) {
            if (!confirm('Are you sure you want to delete this project?')) return;
            try {
                const res = await fetch('api/projects.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, secret })
                });
                if (res.ok) {
                    loadProjects();
                } else {
                    alert('Error deleting project');
                }
            } catch (err) {
                console.error(err);
                alert('Network error');
            }
        }

        document.getElementById('addProjectForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const form = e.target;
            const projectId = document.getElementById('projectId').value;
            const imageInput = document.getElementById('imageUpload');

            if (!projectId && (!imageInput.files || imageInput.files.length === 0)) {
                alert("Please select a project image.");
                return;
            }

            if (selectedTechs.size === 0) {
                alert("Please select at least one technology.");
                return;
            }

            const btn = document.getElementById('submitBtn');
            const status = document.getElementById('statusMsg');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> Saving...';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            status.classList.add('hidden');
            
            const formData = new FormData(form);
            
            try {
                // Artificial delay to show the loading animation for better UX
                await new Promise(resolve => setTimeout(resolve, 800));
                
                const res = await fetch('api/projects.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                
                if (res.ok) {
                    status.innerHTML = `<i class="ri-checkbox-circle-fill"></i> Project ${projectId ? 'updated' : 'added'} successfully!`;
                    status.className = 'rounded-xl p-4 text-sm font-bold text-center bg-green-50 text-green-700 mb-4 block';
                    
                    setTimeout(() => {
                        form.reset();
                        selectedTechs.clear();
                        renderTechSelector();
                        document.getElementById('imagePreview').classList.add('hidden');
                        document.getElementById('uploadUi').classList.remove('bg-white/90');
                        document.getElementById('uploadUi').classList.add('bg-white/80');
                        closeModal();
                        loadProjects();
                        status.classList.add('hidden');
                    }, 600);
                } else {
                    status.textContent = data.error || 'Error saving project';
                    status.className = 'rounded-xl p-4 text-sm font-bold text-center bg-red-50 text-red-700 mb-4 block';
                }
            } catch (err) {
                status.textContent = 'Network error occurred';
                status.className = 'rounded-xl p-4 text-sm font-bold text-center bg-red-50 text-red-700 mb-4 block';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-3-line"></i> Save Project';
                btn.classList.remove('opacity-80', 'cursor-not-allowed');
            }
        });

        // Init
        loadProjects();
    </script>
<?php endif; ?>
</body>
</html>
