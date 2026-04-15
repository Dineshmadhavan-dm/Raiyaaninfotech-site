document
    .querySelector(".sidebar-toggle")
    .addEventListener("click", function () {
        document.querySelector(".sidebar").classList.toggle("active");
        document.querySelector(".main-content").classList.toggle("active");
    });

// Close sidebar when close button is clicked
document.querySelector(".closebtn i").addEventListener("click", function () {
    document.querySelector(".sidebar").classList.remove("active");
    document.querySelector(".main-content").classList.remove("active");
});

// Toggle submenu dropdown
document.querySelectorAll(".submenu-toggle").forEach((toggle) => {
    toggle.addEventListener("click", function () {
        const submenu = this.nextElementSibling;
        const icon = this.querySelector(".toggle-icon");

        if (submenu.classList.contains("show")) {
            submenu.classList.remove("show");
            submenu.style.height = "0";
            icon.style.transform = "rotate(0deg)";
        } else {
            submenu.classList.add("show");
            submenu.style.height = submenu.scrollHeight + "px";
            icon.style.transform = "rotate(180deg)";
        }
    });

    // Initialize collapsed style
    const submenu = toggle.nextElementSibling;
    submenu.classList.remove("show");
    submenu.style.height = "0";
});

document
    .getElementById("sidebarCollapseToggle")
    .addEventListener("click", function () {
        if (window.innerWidth > 992) {
            document.querySelector(".sidebar").classList.toggle("collapsed");
            document
                .querySelector(".main-content")
                .classList.toggle("collapsed");
        }
    });

window.addEventListener("resize", function () {
    if (window.innerWidth <= 992) {
        document.querySelector(".sidebar").classList.remove("collapsed");
        document.querySelector(".main-content").classList.remove("collapsed");
    }
});

const currentPath = window.location.pathname;
document.querySelectorAll(".sidebar-menu .nav-link").forEach((link) => {
    if (link.getAttribute("href") === currentPath) {
        link.classList.add("active");

        // If it's in a submenu, open the parent menu
        if (link.closest(".submenu")) {
            const parentToggle = link
                .closest(".nav-item")
                .previousElementSibling.querySelector(".submenu-toggle");
            if (parentToggle) {
                parentToggle.classList.add("active");
                // Ensure the submenu is visible
                const submenu = parentToggle.nextElementSibling;
                if (submenu && submenu.classList.contains("submenu")) {
                    submenu.classList.add("show");
                }
            }
        }
    }
});
