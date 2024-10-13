<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Discount API</title>
</head>
<body>
    <h1>Test Discount API</h1>
    <button id="testButton">Send POST Request</button>

    <script>
        document.getElementById('testButton').addEventListener('click', function () {
            fetch('http://127.0.0.1:8000/api/apply-discount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer YOUR_TOKEN_HERE'
                },
                body: JSON.stringify({
                    booking: {
                        total: 1000,
                        isFamilyMemberRepeat: true
                    },
                    familyDiscount: {
                        type: 'fixed',
                        value: 100,
                        max_discount: 80
                    },
                    recurringDiscount: {
                        type: 'percentage',
                        value: 10,
                        max_discount: 50
                    }
                })
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
