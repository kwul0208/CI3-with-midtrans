<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- midtrans -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-fOqyfqFlSDR-vSgm"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- end -->

    <title>Spp</title>
</head>

<body class="container">
    <h1>Pembayaran SPP</h1>

    <form id="payment-form" method="post" action="<?= base_url() ?>index.php/snap/finish">
        <input type="hidden" name="result_type" id="result-type" value=""></div>
        <input type="hidden" name="result_data" id="result-data" value=""></div>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="name">
        </div>
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select name="kelas" id="kelas">
                <option value="basic">Basic</option>
                <option value="medim">Medium</option>
                <option value="pro">Pro</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" name="price" id="price" placeholder="Rp.">
        </div>



        <button id="pay-button">Pay!</button>
    </form>


    <h1>History</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">order_id</th>
                <th scope="col">gross_amount</th>
                <th scope="col">transaction_time</th>
                <th scope="col">bank</th>
                <th scope="col">va_number</th>
                <th scope="col">transaction_status</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1 ?>
            <?php foreach ($pending as $p) : ?>
                <tr>
                    <th scope="row"><?= $i++ ?></th>
                    <td><?= $p['order_id'] ?></td>
                    <td><?= $p['gross_amount'] ?></td>
                    <td><?= $p['transaction_time'] ?></td>
                    <td><?= $p['bank'] ?></td>
                    <td><?= $p['va_number'] ?></td>
                    <td> <?= $p['transaction_status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <script type="text/javascript">
        $('#pay-button').click(function(event) {
            event.preventDefault();
            $(this).attr("disabled", "disabled");

            const name = $('#name').val();
            const kelas = $('#kelas').val();
            const price = $('#price').val();

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>index.php/snap/transaksi',
                data: {
                    name: name,
                    kelas: kelas,
                    price: price
                },
                cache: false,

                success: function(data) {
                    //location = data;

                    console.log('token = ' + data);

                    var resultType = document.getElementById('result-type');
                    var resultData = document.getElementById('result-data');

                    function changeResult(type, data) {
                        $("#result-type").val(type);
                        $("#result-data").val(JSON.stringify(data));
                        //resultType.innerHTML = type;
                        //resultData.innerHTML = JSON.stringify(data);
                    }

                    snap.pay(data, {

                        onSuccess: function(result) {
                            changeResult('success', result);
                            console.log(result.status_message);
                            console.log(result);
                            $("#payment-form").submit();
                        },
                        onPending: function(result) {
                            changeResult('pending', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        },
                        onError: function(result) {
                            changeResult('error', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>