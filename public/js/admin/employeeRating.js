
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function openRateEmployeeModal() {
    const form = document.getElementById('rateEmployeeForm');
    form.reset();


    const ratingText = document.querySelector('.rating-text');
    if (ratingText) {
        ratingText.textContent = 'Select a rating';
    }

    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    starInputs.forEach(input => {
        input.checked = false;
    });

    // Hide employee rating summary
    const summaryDiv = document.getElementById('employee-rating-summary');
    if (summaryDiv) {
        summaryDiv.style.display = 'none';
    }


    // Reset update/submit button state
    const submitBtn = document.querySelector('#rateEmployeeModal .btn-primary');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Submit Rating';
    }

    document.getElementById('rateEmployeeModal').style.display = 'block';
}

function rateEmployee(employeeId) {

    document.getElementById('employee_id').value = employeeId;

    loadEmployeeRatings(employeeId);

    openRateEmployeeModal();
}

function viewEmployeeRatings(employeeId) {

    const modal = document.getElementById('viewEmployeeRatingsModal');
    const content = document.getElementById('employee-ratings-content');
    content.innerHTML = '<p>Loading employee ratings...</p>';
    modal.style.display = 'block';

    fetch(`/admin/employeeRatings/employee/${employeeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = `
                    <div class="employee-rating-details">
                        <h3>${data.employee_name}</h3>
                        <p>${data.employee_email} - ${data.employee_department}</p>
                        <div class="rating-summary">
                            <div class="summary-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Average Rating:</span>
                                    <span class="stat-value">${data.average_rating}/5</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Total Ratings:</span>
                                    <span class="stat-value">${data.total_ratings}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ratings-list">
                            ${data.ratings.map(rating => `
                                <div class="rating-item">
                                    <div class="rating-header">
                                        <div class="rating-stars">
                                            ${Array.from({length: 5}, (_, i) => {
                    const isFilled = i < rating.rating;
                    return `<i class="fas fa-star ${isFilled ? 'filled' : ''}"></i>`;
                }).join('')}
                                        </div>
                                        <div class="rating-meta">
                                            <span class="rater-name">${rating.rater_name}</span>
                                            <span class="rating-date">${rating.created_at}</span>
                                        </div>
                                    </div>
                                    ${rating.comment ? `<div class="rating-comment">${rating.comment}</div>` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                content.innerHTML = '<p>Error loading employee ratings.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = '<p>Error loading employee ratings.</p>';
        });
}

function submitRating() {
    const form = document.getElementById('rateEmployeeForm');
    const formData = new FormData(form);

    if (!formData.get('employee_id')) {
        showNotification('Please select an employee', 'error');
        return;
    }
    if (!formData.get('rating')) {
        showNotification('Please select a rating', 'error');
        return;
    }

    const submitBtn = document.querySelector('#rateEmployeeModal .btn-primary');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
    if (data.success) {
        closeModal('rateEmployeeModal');
        showNotification(data.message, 'success');

        // ✅ REAL-TIME UPDATE or PAGE RELOAD
        if (document.getElementById('leaderboard')) {
            updateLeaderboard(
                data.employee_id,
                data.new_average_rating
            );
        } else {
            // If not on leaderboard page (e.g. Admin Ratings list), reload to show new data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    } else {
        showNotification(data.message || 'Error submitting rating', 'error');
    }
        }).finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
}


function exportRatings() {
    console.log('Exporting ratings data...');
    showNotification('Export feature coming soon!', 'info');
}

function updateLeaderboard(employeeId, newRating) {
    const leaderboard = document.getElementById('leaderboard');
    if (!leaderboard) return;

    const items = Array.from(leaderboard.children);

    const target = items.find(
        el => el.dataset.employeeId == employeeId
    );
    if (!target) return;

    // Update rating badge
    target.dataset.rating = newRating;
    target.querySelector('.badge').textContent = newRating.toFixed(1);

    // FIRST
    const first = items.map(el => el.getBoundingClientRect());

    // Sort by rating
    items.sort((a, b) => b.dataset.rating - a.dataset.rating);
    items.forEach(el => leaderboard.appendChild(el));

    // LAST
    const last = items.map(el => el.getBoundingClientRect());

    // FLIP animation
    items.forEach((el, i) => {
        const dx = first[i].left - last[i].left;
        el.style.transition = 'none';
        el.style.transform = `translateX(${dx}px)`;

        requestAnimationFrame(() => {
            el.style.transition = 'transform 400ms cubic-bezier(.4,0,.2,1)';
            el.style.transform = '';
        });
    });

    // Highlight updated employee
    target.classList.add('highlight');
    setTimeout(() => target.classList.remove('highlight'), 500);
}


document.addEventListener('DOMContentLoaded', function() {
    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    const ratingText = document.querySelector('.rating-text');

    starInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            const ratingLabels = {
                '1': 'Poor',
                '2': 'Fair',
                '3': 'Good',
                '4': 'Very Good',
                '5': 'Excellent'
            };
            ratingText.textContent = `${ratingLabels[rating]} (${rating}/5)`;
        });
    });
});


let currentEmployeeId = null;

function loadEmployeeRatings(employeeId, page = 1) {
    if (!employeeId) {
        document.getElementById('employee-rating-summary').style.display = 'none';
        return;
    }

    currentEmployeeId = employeeId;
    const summaryDiv = document.getElementById('employee-rating-summary');
    summaryDiv.style.display = 'block';
    
    document.getElementById('recent-ratings-list').innerHTML = '<p class="text-center text-gray-500 py-2">Loading...</p>';

    if (page === 1) {
        document.getElementById('avg-rating').textContent = '...';
        document.getElementById('total-ratings').textContent = '...';
    }

    fetch(`/admin/employeeRatings/employee/${employeeId}?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avg-rating').textContent = data.average_rating + '/5';
                document.getElementById('total-ratings').textContent = data.total_ratings;

                const recentRatingsList = document.getElementById('recent-ratings-list');
                const ratings = data.ratings || [];

                if (ratings.length > 0) {
                    let html = ratings.map(rating => `
                        <div class="recent-rating-item">
                            <div class="rating-info">
                                <div class="rating-stars">
                                    ${Array.from({length: 5}, (_, i) => {
                                        const isFilled = i < rating.rating;
                                        return `<i class="fas fa-star ${isFilled ? 'filled' : ''}"></i>`;
                                    }).join('')}
                                    <span style="font-size:0.85em;color:#666;margin-left:5px;">(${rating.rating}/5)</span>
                                </div>
                                <div class="rating-meta" style="display:flex; justify-content:space-between; width:100%; margin-top:5px; gap:15px;">
                                    <span class="rater-name" style="font-size:0.85em; color:#555;">By: ${rating.rater_name || 'Unknown'}</span>
                                    <span class="rating-date" style="font-size:0.85em; color:#888;">${rating.created_at}</span>
                                </div>
                            </div>
                            <div class="rating-comment" title="${rating.comment || 'No comment'}">
                                ${rating.comment || 'No comment'}
                            </div>
                        </div>
                    `).join('');

                    if (data.pagination && data.pagination.last_page > 1) {
                        html += `
                            <div class="pagination-controls" style="display:flex; justify-content:center; gap:10px; margin-top:10px; align-items:center;">
                                <button type="button" class="btn btn-sm btn-secondary" 
                                    ${data.pagination.current_page === 1 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}
                                    onclick="loadEmployeeRatings('${employeeId}', ${data.pagination.current_page - 1})">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </button>
                                <span style="font-size:0.85rem; color:#6b7280;">
                                    Page ${data.pagination.current_page} of ${data.pagination.last_page}
                                </span>
                                <button type="button" class="btn btn-sm btn-secondary" 
                                    ${!data.pagination.has_more ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}
                                    onclick="loadEmployeeRatings('${employeeId}', ${data.pagination.current_page + 1})">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        `;
                    }
                    recentRatingsList.innerHTML = html;
                } else {
                    recentRatingsList.innerHTML = '<p>No recent ratings found.</p>';
                }
            } else {
                summaryDiv.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading employee ratings:', error);
            summaryDiv.style.display = 'none';
        });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        color: white;
        font-weight: 600;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    notification.style.backgroundColor = colors[type] || colors.info;

    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
