<?php
    require 'vendor/autoload.php';

    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;

    $app = new Slim\App();


    $app->get('/products', 'getProducts');
    $app->post('/productAdd', 'addProduct');

    $app->run();

    function getConnection() {
        $connection = new mysqli("localhost", "root", "senha254631", "go_table") or die(mysqli_error());
        return $connection;
    }

    function getProducts(){
        $getData = "SELECT * FROM `products`";
        $query = getConnection()->query($getData);

        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }

        header('content-type: application/json');
        echo json_encode($data);
        @mysqli_close($conn);
    }

    function addProduct(ServerRequestInterface $request, ResponseInterface $response){
        $allPostVars = $request->getParsedBody();
        $errors = array();
        $form_data = array();

        if (empty($allPostVars['name'])) {
            $errors['#field-name'] =  'Please complete the name field.';
        }
        if (empty($allPostVars['price'])) {
            $errors['#field-price'] =  'Please complete the price field.';
        }
        if (empty($allPostVars['inclusion'])) {
            $errors['#field-inclusion'] =  'Please complete the inclusion field.';
        }

        if (!empty($errors)) {
            $form_data['success'] = false;
            $form_data['errors']  = $errors;
            echo json_encode($form_data);
        }
        else {
            $name = $allPostVars['name'];
            $price = $allPostVars['price'];
            $inclusion = $allPostVars['inclusion'];

            $setData = "INSERT INTO `products` (`name`, `price`, `inclusion`) VALUES ('$name', '$price', '$inclusion')";
            $query = getConnection()->query($setData);

            if ($query) {
                $data['success'] = true;
                $data['posted'] = 'Data sent successfully';
            } else {
                $data['success'] = false;
                $data['errors']  = ['database', 'There was a problem with the database connection. Please, try again later', $setData . "<br>" . mysqli_error($connection)];
            }

            header('content-type: application/json');
            echo json_encode($data);
            @mysqli_close($conn);
        }
    }
?>
