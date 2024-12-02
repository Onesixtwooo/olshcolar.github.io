<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Research</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }

        .modal {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            color: #8B0000;
            font-size: 24px;
            font-weight: bold;
        }

        .close-button {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 24px;
            padding: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-column {
            flex: 1;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .search-group {
            display: flex;
            gap: 10px;
        }

        .search-group input {
            flex: 1;
        }

        .search-button {
            background: #8B0000;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            padding: 8px 16px;
            font-size: 14px;
        }

        .file-drop {
            border: 2px dashed #ddd;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .button {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .button-cancel {
            background: #f5f5f5;
            border: 1px solid #ddd;
            color: #333;
        }

        .button-confirm {
            background: #8B0000;
            border: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="modal">
        <div class="modal-header">
            <h1 class="modal-title">Edit Research</h1>
            <a href="addresearch.php" class="close-button">&times;</a>

            
        </div>
        
        <form>
        <div class="form-row">
        <div class="form-column">
            <label for="research-id">Research ID</label>
            <div class="search-group">
                <input type="text" id="research-id" placeholder="Enter research ID">
                <button type="button" class="search-button" id="search-research">Search</button>
            </div>
        </div>
        <div class="form-column">
            <label for="title">Title</label>
            <input type="text" id="title" placeholder="Enter research title">
        </div>
    </div>

    <div class="form-row">
        <div class="form-column">
            <label for="author">Author</label>
            <input type="text" id="author" placeholder="Enter author">
        </div>
        <div class="form-column">
            <label for="keywords">Keywords</label>
            <input type="text" id="keywords" placeholder="Enter keywords">
        </div>
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <select id="category">
            <option>Quantitative Research</option>
            <option>Qualitative Research</option>
            <option>Mixed Methods Research</option>
        </select>
    </div>

    <div class="form-group">
        <label for="abstract">Abstract</label>
        <textarea id="abstract" placeholder="Enter research abstract"></textarea>
    </div>

    <div class="modal-footer">
        <button type="button" class="button button-cancel" onclick="window.location.href='addresearch.php';">Cancel</button>
        <button type="submit" class="button button-confirm">Confirm</button>
    </div>
      
        </form>
    
<script>
    document.getElementById('search-research').addEventListener('click', function() {
        var researchId = document.getElementById('research-id').value;

        if (researchId) {
            // Send AJAX request to fetch research data
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_research.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.success) {
                        // Populate the fields with the fetched data
                        document.getElementById('title').value = response.data.title;
                        document.getElementById('author').value = response.data.author;
                        document.getElementById('keywords').value = response.data.keywords;
                        document.getElementById('abstract').value = response.data.abstract;
                        document.getElementById('category').value = response.data.category;
                    } else {
                        alert('Research not found!');
                    }
                }
            };
            xhr.send('researchid=' + researchId);
        } else {
            alert('Please enter a Research ID.');
        }
    });
</script>
    </div>
</body>



</html>