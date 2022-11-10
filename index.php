<?php

$config = parse_ini_file("config.ini", true);

$host = $config["database"]["host"];
$db   = $config["database"]["db"];
$user = $config["database"]["user"];
$pass = $config["database"]["pass"];
$port = "3306";
$charset = 'utf8';

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
try {
     $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$limit="";
if(!$_GET["o"]=="all"){
    $limit = "LIMIT 1000";
}

$sql = "select dateRate, timeRate, offerRate, bidRate from (SELECT id, dateRate, timeRate, offerRate, bidRate FROM tucambista ORDER BY id DESC {$limit}) tc ORDER BY id ASC";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <title>Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div >
  <canvas id="myChart"  ></canvas>
</div>
<script>
  const labels = [
    <?php foreach ($pdo->query($sql) as $row) {   
            $dateRs = new DateTime($row['dateRate']);
            $dateFmt = $dateRs->format('m.d');
            $timeFmt = substr($row['timeRate'],0,5);

            print "'".$dateFmt." ".$timeFmt."',"; 
        }?>
  ];

  const data = {
    labels: labels,
    datasets: [{
      label: 'Tu cambista offerRate',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      borderWidth: 1,
      radius: 0,
      data: [<?php foreach ($pdo->query($sql) as $row) {   print $row['offerRate'].","; }?>]
    },
    {
      label: 'Tu cambista bidRate',
      backgroundColor: 'rgb(255, aa, 132)',
      borderColor: 'rgb(255, aa, 132)',
      borderWidth: 1,
      radius: 0,
      data: [<?php foreach ($pdo->query($sql) as $row) {   print $row['bidRate'].","; }?>]
    }
    ]
  };

  const config = {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        interaction: {
            intersect: false,
        },
    }
  };
</script>

<script>
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
</script>

</body>
</html>