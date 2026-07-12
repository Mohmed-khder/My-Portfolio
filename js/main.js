// filter projects - now initialized after fetch
let mixer;

function initMixitUp() {
  mixer = mixitup(".mix-container");

  document.querySelectorAll(".filter-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const activeButton = document.querySelector(".filter-btn.active");
      if (activeButton) activeButton.classList.remove("active");
      this.classList.add("active");
    });
  });
}

//change active buttons
document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".filter-btn");

  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      buttons.forEach((btn) => {
        btn.classList.remove("bg-gray-950", "text-white", "border-gray-950", "active");
        btn.classList.add(
          "bg-white",
          "text-gray-700",
          "border",
          "border-gray-200"
        );
      });

      this.classList.remove("bg-white", "text-gray-700", "border-gray-200");
      this.classList.add("bg-gray-950", "text-white", "border-gray-950", "active");
    });
  });
});

// up and down button
let btn = document.querySelector(".top");
window.onscroll = function () {
  if (window.scrollY >= 600) {
    btn.style.display = "block";
  } else {
    btn.style.display = "none";
  }
};

btn.onclick = function () {
  window.scrollTo({
    left: 0,
    top: 0,
    behavior: "smooth",
  });
};

// notification message
setTimeout(function () {
  var notification = document.getElementById("Notification");
  if(notification) notification.classList.add("show");
}, 1000);

function closeNotification() {
  var notification = document.getElementById("Notification");
  if(notification) notification.classList.remove("show");
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("contactForm");
  if(!form) return;
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const messageInput = document.getElementById("message");
  const submitButton = document.getElementById("submitButton");
  const formStatus = document.getElementById("formStatus");
  const messageCount = document.getElementById("messageCount");

  messageInput.addEventListener("input", function () {
    const count = this.value.length;
    messageCount.textContent = count;
    if (count > 20) {
      this.value = this.value.substring(0, 20);
      messageCount.textContent = 20;
    }

    if (this.value.length < 10) {
      showError(this, "Message must be at least 10 characters long");
    } else if (this.value.length > 20) {
      showError(this, "Message must be less than 20 characters");
    } else {
      hideError(this);
    }
  });

  function showError(element, message) {
    const errorSpan = document.getElementById(element.id + "Error");
    errorSpan.textContent = message;
    errorSpan.classList.remove("hidden");
    element.classList.add("border-red-500", "bg-red-50");
    element.classList.remove("border-[#ccc]");
  }

  // Function to hide error
  function hideError(element) {
    const errorSpan = document.getElementById(element.id + "Error");
    errorSpan.classList.add("hidden");
    element.classList.remove("border-red-500", "bg-red-50");
    element.classList.add("border-[#ccc]");
  }

  nameInput.addEventListener("input", function () {
    if (this.value.length < 2) {
      showError(this, "Name must be at least 2 characters long");
    } else if (this.value.length > 50) {
      showError(this, "Name must be less than 50 characters");
    } else {
      hideError(this);
    }
  });

  emailInput.addEventListener("input", function () {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(this.value)) {
      showError(this, "Please enter a valid email address");
    } else {
      hideError(this);
    }
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    hideError(nameInput);
    hideError(emailInput);
    hideError(messageInput);

    let isValid = true;

    if (nameInput.value.length < 2 || nameInput.value.length > 50) {
      showError(nameInput, "Name must be between 2 and 50 characters");
      isValid = false;
    }

    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(emailInput.value)) {
      showError(emailInput, "Please enter a valid email address");
      isValid = false;
    }

    if (messageInput.value.length < 10 || messageInput.value.length > 20) {
      showError(messageInput, "Message must be between 10 and 20 characters");
      isValid = false;
    }

    if (!isValid) {
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = "Sending...";

    const formData = new FormData(this);

    fetch("send_email.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        formStatus.classList.remove("hidden", "bg-red-100", "bg-green-100");
        if (data.status === "success") {
          formStatus.classList.add("bg-green-100", "text-green-700");
          formStatus.textContent = "Message sent successfully!";
          form.reset();
          messageCount.textContent = "0";
        } else {
          formStatus.classList.add("bg-red-100", "text-red-700");
          formStatus.textContent =
            data.message || "Error sending message. Please try again.";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        formStatus.classList.remove("hidden", "bg-green-100");
        formStatus.classList.add("bg-red-100", "text-red-700");
        formStatus.textContent = "Error sending message. Please try again.";
      })
      .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.textContent = "Send Message";
        // Scroll status into view
        formStatus.scrollIntoView({ behavior: "smooth", block: "nearest" });
      });
  });
});

