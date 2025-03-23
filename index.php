<?php
    echo    "<h1>Welcome to the Quotes API</h1>
            <br>
            <p>Use the following endpoints to interact with the API:</p>
            <ul>
                <li><b>GET api/quotes/</b> - Retrieve all quotes. You can specify 'id', 'author_id', and/or 'category_id' in the url string to filter results.</li>
                <li><b>POST api/quotes/</b> - Create a new quote. Requires 'quote', 'author_id', and 'category_id' in the request body.</li>
                <li><b>PUT api/quotes/</b> - Update an existing quote. Requires 'id', 'quote', 'author_id', and 'category_id' in the request body.</li>
                <li><b>DELETE api/quotes/</b> - Delete a quote. Requires 'id' in the request body.</li>
                <br>
                <li><b>GET api/authors/</b> - Retrieve all authors. You can specify 'id' in the url string to get a specific author.</li>
                <li><b>POST api/authors/</b> - Create a new author. Requires 'author' in the request body.</li>
                <li><b>PUT api/authors/</b> - Update an existing author. Requires 'id' and 'author' in the request body.</li>
                <li><b>DELETE api/authors/</b> - Delete an author. Requires 'id' in the request body.</li>
                <br>
                <li><b>GET api/categories/</b> - Retrieve all categories. You can specify 'id' in the url string to get a specific category.</li>
                <li><b>POST api/categories/</b> - Create a new category. Requires 'category' in the request body.</li>
                <li><b>PUT api/categories/</b> - Update an existing category. Requires 'id' and 'category' in the request body.</li>
                <li><b>DELETE api/categories/</b> - Delete a category. Requires 'id' in the request body.</li>
            </ul>";
?>