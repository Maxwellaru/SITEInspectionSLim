<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get All Customers
$app->get('/api/customers', function(Request $request, Response $response){
    $sql = "SELECT * FROM table_one";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->get('/api/inspectors/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $sql = "SELECT table_one.idTable_one, table_one.site, table_one.completed_by,table_one.date,
    table_one.work_area,table_one.job_descriptionn,table_one.supervisor,table_one.Inspector,
    table_one.type,table_one.total_interventions,
    table_two.idTable_two,table_two.category,table_two.sub_category,table_two.Interventions,
    table_two.comments,table_two.completed,table_two.action_taken
  FROM table_one
    INNER JOIN table_two ON table_one.idTable_one = table_two.idTable_one WHERE table_one.idTable_one = $id ";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// Get Single Inspector
$app->get('/api/inspector/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM table_one WHERE idTable_one = $id";


    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


$app->get('/api/users', function(Request $request, Response $response){
   // $id = $request->getAttribute('id');

   $sql = "SELECT * FROM table_three";


   try{
    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();

    $stmt = $db->query($sql);
    $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($customers);
} catch(PDOException $e){
    echo '{"error": {"text": '.$e->getMessage().'}';
}
});






// Add Inspector as User
$app->post('/api/inspectors/add', function(Request $request, Response $response){
    $name = $request->getParam('name');
    $type = $request->getParam('type');
    $supervisor = $request->getParam('supervisor');   
    $password = $request->getParam('password');

    $sql = "INSERT INTO table_three (name,type,supervisor,password) VALUES
    (:name,:type,:supervisor,:password)";
//:idTable_one
//,idTable_one
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type',      $type);
        $stmt->bindParam(':supervisor',    $supervisor);
        $stmt->bindParam(':password',    $password);
       
        
        

        $stmt->execute();

        echo '{"notice": {"text": "Inspector Added"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});





// Add Inspector
$app->post('/api/inspector/add', function(Request $request, Response $response){
    $Inspector = $request->getParam('Inspector');
    $completed_by = $request->getParam('completed_by');
    $date = $request->getParam('date');
    $job_descriptionn = $request->getParam('job_descriptionn');
    $site = $request->getParam('site');
    $supervisor = $request->getParam('supervisor');
    $type = $request->getParam('type');
    $work_area = $request->getParam('work_area');
  //  $idTable_one = $request->getParam('idTable_one');

    $sql = "INSERT INTO table_one (Inspector,completed_by,date,job_descriptionn,supervisor,site,type,work_area) VALUES
    (:Inspector,:completed_by,:date,:job_descriptionn,:supervisor,:site,:type,:work_area)";
//:idTable_one
//,idTable_one
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':Inspector', $Inspector);
        $stmt->bindParam(':completed_by',  $completed_by);
        $stmt->bindParam(':date',      $date);
        $stmt->bindParam(':job_descriptionn',      $job_descriptionn);
        $stmt->bindParam(':supervisor',    $supervisor);
        $stmt->bindParam(':site',       $site);
        $stmt->bindParam(':type',      $type);
        $stmt->bindParam(':work_area',      $work_area);
       // $stmt->bindParam(':idTable_one',      $idTable_one);

        $stmt->execute();

        echo '{"notice": {"text": "Inspector Added"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Add Inspection List
$app->post('/api/inspectorlist/add', function(Request $request, Response $response){

     $Inspector = "";
     $completed_by ;
     $date ;
     $job_descriptionn ;
     $site ;
     $supervisor ;
     $type ;
    $work_area ;
    $total_interventions ;


    $sql;
    $table_oneid;

    $idTable_one;
    $category ;
    $sub_category;
    $Interventions;
    $comments;
    $completed;
    $action_taken;
    $dbSql;
      
    
    
    $inspectionlist = $request->getparam("inspectionlist");
      $categories = array();


      $items = array();

      foreach($inspectionlist as $x=>$value){
   //echo "$x=> $value\n";
       $items[] = $value;

       $Inspector =  $value["Inspector"];
       $completed_by = $value["completed_by"] ;
       $date = $value["date"];
       $job_descriptionn = $value["job_descriptionn"];
       $site = $value["site"];
       $supervisor = $value["supervisor"] ;
       $type = $value["type"];
       $work_area  = $value["type"];
       $total_interventions = $value["total_interventions"];
   
      $sql = "INSERT INTO table_one (Inspector,completed_by,date,job_descriptionn,supervisor,site,type,total_interventions,work_area) VALUES
      (:Inspector,:completed_by,:date,:job_descriptionn,:supervisor,:site,:type,:total_interventions,:work_area)";

    foreach($value as $y=>$z){
        $categories = $z;
      // echo "$y => $z \n";
       }
    }

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

    
    $stmt->bindParam(':Inspector', $Inspector);
    $stmt->bindParam(':completed_by',  $completed_by);
    $stmt->bindParam(':date',      $date);
    $stmt->bindParam(':job_descriptionn',      $job_descriptionn);
    $stmt->bindParam(':supervisor',    $supervisor);
    $stmt->bindParam(':site',       $site);
    $stmt->bindParam(':type',      $type);
    $stmt->bindParam(':work_area',      $work_area);
    $stmt->bindParam(':total_interventions',      $total_interventions);

    $stmt->execute();
    $table_oneid =$db->lastInsertId();

    echo '{"notice": {"text": "Inspector Added"}';

  $count = 0;
   foreach($categories as $x=>$value){
    //echo "Inspection :$x = $value:\n";
    foreach($value as $y=>$z){
       // echo "Category : $y = $z:\n";
        foreach($z[0] as $a=>$b){
           // echo "Sub Category :$a = $b:\n";
           
              foreach($b as $u=>$c){

               
              
                // echo "Inspection :$x = $value:\n";
                //echo "Category : $y = $z:\n";
               // echo "Sub Category :$a = $b:\n";
                //echo "value : $u = $c\n";
                 
                if($u == "Interventions")
                {
                    $Interventions = $c;
                }
                if($u == "comments")
                {
                    $comments = $c;
                }
                if($u == "completed")
                {
                   $completed = $c;
                }
                if($u == "action_taken")
                {
                 $action_taken = $c;
                }
               
                

                echo $idTable_one = $table_oneid ;
                 $category = $y;
                 $sub_category = $a;
               
                 //echo $completed = $u;
                // echo $action_taken = $u["action_taken"];
                 //echo $comments = $u["comments"];
                 echo $count++;
                 
                    $dbsql = "INSERT INTO table_two(idTable_one,category,sub_category,Interventions,comments,completed,action_taken) VALUES
                                (:idTable_one,:category,:sub_category,:Interventions,:comments,:completed,:action_taken)";

   
    //echo $table_oneid;

    $stmts = $db->prepare($dbsql);
    
    
    $stmts->bindParam(':idTable_one', $table_oneid);
    $stmts->bindParam(':category',  $category);
    $stmts->bindParam(':sub_category',      $sub_category);
    $stmts->bindParam(':Interventions',      $Interventions);
    $stmts->bindParam(':comments',    $comments);
    $stmts->bindParam(':completed',       $completed);
    $stmts->bindParam(':action_taken',      $action_taken);
   

    $stmts->execute();

    echo '{"notice": {"text": "Inspection Data Added"}';
}   
}
}
}

} catch(PDOException $e){
    echo '{"error": {"text": '.$e->getMessage().'}';
}
});






$app->get('/api/inspectionlist/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

     //"SELECT * FROM table_one WHERE idTable_one = $id";

     $sql ="SELECT table_one.idTable_one, table_two.Interventions, table_one.completed_by,table_one.site,table_two.category,table_two.sub_category
           FROM table_one
            INNER JOIN table_two ON table_one.idTable_one = table_two.idTable_one WHERE table_one.idTable_one = $id "; 

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

   
   
   



// Update Customer
$app->put('/api/customer/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql = "UPDATE customers SET
				first_name 	= :first_name,
				last_name 	= :last_name,
                phone		= :phone,
                email		= :email,
                address 	= :address,
                city 		= :city,
                state		= :state
			WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':phone',      $phone);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':address',    $address);
        $stmt->bindParam(':city',       $city);
        $stmt->bindParam(':state',      $state);

        $stmt->execute();

        echo '{"notice": {"text": "Customer Updated"}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Delete Customer
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM customers WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Customer Deleted"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});