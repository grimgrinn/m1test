

<?php include ROOT.'/views/layout/head.php'; ?>

<body>

<?php include ROOT.'/views/layout/header.php'; ?>


<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">


            <form id="cat-form">
                <div class="form-group">
                    <label for="cat-name">Добавить</label>
                    <input type="text" name="name" class="form-control" id="cat-name" placeholder="Введите название" required>
                </div>
                <input type="submit" name="submit" class="btn btn-primary">
            </form>


        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">

            <?php foreach ($catsList as $cat): ?>
            <div class="post-preview">
                <a href="#">
                    <h2 class="post-title"><?=$cat['name']?></h2>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

<hr>

<?php include ROOT.'/views/layout/footer.php'; ?>

<script>

    $(document).ready(function(){
        console.log('hi');

        $('#cat-form').submit(function(e){
            e.preventDefault();
            var data = $(this).serialize();
            var url = 'add';

            $.ajax({
                data: data,
                type: 'POST',
                url: url,
                success: function(response){
                    console.log(response);
                    alert('Категория добавлена');
                },
                error: function(error){
                    alert(error);
                }
            });

        });
    });

</script>

</body>

</html>



