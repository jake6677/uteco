<?php
$conn=mysqli_connect("localhost:81","*","*","project_memories");
$image_display='';
$formatted_date='';
$comment_list='';
if (isset($_GET['id'])){
    $filtered_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM image LEFT JOIN who ON image.who_id=who.id WHERE image.id={$filtered_id}";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $article = array(
        'created' => htmlspecialchars($row['created']),
        'image' => $row['image'],
        'creater' => htmlspecialchars($row['creater'])
    );
    $image_display = 
    '<img class="displayImage" src="data:image;base64,'.base64_encode($article['image']).'"alt="Image" style="width: 100%; height: 100%;">';
    $formatted_date = date("y년 m월 d일", strtotime($article['created']));

    $sql2 = "SELECT * FROM comment WHERE image_id={$filtered_id}";
    $result2 = mysqli_query($conn, $sql2);
    while ($row = mysqli_fetch_array($result2)){
        $delete_box = '
        <form class="delete_box" action="process_comment_delete.php" method="POST" onsubmit="if(!confirm(\'정말 삭제하시겠습니까?\')) {return false;}">
            <input type="hidden" name="id" value="'.$row['id'].'">
            <input type="hidden" name="page_id" value="'.$_GET['id'].'">
            <input id="delete_submit" type="submit" value="X">
        </form>
        ';
        $escaped_comment = htmlspecialchars($row['comment']);
        $comment_list = $comment_list."<li class='float_left'>{$escaped_comment}</li>{$delete_box}";
    }
} else {
    header("Location: index230906.php");
}
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
    <link href="style_display.css" rel="stylesheet">
</head>
<body>
    <div class="layout">
        <div class="topLayout">
            <div class="titleContents" id="who"><?=$article['creater']?></div>
            <div class="titleContents" id="when"><?=$formatted_date?></div>
            <div class="titleContents" id="close"><a href="index230906.php">X</a></div>
        </div>
        <div class="photo">
            <div class="photoLayout" id="leftLayout">
                <?=$image_display?>
            </div>
            <div class="photoLayout" id="rightLayout">
                <div class="chatLayout">
                <ol>
                    <?=$comment_list?>
                </ol>
            </div>
                <div class="chatInputLayout">
                    <form action="process_comment_create.php" method="post">
                        <input type="hidden" name="image_id" value="<?=$_GET['id']?>">
                        <input type="text" id="comment" name="comment" placeholder="댓글을 입력하세요."> 
                        <input type="submit" id="comment_submit" value="남기기">
                    </form>
                </div>
            </div>
        <div>
    </div>
</body>
</html>