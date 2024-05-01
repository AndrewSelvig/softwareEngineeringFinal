document.addEventListener('DOMContentLoaded', function () {
    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const tr = event.target.closest('tr');
            tr.remove();  // Remove the row from the table
        });
    });
});
