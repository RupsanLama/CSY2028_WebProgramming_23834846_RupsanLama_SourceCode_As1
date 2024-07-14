// Function to toggle visibility by element ID
function toggleVisibility(elementId) {
    var element = document.getElementById(elementId);
    if (element.style.visibility === 'visible') {
        element.style.visibility = 'hidden';
    } else {
        element.style.visibility = 'visible';
    }
}

// More category display
function displayCategory() {
    toggleVisibility('moreCategory');
}

function hideCategory() {
    toggleVisibility('moreCategory');
}

// User auction display
function displayUserAuction() {
    toggleVisibility('userAuction');
}

function hideUserAuction() {
    toggleVisibility('userAuction');
}

// Admin category display
function displayAdminCategory() {
    toggleVisibility('adminCategory');
}

function hideAdminCategory() {
    toggleVisibility('adminCategory');
}

// Navigation functions
function register() {
    window.location.href = 'register.php';
}

function login() {
    window.location.href = 'login.php';
}
