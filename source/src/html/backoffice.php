<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>BackEnd Corner</title>
</head>

<body>
    <h1 class="mb-5 text-4xl font-bold flex justify-center">BackEnd Corner</h1>

    <button type="button" id="t1" class="ml-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Back office</button>
    <button type="button" id="t2" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">GuestBook</button>
    <button type="button" id="t3" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Gallery</button>

    <div id="tab1" class="relative overflow-x-auto shadow-md sm:rounded-lg mx-3">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">First Name</th>
                    <th scope="col" class="px-6 py-3">Last Name</th>
                    <th scope="col" class="px-6 py-3">Subject</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Message</th>
                    <th scope="col" class="px-6 py-3 text-center">Remove button</th>
                </tr>
            </thead>
            <tbody>
                <?php getfromdb(); ?>
            </tbody>
        </table>
    </div>

    <div id="tab2" class="hidden">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-3">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Message</th>
                        <th scope="col" class="px-6 py-3 text-center">check button</th>
                    </tr>
                </thead>
                <tbody>
                    <?php getfromdb2(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab3" class="hidden">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-3">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3 text-center">Remove button</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Content for tab 3 -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('button[id^="t"]');
    const tabs = document.querySelectorAll('div[id^="tab"]');

    // Function to handle tab switching
    function switchTab(tabNumber) {
        tabs.forEach(tab => tab.classList.add('hidden'));
        document.getElementById(`tab${tabNumber}`).classList.remove('hidden');
    }

    // Add event listeners to tab buttons
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabNumber = this.id.split('t')[1];
            switchTab(tabNumber);
        });
    });

    // Function to handle remove button clicks
    function addRemoveButtonListeners() {
        document.querySelectorAll('.remove-button').forEach(button => {
            button.addEventListener('click', () => {
                let id = button.getAttribute("data-id");
                rm_db(id, 'delete.php');
            });
        });

        document.querySelectorAll('.remove-button2').forEach(button => {
            button.addEventListener('click', () => {
                let id = button.getAttribute("data-id");
                rm_db(id, 'delete2.php');
            });
        });
    }

    // Function to send delete request
    function rm_db(id, url) {
        fetch(`${url}?id=${id}`, {
            method: 'GET'
        }).then(response => response.text())
          .then(data => {
            console.log(data); // Debugging: Check server response
            location.reload(); // Reload page to reflect changes
        });
    }

    // Initialize remove button listeners
    addRemoveButtonListeners();

    // Reinitialize listeners when switching tabs
    tabButtons.forEach(button => {
        button.addEventListener('click', addRemoveButtonListeners);
    });
});

    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validation pour le premier formulaire
        $required_fields = ['first_name', 'last_name', 'subject', 'email', 'message'];
        $all_fields_present = true;

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $all_fields_present = false;
                break;
            }
        }

        if ($all_fields_present) {
            // Sanitize input
            $first_name = htmlspecialchars($_POST['first_name']);
            $last_name = htmlspecialchars($_POST['last_name']);
            $subject = htmlspecialchars($_POST['subject']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);

            // All checks passed, call addToDb
            addToDb($first_name, $last_name, $subject, $email, $message);
        }

        // Validation pour le deuxième formulaire
        $required_fields2 = ['name', 'mail', 'msg'];
        $all_fields_present2 = true;

        foreach ($required_fields2 as $field) {
            if (empty($_POST[$field])) {
                $all_fields_present2 = false;
                break;
            }
        }

        if ($all_fields_present2) {
            // Sanitize input
            $name = htmlspecialchars($_POST['name']);
            $mail = htmlspecialchars($_POST['mail']);
            $msg = htmlspecialchars($_POST['msg']);

            // All checks passed, call addToDb2
            addToDb2($name, $mail, $msg);
        }
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

            $sql = "INSERT INTO client (first_name, last_name, subject, email, message) VALUES (:first_name, :last_name, :subject, :email, :message)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);

            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    function addToDb2($name, $mail, $msg)
    {
        $host = 'db';
        $user = 'user';
        $pass = 'pass';
        $dbn = 'mydb';

        try {
            $dsn = "mysql:host=$host;dbname=$dbn";
            $pdo = new PDO($dsn, $user, $pass);

            $sql = "INSERT INTO guestbook (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $mail);
            $stmt->bindParam(':message', $msg);

            $stmt->execute();
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

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                echo '<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">';
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['subject']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td class='px-6 py-4 flex justify-center'>";
                echo "<button class='remove-button' data-id='" . htmlspecialchars($row['id']) . "'>
                    <i class='fas fa-times' style='color: red; font-size: 16px;'></i>
                </button>";
                echo "</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    function getfromdb2()
    {
        $host = 'db';
        $user = 'user';
        $pass = 'pass';
        $dbn = 'mydb';

        try {
            $dsn = "mysql:host=$host;dbname=$dbn";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM `guestbook`";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($results as $row) {
                echo '<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">';
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td class='px-6 py-4'>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td class='px-6 py-4 flex justify-center'>";
                echo "<button class='remove-button2' data-id='" . htmlspecialchars($row['id']) . "'>
                    <i class='fas fa-times' style='color: red; font-size: 16px;'></i>
                </button>";
                echo "</td>";
                echo "<td class='px-6 py-4 flex justify-center'>";
                echo "<button class='validate-button' data-id='" . htmlspecialchars($row['id']) . "'>
                    <i class='fas fa-check' style='color: green; font-size: 16px;'></i>
                </button>";
                echo "</td>";
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
