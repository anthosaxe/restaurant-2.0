<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>backoffice</title>
</head>

<body>
    <h1 class=" mb-5 text-4xl text-bold flex justify-center">Backoffice</h1>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-3">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        First Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Last Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Subject
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Message
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Remove button
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php getfromdb(); ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                let bid = button.getAttribute("id");
                rm_db(bid);
            });
        });

        function rm_db(id) {
            fetch(`delete.php?id=${id}`, {
                    method: 'GET'
                }).then(response => response.text())
                .then(data => {
                    console.log(data);
                    location.reload;
                });
        }
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collecter et sécuriser les données du formulaire
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $subject = htmlspecialchars($_POST['subject']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        // Appeler la fonction pour ajouter les données à la base de données
        addToDb($first_name, $last_name, $subject, $email, $message);
    }

    function addToDb($first_name, $last_name, $subject, $email, $message)
    {
        $host = 'db';
        $user = 'user';
        $pass = 'pass';
        $dbn = 'mydb';

        try {
            $dsn = "mysql:host=$host;dbname=$dbn";
            $pdo = new PDO($dsn, $user, $pass);

            // Préparer la requête SQL
            $sql = "INSERT INTO client (first_name, last_name, subject, email, message) VALUES (:first_name, :last_name, :subject, :email, :message)";
            $stmt = $pdo->prepare($sql);

            // Lier les paramètres
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);

            // Exécuter la requête
            $stmt->execute();

            // Optionnel: Afficher un message de succès ou faire une redirection
            echo "Data successfully inserted into the database.";
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    function getfromdb()
    {
        $host = 'db';
        $user = 'user';
        $pass = 'pass';
        $dbn = 'mydb';

        try {
            $dsn = "mysql:host=$host;dbname=$dbn";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM `client`";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Fetch all results as associative arrays
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Display results in HTML table

            foreach ($results as $row) {
                echo '<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">';
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subject']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td class='px-6 py-4 flex justify-center'>";
                echo "<button id='" . htmlspecialchars($row['id']) . "'>
                            <i class='fas fa-times' style='color: red; font-size: 16px;'></i>
                        </button>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }
    ?>

</body>

</html>