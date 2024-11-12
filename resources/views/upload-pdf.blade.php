<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PDF and Extract Names & NIKs</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Upload PDF and Extract Names & NIKs</h2>

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="pdf_file" accept=".pdf" required>
        <button type="submit">Upload PDF</button>
    </form>

    <div id="message"></div>

    <script>
        $(document).ready(function() {
            $('#uploadForm').submit(function(event) {
                event.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '{{ route("upload.pdf") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#message').html('<p>' + response.message + '</p>');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error uploading PDF:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
