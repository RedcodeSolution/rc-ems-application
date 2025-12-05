// Modal Functions
window.openEmployeeModal = function () {
    document.getElementById("employeeModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeEmployeeModal = function () {
    document.getElementById("employeeModal").classList.remove("active");
    document.body.style.overflow = "auto";
    document.getElementById("employeeForm").reset();
    const errorMessage = document.querySelector(
        "#employeeModal .error-message"
    );
    if (errorMessage) errorMessage.remove();
};

document
    .getElementById("employeeModal")
    .addEventListener("click", function (e) {
        if (e.target === this) window.closeEmployeeModal();
    });
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") window.closeEmployeeModal();
});

// Profile photo preview for add
if (
    document.getElementById("photoPreview") &&
    document.getElementById("profile_photo")
) {
    document
        .getElementById("photoPreview")
        .addEventListener("click", function () {
            document.getElementById("profile_photo").click();
        });
    document
        .getElementById("profile_photo")
        .addEventListener("change", function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById("photoPreview");
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert("File size must be less than 2MB");
                    this.value = "";
                    return;
                }
                if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
                    alert("Please select a valid image file (JPG, PNG, GIF)");
                    this.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `
                <div class="photo-placeholder">
                    <i class="fas fa-user-circle"></i>
                    <span>Choose Photo</span>
                </div>
            `;
            }
        });
}

// Edit modal
let currentEditEmployeeId = null;
window.openEditModal = function (
    employeeId,
    employeeName,
    email,
    contactNo,
    employeeType,
    role,
    departmentId,
    adminId,
    employeeStatus,
    paidStatus,
    profilePhotoUrl
) {
    currentEditEmployeeId = employeeId;
    document.getElementById("edit_employee_id").value = employeeId || "";
    document.getElementById("edit_employee_name").value = employeeName || "";
    document.getElementById("edit_email").value = email || "";
    document.getElementById("edit_contact_no").value = contactNo || "";
    document.getElementById("edit_role").value = role || "";
    document.getElementById("edit_employee_type").value = employeeType || "";
    document.getElementById("edit_department_id").value = departmentId || "";
    document.getElementById("edit_admin_id").value = adminId || "";
    document.getElementById("edit_employee_status").value =
        employeeStatus || "";
    document.getElementById("edit_paid_status").value = paidStatus || "";
    document
        .getElementById("edit_profile_photo")
        .addEventListener("change", function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById("edit_photoPreview");
            const placeholder = document.getElementById(
                "edit_photoPlaceholder"
            );
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert("File size must be less than 2MB");
                    this.value = "";
                    return;
                }
                if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
                    alert("Please select a valid image file (JPG, PNG, GIF)");
                    this.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                    placeholder.style.display = "none";
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = "none";
                placeholder.style.display = "flex";
            }
        });
    document.getElementById(
        "editEmployeeForm"
    ).action = `/admin/employees/${employeeId}`;
    const photoPreview = document.getElementById("edit_photoPreview");
    const photoPlaceholder = document.getElementById("edit_photoPlaceholder");
    if (profilePhotoUrl) {
        photoPreview.src = profilePhotoUrl;
        photoPreview.style.display = "block";
        photoPlaceholder.style.display = "none";
    } else {
        photoPreview.src = "";
        photoPreview.style.display = "none";
        photoPlaceholder.style.display = "flex";
    }
    document.getElementById("editEmployeeModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeEditModal = function () {
    document.getElementById("editEmployeeModal").classList.remove("active");
    document.body.style.overflow = "auto";
    document.getElementById("editEmployeeForm").reset();
    const errorMessage = document.querySelector(
        "#editEmployeeModal .error-message"
    );
    if (errorMessage) errorMessage.remove();
};

document
    .getElementById("editEmployeeModal")
    .addEventListener("click", function (e) {
        if (e.target === this) window.closeEditModal();
    });
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        const addModal = document.getElementById("employeeModal");
        const editModal = document.getElementById("editEmployeeModal");
        if (addModal.classList.contains("active")) window.closeEmployeeModal();
        else if (editModal.classList.contains("active"))
            window.closeEditModal();
    }
});

// Form validation
document
    .getElementById("editEmployeeForm")
    .addEventListener("submit", function (e) {
        const requiredFields = this.querySelectorAll("[required]");
        let isValid = true;
        requiredFields.forEach((field) => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = "var(--redcode-primary)";
                field.style.background = "rgba(220, 38, 38, 0.05)";
            } else {
                field.style.borderColor = "var(--redcode-green)";
                field.style.background = "rgba(5, 150, 105, 0.05)";
            }
        });
        if (!isValid) {
            e.preventDefault();
            alert("Please fill in all required fields.");
        }
    });