window.addEventListener("load", async () => {
  // Fix loader: smooth transition without 2.5s delay
  const loader = document.getElementById("loading");
  const dataContainer = document.getElementById("data");
  
  setTimeout(() => {
    // Quick fade out
    if(loader) {
        loader.style.opacity = "0";
        loader.style.transition = "opacity 0.5s ease";
    }
    
    setTimeout(() => {
      if(loader) loader.style.display = "none";
      if(dataContainer) dataContainer.style.display = "block";
    }, 500);
  }, 2500);

  // Fetch projects dynamically
  await fetchAndRenderProjects();
});

async function fetchAndRenderProjects() {
  const container = document.querySelector('.mix-container .grid');
  if (!container) return;

  try {
    let allProjects;
    try {
        // Try to fetch from PHP API
        let res = await fetch('api/projects.php');
        if (!res.ok) throw new Error("API response not ok");
        // If hosted on Netlify, this will return the raw PHP text and throw a SyntaxError here
        allProjects = await res.json(); 
    } catch (apiError) {
        console.warn('PHP API unavailable or returned non-JSON. Falling back to static JSON.', apiError);
        // Fallback to static JSON
        let fallbackRes = await fetch('data/projects.json');
        allProjects = await fallbackRes.json();
    }
    
    const projects = allProjects.filter(p => p.isActive !== false);
    
    container.innerHTML = '';
    
    projects.forEach(p => {
      let techHTML = '';
      p.technologies.forEach((tech, index) => {
          const color = (p.techColors && p.techColors[index]) ? p.techColors[index] : '#333';
          const textColor = (tech.toLowerCase() === 'javascript' || tech.toLowerCase() === 'firebase') ? '#000' : '#fff';
          techHTML += `<li class="px-3 py-1 rounded-full font-bold text-[11px] shadow-sm ring-1 ring-black/5" style="background-color: ${color}; color: ${textColor};">${tech}</li>`;
      });

      const countryBadge = p.country && p.country !== '' ? `<img src="${p.country}" class="w-8 h-6 object-cover rounded-[2px] shadow-sm ring-1 ring-black/10" alt="Project country">` : '';
      const techCount = p.technologies.length;
      const hasIssue = p.hasIssue === true;
      const isInProgress = p.isInProgress === true;
      const isUnavailable = hasIssue || isInProgress;
      const projectStatusRibbon = hasIssue
        ? `<div class="absolute inset-x-0 bottom-0 z-20 bg-red-600 px-4 py-2 text-center text-xs font-black uppercase tracking-wider text-white shadow-lg"><i class="ri-error-warning-fill"></i> Current Issue</div>`
        : (isInProgress ? `<div class="absolute inset-x-0 bottom-0 z-20 bg-orange-500 px-4 py-2 text-center text-xs font-black uppercase tracking-wider text-white shadow-lg"><i class="ri-tools-fill"></i> Under Work</div>` : '');
      const projectStatusOverlay = hasIssue
        ? `<div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-950/65 px-6 text-center backdrop-blur-[2px]">
            <div class="border border-white/20 bg-white/10 px-5 py-4 shadow-xl">
              <i class="ri-error-warning-fill block text-3xl text-red-300 mb-2"></i>
              <p class="text-sm font-black uppercase tracking-wider text-white">Temporarily Unavailable</p>
              <p class="mt-1 text-xs font-semibold text-red-100">This project has a current issue.</p>
            </div>
          </div>`
        : (isInProgress ? `<div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-950/55 px-6 text-center backdrop-blur-[2px]">
            <div class="border border-white/20 bg-white/10 px-5 py-4 shadow-xl">
              <i class="ri-tools-fill block text-3xl text-orange-300 mb-2"></i>
              <p class="text-sm font-black uppercase tracking-wider text-white">Under Work</p>
              <p class="mt-1 text-xs font-semibold text-orange-100">Preview is disabled for now.</p>
            </div>
          </div>` : '');
      const previewButton = p.previewLink
        ? (isUnavailable
            ? `<span aria-disabled="true" class="min-h-12 cursor-not-allowed bg-gray-200 px-4 py-3 text-center text-sm font-black text-gray-500 flex items-center justify-center gap-2 opacity-80"><i class="ri-lock-2-fill text-lg"></i> Preview Disabled</span>`
            : `<a href="${p.previewLink}" target="_blank" class="min-h-12 bg-gray-950 px-4 py-3 text-center text-sm font-black text-white transition-all duration-300 hover:bg-assent-secondary flex items-center justify-center gap-2"><i class="ri-eye-fill text-lg"></i> Live Preview</a>`)
        : '';
      const titleOffsetClass = isUnavailable ? 'bottom-12' : 'bottom-4';

      const cardHTML = `
        <article class="group flex h-full flex-col overflow-hidden bg-white border border-gray-200 shadow-sm hover:shadow-[0_22px_55px_rgba(15,23,42,0.12)] transition-all duration-500 hover:-translate-y-1 mix ${p.category}">
            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                <img src="${p.image}" class="h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105" alt="${p.title}">
                <div class="absolute inset-x-0 top-0 z-30 flex items-start justify-between gap-3 p-4">
                    ${p.isFeatured ? `<span class="inline-flex items-center gap-1.5 bg-white/95 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider text-gray-950 shadow-sm"><i class="ri-star-fill text-yellow-500"></i> Featured</span>` : `<span class="inline-flex items-center bg-white/90 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider text-gray-700 shadow-sm">Project</span>`}
                    ${countryBadge}
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-gray-950/10 to-transparent opacity-80"></div>
                ${projectStatusOverlay}
                <div class="absolute ${titleOffsetClass} left-4 right-4 z-20 flex items-end justify-between gap-3">
                    <h3 class="text-xl sm:text-2xl font-black leading-tight text-white drop-shadow-md">${p.title}</h3>
                    <span class="shrink-0 bg-white/95 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider text-gray-950">${techCount} Tech</span>
                </div>
                <div class="z-30">
                    ${projectStatusRibbon}
                </div>
            </div>
            
            <div class="flex flex-1 flex-col p-5 sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-assent-secondary">Case Study</p>
                        <p class="mt-1 text-sm font-semibold text-gray-500">Frontend implementation</p>
                    </div>
                    <i class="ri-arrow-right-up-line text-2xl text-gray-300 transition-colors duration-300 group-hover:text-assent-secondary"></i>
                </div>

                <ul class="flex flex-wrap mb-6 gap-2">
                    ${techHTML}
                </ul>
                
                <div class="mt-auto grid ${p.githubLink && p.previewLink ? 'grid-cols-2' : 'grid-cols-1'} gap-3">
                    ${p.githubLink ? `<a href="${p.githubLink}" target="_blank" class="min-h-12 border border-gray-200 bg-white px-4 py-3 text-center text-sm font-black text-gray-900 transition-all duration-300 hover:border-gray-950 hover:bg-gray-950 hover:text-white flex items-center justify-center gap-2"><i class="ri-github-fill text-lg"></i> Github</a>` : ''}
                    ${previewButton}
                </div>
            </div>
        </article>
      `;
      container.innerHTML += cardHTML;
    });

    initMixitUp();

  } catch (err) {
    console.error('Error fetching projects:', err);
    container.innerHTML = '<p class="text-center w-full col-span-full text-red-500 font-bold">Failed to load projects.</p>';
  }
}

