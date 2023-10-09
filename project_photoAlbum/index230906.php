<?php
$conn=mysqli_connect("localhost:81","*","*","project_memories");

$sql = "SELECT * FROM who";
$result = mysqli_query($conn, $sql);
$select_form = '<select id="select" name="creater"><option disabled selected>누구야?</option>';
while($row = mysqli_fetch_array($result)){
    $select_form .= '<option value="'.$row['id'].'">'.$row['creater'].'</option>';
}
$select_form .= '</select>'; 
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Nanum+Gothic&display=swap" rel="stylesheet">
    <link href="style_index.css" rel="stylesheet">
    <script>
    function autoSubmit() {
        var form = document.getElementById('uploadForm');
        form.submit();
    }
    </script>
</head>

<body>
    <div class="layout">
        <div class="titleBox">
            <div class="titleName">Memories</div>
            <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload" value="1">
                <label class="uploadBotton" for="uploadInput">+</label>
                <input type="file" id="uploadInput" style="display:none" name="image" onchange="autoSubmit()">
                <?=$select_form?>
            </form>    
        </div>

        <?php
        session_start();

            if(isset($_POST['upload'])) {
                $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
                $file_type = $_FILES["image"]["type"];
                if($file_type == "image/jpeg" || $file_type == "image/png" || $file_type == "image/gif" || $file_type == "image/jpg" ) {
                    $filtered_id = mysqli_real_escape_string($conn, $_POST['creater']);
                    $sql = "insert into image (image,created,who_id) values('$file',NOW(),'$filtered_id')";
                    if(mysqli_query($conn, $sql)) {
                        $_SESSION['message'] = "헤헤 고맙따!";
                        header("Location: ".$_SERVER['PHP_SELF']); 
                        exit();
                    } else {
                        $_SESSION['message'] = "밍..실패했어오! 누가올린건지 알려줘요!"; 
                        header("Location: ".$_SERVER['PHP_SELF']); 
                        exit();
                    }
                } else {
                    $_SESSION['message'] = "이상한거 올리지마! 바보!";
                    header("Location: ".$_SERVER['PHP_SELF']); 
                    exit();
                }
            }

            if(isset($_SESSION['message'])) {
            echo '<script type="text/javascript"> alert("' . $_SESSION['message'] . '"); </script>';
            unset($_SESSION['message']); 
            }

            $sql = "select * from image order by id desc limit 18";
            $result = mysqli_query($conn, $sql);
            ?>

        <div id="photoLayout" class="photoLayout">    
            <?php    
                while($row = mysqli_fetch_array($result))
                {
                    echo '<div class="photo"><a href="image_display230906.php?id='.$row['id'].'">
                    <img class="img" src="data:image;base64,'.base64_encode($row['image']).'" alt="Image"></a>
                    </div>';
                }
        ?>
        </div>


    <!-- <script>
        window.onscroll = function() {
            if((window.innerHeight + Math.ceil(window.scrollY))>= document.body.offsetHeight) {
                let Newphoto = document.createElement('div');
                Newphoto.classList.add('photo');
                document.getElementById('photoLayout').appendChild(Newphoto);
            }
        }
    </script> -->
</body>

</html>