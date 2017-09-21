

<?php include ROOT.'/views/layout/head.php'; ?>

<body>

<?php include ROOT.'/views/layout/header.php'; ?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">

            <?php foreach ($posts as $post): ?>
                <div class="post-preview">
                    <a href="/post/<?=$post['id']?>">
                        <h2 class="post-title">
                            <?=$post['header']?>
                        </h2>
                    </a>
                    <div>
                        <img src="<?=$post['image']?>" alt="">
                    </div>

                    <p>
                        <?=$post['text']?>
                    </p>
                    <p class="post-meta">Обновлен <?=$post['time']?></p>
                    <p class="post-meta">Рубрика<?=$post['category']?></p>

                </div>
                <hr>
            <?php endforeach; ?>

            <div class="clearfix">

            </div>
        </div>
    </div>
</div>

<hr>

<?php include ROOT.'/views/layout/footer.php'; ?>

</body>

</html>
