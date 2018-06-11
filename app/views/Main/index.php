<h1>Это шаблон view - динамический контент</h1>

<?php foreach($posts as $post) :?>
    <p><?= $post->id?></p>
    <p><?= $post->title?></p>
<?php endforeach; ?>