document
    .getElementById("edit_contact_no")
    .addEventListener("input", function () {
        let value = this.value.replace(/\D/g, "");
        if (value.length > 15) value = value.substring(0, 15);
        this.value = value;
    });
document
    .querySelectorAll(
        "#editEmployeeModal .form-input, #editEmployeeModal .form-select"
    )
    .forEach((input) => {
        input.addEventListener("focus", function () {
            const icon = this.nextElementSibling;
            if (icon && icon.classList.contains("input-icon")) {
                icon.style.color = "var(--redcode-primary)";
                icon.style.transform = "translateY(-50%) scale(1.1)";
            }
        });
        input.addEventListener("blur", function () {
            const icon = this.nextElementSibling;
            if (icon && icon.classList.contains("input-icon")) {
                icon.style.color = "var(--text-light)";
                icon.style.transform = "translateY(-50%) scale(1)";
            }
        });
    });

document
    .getElementById("employeeForm")
    .addEventListener("submit", function (e) {
        const requiredFields = this.querySelectorAll("[required]");
        let isValid = true;
        requiredFields.forEach((field) => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = "var(--redcode-primary)";
                field.style.background = "rgba(220, 38, 38, 0.05)";
            } else {
                field.style.borderColor = "var(--redcode-green)";
                field.style.background = "rgba(5, 150, 105, 0.05)";
            }
        });
        if (!isValid) {
            e.preventDefault();
            alert("Please fill in all required fields.");
        }
    });
document.getElementById("contact_no").addEventListener("input", function () {
    let value = this.value.replace(/\D/g, "");
    if (value.length > 15) value = value.substring(0, 15);
    this.value = value;
});
document.querySelectorAll(".form-input, .form-select").forEach((input) => {
    input.addEventListener("focus", function () {
        const icon = this.nextElementSibling;
        if (icon && icon.classList.contains("input-icon")) {
            icon.style.color = "var(--redcode-primary)";
            icon.style.transform = "translateY(-50%) scale(1.1)";
        }
    });
    input.addEventListener("blur", function () {
        const icon = this.nextElementSibling;
        if (icon && icon.classList.contains("input-icon")) {
            icon.style.color = "var(--text-light)";
            icon.style.transform = "translateY(-50%) scale(1)";
        }
    });
});

// Error modal open on validation
if (window.hasEmployeeFormErrors) {
    window.addEventListener("load", function () {
        window.openEmployeeModal();
    });
}

// Search/filter functions
// window.searchEmployees = function () {
//     const searchTerm = document
//         .getElementById("searchInput")
//         .value.toLowerCase();
//     const rows = document.querySelectorAll("tbody tr");
//     let foundEmployee = null;
//     rows.forEach((row) => {
//         if (row.cells.length < 8) return;
//         const employeeName = row.cells[0].textContent.toLowerCase();
//         const department = row.cells[1].textContent.toLowerCase();
//         const position = row.cells[2].textContent.toLowerCase();
//         const email = row.cells[3].textContent.toLowerCase();
//         const matchesSearch =
//             employeeName.includes(searchTerm) ||
//             department.includes(searchTerm) ||
//             position.includes(searchTerm) ||
//             email.includes(searchTerm);
//         const currentDisplay = row.style.display;
//         if (currentDisplay === "none") row.style.display = "none";
//         else row.style.display = matchesSearch ? "" : "none";
//         if (
//             matchesSearch &&
//             employeeName.includes(searchTerm) &&
//             searchTerm.length > 2
//         ) {
//             const employeeId = row
//                 .querySelector('button[title="View Employee"]')
//                 ?.getAttribute("onclick")
//                 ?.match(/'([^']+)'/)?.[1];
//             if (employeeId) {
//                 foundEmployee = {
//                     id: employeeId,
//                     name: row.cells[0].textContent.trim(),
//                     department: row.cells[1].textContent.trim(),
//                     position: row.cells[2].textContent.trim(),
//                 };
//             }
//         }
//     });
//     if (foundEmployee) {
//         window.showEmployeeLeaveInfo(
//             foundEmployee.id,
//             foundEmployee.name,
//             foundEmployee.department,
//             foundEmployee.position
//         );
//     } else {
//         window.hideEmployeeLeaveInfo();
//     }
// };

