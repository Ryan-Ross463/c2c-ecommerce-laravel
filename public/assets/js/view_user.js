document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        window.confirmDelete = function(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserForm').action = BASE_URL + '/admin/users/delete/' + userId;
            const modal = new bootstrap.Modal(deleteModal);
            modal.show();
        };
    }
});
