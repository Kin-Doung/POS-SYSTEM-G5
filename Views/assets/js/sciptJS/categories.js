
    function confirmDelete(categoryId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set the deletion and timeout in localStorage
                localStorage.setItem('categoryDeleted', 'Category deleted successfully!');
                localStorage.setItem('notificationTimeout', Date.now() + 5000);
                window.location.href = '/category/delete?id=' + categoryId;
            }
        });
    }

    window.onload = function() {
        const message = localStorage.getItem('categoryDeleted');
        const timeout = localStorage.getItem('notificationTimeout');
        if (message && Date.now() < timeout) {
            showNotification(message);
        }
    };

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.textContent = message;
        Object.assign(notification.style, {
            position: 'fixed',
            top: '100px', // Move the notification down
            right: '20px', // Align it to the right
            backgroundColor: '#39e75f',
            color: 'white',
            padding: '10px 20px',
            borderRadius: '5px',
            fontSize: '16px',
            zIndex: '9999',
            opacity: '1', // Initial opacity
            transition: 'opacity 3s ease-out' // Fade out effect
        });
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0'; // Fade out the notification
        }, 0); // Apply the fade immediately after it's added
        setTimeout(() => {
            notification.remove();
            localStorage.removeItem('categoryDeleted');
            localStorage.removeItem('notificationTimeout');
        }, 6000); // Ensure the notification is removed after fade-out (5 seconds + transition time)
    }

    