window.searchEmployees = function () {
    const searchTerm = document
        .getElementById("searchInput")
        .value.toLowerCase();

    const rows = document.querySelectorAll("tbody tr");
    let visibleCount = 0;
    let foundEmployee = null;

    rows.forEach((row) => {
        if (row.cells.length < 8) return;

        const employeeName = row.cells[0].textContent.toLowerCase();
        const department = row.cells[1].textContent.toLowerCase();
        const position = row.cells[2].textContent.toLowerCase();
        const email = row.cells[3].textContent.toLowerCase();

        const matchesSearch =
            employeeName.includes(searchTerm) ||
            department.includes(searchTerm) ||
            position.includes(searchTerm) ||
            email.includes(searchTerm);

        row.style.display = matchesSearch ? "" : "none";

        if (matchesSearch) visibleCount++;

        if (
            matchesSearch &&
            employeeName.includes(searchTerm) &&
            searchTerm.length > 2
        ) {
            const employeeId = row
                .querySelector('button[title="View Employee"]')
                ?.getAttribute("onclick")
                ?.match(/'([^']+)'/)?.[1];

            if (employeeId) {
                foundEmployee = {
                    id: employeeId,
                    name: row.cells[0].textContent.trim(),
                    department: row.cells[1].textContent.trim(),
                    position: row.cells[2].textContent.trim(),
                };
            }
        }
    });

    // Show / hide the "no employees found" message
    const msg = document.getElementById("noEmployeesFoundMsg");
    if (visibleCount === 0) msg.style.display = "block";
    else msg.style.display = "none";

    if (foundEmployee) {
        window.showEmployeeLeaveInfo(
            foundEmployee.id,
            foundEmployee.name,
            foundEmployee.department,
            foundEmployee.position
        );
    } else {
        window.hideEmployeeLeaveInfo();
    }
};

