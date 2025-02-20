<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Ajax CRUD</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Laravel AJAX CRUD</h1>

        <div class="card shadow-lg">
            <div class="card-body">
                <h4 class="mb-3">Manage Posts</h4>

                <!-- Form -->
                <form id="postForm">
                    <input type="hidden" id="postId">

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" class="form-control" placeholder="Enter title" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" class="form-control" placeholder="Enter content" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Post</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>

        <hr>

        <!-- Post List -->
        <div class="card shadow-lg">
            <div class="card-body">
                <h4 class="mb-3">Post List</h4>
                <table class="table table-striped" id="postTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            fetchPosts();

            // Fetch Posts
            function fetchPosts() {
                $.get('/posts', function (data) {
                    let rows = '';
                    data.forEach(post => {
                        rows += `
                            <tr>
                                <td>${post.title}</td>
                                <td>${post.content}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editPost(${post.id}, '${post.title}', '${post.content}')">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deletePost(${post.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#postTable tbody').html(rows);
                });
            }

            // Submit Form
            $('#postForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#postId').val();
                let url = id ? `/posts/${id}` : '/posts';
                let type = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: $('#title').val(),
                        content: $('#content').val()
                    },
                    success: function () {
                        Swal.fire('Success!', 'Post has been saved.', 'success');
                        $('#postForm')[0].reset();
                        $('#postId').val('');
                        fetchPosts();
                    }
                });
            });

            // Edit Post
            window.editPost = function (id, title, content) {
                $('#postId').val(id);
                $('#title').val(title);
                $('#content').val(content);
            };

            // Delete Post
            window.deletePost = function (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/posts/${id}`,
                            type: 'DELETE',
                            data: { _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function () {
                                Swal.fire('Deleted!', 'Your post has been deleted.', 'success');
                                fetchPosts();
                            }
                        });
                    }
                });
            };
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
