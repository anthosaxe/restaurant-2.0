<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Dream</title>
</head>

<body>
    <?php
    require_once 'conect.php';
    $devise1 = 'EUR';
    $devise2 = 'USD';
    $amount = 1;

    if (isset($_GET['amount']) && isset($_GET['devise1']) && isset($_GET['devise2'])) {
        $amount = $_GET['amount'];
        $devise1 = strtolower($_GET['devise1']);
        $devise2 = strtolower($_GET['devise2']);
    }

    $url = "https://forex-convertor.p.rapidapi.com/getExchangeRate?";
    // "from=eur&to=usd";
    $param = "from=$devise1&to=$devise2";
    $url .= $param;

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: forex-convertor.p.rapidapi.com",
            "x-rapidapi-key: a47cff304emsh5ef9517c691c0aep119250jsn4de641afed63"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $data = json_decode($response, true);
    $convert = $amount * (float)$data['exchangeRate'];

    //addtodb($devise1, $devise2, $data["exchangeRate"]);

    ?>

    <h1 class="flex justify-center mb-5 items-center text-5xl font-extrabold dark:text-white">The Dream</h1>

    <form class="max-w-md mx-auto" method="get">
        <label for="amount" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <input type="text" id="amount" name="amount" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Monney amount" required />
            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Change</button>
        </div>
        <div class="grid grid-cols-12 mt-5">
            <div class="col-span-6 flex justify-center mb-3">
                <h2>Your Devise</h2>
            </div>
            <div class="col-span-6 flex justify-center mb-3">
                <h2>Destination Devise</h2>
            </div>
            <div class="col-span-6 flex justify-center">
                <?php echo create_select("devise1") ?>
            </div>
            <div class="col-span-6 flex justify-center">
                <?php echo create_select("devise2") ?>
            </div>
            <div class="col-span-12 flex justify-center mt-5">
                Change Rate :
                <?php echo $data["exchangeRate"]; ?>
            </div>
            <div class="col-span-4 flex justify-center mt-5">
                <?php echo "$amount $devise1"; ?>
            </div>
            <div class="col-span-4 flex justify-center mt-5">
                =
            </div>
            <div class="col-span-4 flex justify-center mt-5">
                <?php echo "$convert $devise2"; ?>
            </div>
            <div class="col-span-12 flex justify-center mt-5">
                <?php
                //$sql = "SELECT `USD1` FROM `exchange_rate` WHERE USD2='jpy';";
                //$query = $pdo->query($sql);
                //var_dump($query->fetchAll());
                ?>
            </div>
        </div>
    </form>

</body>

</html>