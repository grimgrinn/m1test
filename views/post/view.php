

<?php include ROOT.'/views/layout/head.php'; ?>

<body>

<?php include ROOT.'/views/layout/header.php'; ?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">

            <h1>
                <?=$post['header']?>
            </h1>

            <div>
                <img src="<?=$post['image']?>" alt="">
            </div>

            <div>
                <?=$post['text']?>
            </div>

            <div class="clearfix">

            </div>
        </div>
    </div>
</div>

<hr>

<?php include ROOT.'/views/layout/footer.php'; ?>

</body>

</html>