window.filterEmployees = function () {
    const departmentFilter = document
        .getElementById("departmentFilter")
        .value.toLowerCase();
    const rows = document.querySelectorAll("tbody tr");
    rows.forEach((row) => {
        if (row.cells.length === 1) return;
        const department = row.cells[1].textContent.toLowerCase();
        if (departmentFilter === "" || department.includes(departmentFilter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
};

window.resetFilters = function () {
    document.getElementById("searchInput").value = "";
    document.getElementById("departmentFilter").value = "";
    const rows = document.querySelectorAll("tbody tr");
    rows.forEach((row) => {
        row.style.display = "";
    });
};

window.confirmDelete = function (employeeId, employeeName) {
    Swal.fire({
        title: "Delete Employee?",
        html: `Are you sure you want to delete <b>${employeeName}</b> (ID: <b>${employeeId}</b>)?<br><br>This action <span style="color:red;">cannot be undone</span>.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // create delete form
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/employees/${employeeId}`;

            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            ).content;

            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "DELETE";

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);

            form.submit();
        }
    });
};

// View modal
let currentViewEmployeeId = null;
window.openViewModal = function (
    employeeId,
    employeeName,
    email,
    contactNo,
    employeeType,
    role,
    department,
    admin,
    employeeStatus,
    paidStatus,
    createdAt,
    teams,
    profilePhotoUrl
) {
    currentViewEmployeeId = employeeId;
    var img = document.getElementById("view_profile_photo");
    var placeholder = document.getElementById("view_profile_photo_placeholder");
    if (profilePhotoUrl && profilePhotoUrl !== "") {
        img.src = profilePhotoUrl;
        img.style.display = "block";
        placeholder.style.display = "none";
    } else {
        img.src = "";
        img.style.display = "none";
        placeholder.style.display = "block";
    }
    document.getElementById("view_employee_id").textContent =
        employeeId || "Not specified";
    document.getElementById("view_employee_name").textContent =
        employeeName || "Not specified";
    document.getElementById("view_employee_name_header").textContent =
        employeeName || "Unknown Employee";
    document.getElementById(
        "view_employee_id_header"
    ).textContent = `Employee ID: ${employeeId || "Not specified"}`;
    document.getElementById("view_email").textContent =
        email || "Not specified";
    document.getElementById("view_contact_no").textContent =
        contactNo || "Not specified";
    document.getElementById("view_employee_type").textContent =
        employeeType || "Not specified";
    document.getElementById("view_role").textContent = role || "Not specified";
    document.getElementById("view_department").textContent =
        department || "Not assigned";
    document.getElementById("view_admin").textContent = admin || "Not assigned";
    document.getElementById("view_teams").textContent =
        teams || "No teams assigned";
    document.getElementById("view_created_at").textContent =
        createdAt || "Not specified";
    const statusElement = document.getElementById("view_employee_status");
    if (employeeStatus) {
        let statusClass = "";
        switch (employeeStatus.toLowerCase()) {
            case "active":
                statusClass = "active";
                break;
            case "inactive":
                statusClass = "inactive";
                break;
            case "on leave":
                statusClass = "on-leave";
                break;
            case "terminated":
                statusClass = "terminated";
                break;
            default:
                statusClass = "inactive";
        }
        statusElement.innerHTML = `<span class="status-badge ${statusClass}">${employeeStatus}</span>`;
    } else {
        statusElement.textContent = "Not specified";
    }
    const paidElement = document.getElementById("view_paid_status");
    if (paidStatus) {
        let paidClass = "";
        switch (paidStatus.toLowerCase()) {
            case "paid":
                paidClass = "paid";
                break;
            case "pending":
                paidClass = "pending";
                break;
            case "overdue":
                paidClass = "overdue";
                break;
            case "not applicable":
                paidClass = "not-applicable";
                break;
            default:
                paidClass = "pending";
        }
        paidElement.innerHTML = `<span class="status-badge ${paidClass}">${paidStatus}</span>`;
    } else {
        paidElement.textContent = "Not specified";
    }
    const accountAgeElement = document.getElementById("view_account_age");
    if (createdAt && createdAt !== "N/A") {
        try {
            const createdDate = new Date(createdAt);
            const now = new Date();
            const diffTime = Math.abs(now - createdDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays < 30) {
                accountAgeElement.textContent = `${diffDays} days`;
            } else if (diffDays < 365) {
                const months = Math.floor(diffDays / 30);
                accountAgeElement.textContent = `${months} month${
                    months > 1 ? "s" : ""
                }`;
            } else {
                const years = Math.floor(diffDays / 365);
                const remainingMonths = Math.floor((diffDays % 365) / 30);
                let ageText = `${years} year${years > 1 ? "s" : ""}`;
                if (remainingMonths > 0) {
                    ageText += ` ${remainingMonths} month${
                        remainingMonths > 1 ? "s" : ""
                    }`;
                }
                accountAgeElement.textContent = ageText;
            }
        } catch (e) {
            accountAgeElement.textContent = "Unable to calculate";
        }
    } else {
        accountAgeElement.textContent = "Not available";
    }
    document.getElementById("viewEmployeeModal").classList.add("active");
};

window.closeViewModal = function () {
    document.getElementById("viewEmployeeModal").classList.remove("active");
    currentViewEmployeeId = null;
};

window.openEditModalFromView = function () {
    if (currentViewEmployeeId) {
        const rows = document.querySelectorAll("tbody tr");
        for (let row of rows) {
            if (row.cells.length > 1) {
                const employeeIdCell = row.cells[0].textContent.trim();
                if (employeeIdCell === currentViewEmployeeId) {
                    const editButton = row.querySelector(
                        'button[title="Edit Employee"]'
                    );
                    if (editButton) {
                        editButton.click();
                        return;
                    }
                }
            }
        }
    }
};

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("modal-overlay")) {
        if (e.target.id === "viewEmployeeModal") window.closeViewModal();
    }
});
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        if (
            document
                .getElementById("viewEmployeeModal")
                .classList.contains("active")
        )
            window.closeViewModal();
    }
});
function exportEmployeePDF() {
    const rows = document.querySelectorAll("table tbody tr");

    if (rows.length === 0) {
        alert("No data available to export!");
        return;
    }

    let tableData = [];

    rows.forEach((row) => {
        const cols = row.querySelectorAll("td");

        // Skip empty (no employees) row
        if (cols.length < 7) return;

        tableData.push([
            cols[0].innerText.trim(), // Employee Name
            cols[1].innerText.trim(), // Department
            cols[2].innerText.trim(), // Position
            cols[3].innerText.trim(), // Email
            cols[4].innerText.trim(), // Phone
            cols[5].innerText.trim(), // Status
            cols[6].innerText.trim(), // Leave Count
        ]);
    });

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF("landscape");

    doc.setFontSize(16);
    doc.text("Employee List Report", 14, 15);

    doc.setFontSize(10);
    doc.text("Generated: " + new Date().toLocaleString(), 14, 22);

    doc.autoTable({
        head: [
            [
                "Employee",
                "Department",
                "Position",
                "Email",
                "Phone",
                "Status",
                "Leave Count",
            ],
        ],
        body: tableData,
        startY: 28,
        theme: "grid",
        styles: { fontSize: 9 },
        headStyles: { fillColor: [46, 125, 50] },
    });

    doc.save("Employee_List.pdf");
}
