const bookModalEl = document.getElementById('bookModal');
const editModalEl = document.getElementById('editModal');
const bookModal = bootstrap.Modal.getOrCreateInstance(bookModalEl);
const editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);

const bookForm = document.getElementById('bookForm');
const editForm = document.getElementById('editForm');

document.getElementById('searchInput').addEventListener('input', async function() {
    console.log('Search input changed:', this.value);
});

async function addBook() {
    console.log('Add book');
}

async function deleteBook(id) {
    if (!confirm('Delete this book?')) return;
    console.log('Delete book with ID:', id);
}

function editBook(id) {
    console.log('Edit book with ID:', id);
}

async function updateBook() {
    console.log('Update book');
}

window.addBook = addBook;
window.deleteBook = deleteBook;
window.editBook = editBook;
window.updateBook = updateBook;