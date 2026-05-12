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
        btn.classList.remove("bg-assent-secondary", "text-[#fff]");
        btn.classList.add(
          "bg-white",
          "text-assent-secondary",
          "border",
          "border-[#ccc]"
        );
      });

      this.classList.remove("bg-white", "text-assent-secondary");
      this.classList.add("bg-assent-secondary", "text-[#fff]");
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
          techHTML += `<li class="px-[15px] lg:px-[20px] py-[8px] mr-1 lg:mr-2 mb-2 rounded-full text-[#fff] font-semibold cursor-pointer text-sm shadow-sm hover:shadow-md transition-all hover:-translate-y-1" style="background-color: ${color}">${tech}</li>`;
      });

      const cardHTML = `
        <div class="group flex flex-col bg-white rounded-3xl overflow-hidden shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] border border-gray-100 hover:shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)] transition-all duration-500 hover:-translate-y-2 mix ${p.category}">
            <!-- Image Section -->
            <div class="relative w-full overflow-hidden cursor-pointer">
                <img src="${p.image}" class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-110" alt="${p.title}">
                <!-- Title Overlay (Visible by default, hides on hover) -->
                <div class="absolute inset-0 flex items-center justify-center font-bold text-white text-2xl md:text-3xl bg-black/40 opacity-100 group-hover:opacity-0 transition-opacity duration-300 text-center px-4">
                    ${p.title}
                </div>
            </div>
            
            <!-- Content Section -->
            <div class="flex flex-col p-6 bg-white text-center border-t border-gray-50 flex-1">
                
                <!-- Project Title (Added to fill space elegantly) -->
                <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-assent-secondary transition-colors duration-300">${p.title}</h3>
                
                <div class="w-12 h-1 bg-assent-secondary mx-auto rounded-full mb-4"></div>

                <!-- Tech Stack -->
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-3">Technologies Used</p>
                <ul class="flex flex-wrap justify-center mb-6">
                    ${techHTML}
                </ul>
                
                <!-- Buttons -->
                <div class="flex justify-center gap-3 mt-auto">
                    ${p.githubLink ? `<a href="${p.githubLink}" target="_blank" class="px-[20px] py-[12px] lg:px-[25px] lg:py-[15px] bg-white text-[#000] border border-[#ccc] hover:bg-gray-100 shadow-sm transform transition-all duration-300 ease-in-out hover:-translate-y-1 font-bold rounded-full flex items-center gap-2">Github <i class="ri-github-fill text-xl"></i></a>` : ''}
                    ${p.previewLink ? `<a href="${p.previewLink}" target="_blank" class="px-[20px] py-[12px] lg:px-[25px] lg:py-[15px] bg-[#000] hover:bg-[#222] shadow-lg text-[#fff] transform transition-all duration-300 ease-in-out hover:-translate-y-1 font-bold rounded-full flex items-center gap-2">Preview <i class="ri-eye-fill text-xl"></i></a>` : ''}
                </div>
            </div>
        </div>
      `;
      container.innerHTML += cardHTML;
    });

    initMixitUp();

  } catch (err) {
    console.error('Error fetching projects:', err);
    container.innerHTML = '<p class="text-center w-full col-span-full text-red-500 font-bold">Failed to load projects.</p>';
  }
}