// Mobile menu toggle
document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuBtn = document.getElementById("mobileMenuBtn");
  const mobileMenu = document.getElementById("mobileMenu");
  const menuIcon = document.getElementById("menuIcon");
  const mobileLinks = document.querySelectorAll(".mobile-link");

  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener("click", function () {
      const isOpen = !mobileMenu.classList.contains("opacity-0");
      
      if (isOpen) {
        // Close menu
        mobileMenu.classList.add("opacity-0", "invisible", "scale-95");
        mobileMenu.classList.remove("opacity-100", "visible", "scale-100");
        menuIcon.classList.remove("ri-close-line");
        menuIcon.classList.add("ri-menu-4-line");
      } else {
        // Open menu
        mobileMenu.classList.remove("opacity-0", "invisible", "scale-95");
        mobileMenu.classList.add("opacity-100", "visible", "scale-100");
        menuIcon.classList.remove("ri-menu-4-line");
        menuIcon.classList.add("ri-close-line");
      }
    });

    // Close menu when clicking a link
    mobileLinks.forEach(link => {
      link.addEventListener("click", () => {
        mobileMenu.classList.add("opacity-0", "invisible", "scale-95");
        mobileMenu.classList.remove("opacity-100", "visible", "scale-100");
        menuIcon.classList.remove("ri-close-line");
        menuIcon.classList.add("ri-menu-4-line");
      });
    });
  }
});
