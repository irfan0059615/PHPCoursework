const bookModalEl = document.getElementById('bookModal');
const editModalEl = document.getElementById('editModal');
const bookModal = bootstrap.Modal.getOrCreateInstance(bookModalEl);
const editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);

const bookForm = document.getElementById('bookForm');
const editForm = document.getElementById('editForm');

document.getElementById('searchInput').addEventListener('input', async function() {
    const query = this.value;
    const data = await PostData('php/search_books.php', new URLSearchParams({ search: query }));
    const tbody = document.querySelector('table tbody');
    tbody.innerHTML = '';
    if(data.books.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No books found</td></tr>';
        return;
    }
    data.books.forEach((book, i) => {
        tbody.innerHTML += `<tr>
            <td>${i+1}</td>
            <td>${book.title}</td>
            <td>${book.author}</td>
            <td>${book.genre}</td>
            <td>${book.published_year}</td>
            <td>${book.updated_on_short}</td>
            <td>
                <button class="btn btn-sm btn-outline-light me-1" onclick="editBook(${book.id})" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">Delete</button>
            </td>
        </tr>`;
    });
});

function PostData(url, body) {
    return new Promise((resolve) => {
        const httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', url, true);

        httpRequest.onreadystatechange = () => {
            if (httpRequest.readyState !== 4) return;
            if (httpRequest.status >= 200 && httpRequest.status < 300) {
                try {
                    resolve(JSON.parse(httpRequest.responseText));
                } catch (e) {
                    resolve({ status: 'error', message: 'Invalid JSON' });
                }
            } else {
                resolve({ status: 'error', message: httpRequest.statusText || 'Request failed' });
            }
        };

        httpRequest.onerror = () => resolve({ status: 'error', message: 'Network error' });

        if (body instanceof FormData) {
            httpRequest.send(body);
        } else if (body instanceof URLSearchParams) {
            httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpRequest.send(body.toString());
        } else {
            httpRequest.setRequestHeader('Content-Type', 'application/json');
            httpRequest.send(JSON.stringify(body));
        }
    });
}

async function addBook() {
    try {
        const data = await PostData('php/add_book.php', new FormData(bookForm));
        if (data.status === 'success') location.reload();
        else alert('Error: ' + (data.message || 'Failed to add book'));
    } catch {
        alert('Network error');
    }
}

async function deleteBook(id) {
    if (!confirm('Delete this book?')) return;
    try {
        const data = await PostData('php/delete_book.php', new URLSearchParams({ id }));
        if (data.status === 'success') location.reload();
        else alert('Error: ' + (data.message || 'Failed to delete'));
    } catch {
        alert('Network error');
    }
}

function editBook(id) {
    const btn = document.querySelector(`button[onclick="editBook(${id})"]`);
    if (!btn) return alert('Item not found');
    const row = btn.closest('tr');
    editForm.reset();
    editForm.elements['id'].value = id;
    editForm.elements['title'].value = row.children[1].textContent.trim();
    editForm.elements['author'].value = row.children[2].textContent.trim();
    editForm.elements['genre'].value = row.children[3].textContent.trim();
    editForm.elements['published_year'].value = row.children[4].textContent.trim();
    editModal.show();
}

async function updateBook() {
    try {
        const data = await PostData('php/update_book.php', new FormData(editForm));
        if (data.status === 'success') location.reload();
        else alert('Error: ' + (data.message || 'Failed to update'));
    } catch {
        alert('Network error');
    }
}

window.addBook = addBook;
window.deleteBook = deleteBook;
window.editBook = editBook;
window.updateBook = updateBook;