

<?php include ROOT.'/views/layout/head.php'; ?>

<body>

<?php include ROOT.'/views/layout/header.php'; ?>


<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">


            <form id="post-form">
                <div class="form-group">
                    <label for="cat-name">Добавить</label>
                    <input type="text" name="header" class="form-control" id="cat-name" placeholder="Введите заголовок" required>
                    <select name="category" id="">
                        <?php foreach ($catsList as $cat): ?>
                            <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="file" name="image" placeholder="">
                    <textarea name="text" id="" cols="30" rows="10" placeholder="Текст поста"></textarea>
                </div>
                <input type="submit" name="submit" class="btn btn-primary">
            </form>


        </div>
    </div>
</div>

<hr>

<?php include ROOT.'/views/layout/footer.php'; ?>

<script>

    $(document).ready(function(){
        console.log('hi');

        $('#post-form').submit(function(e){
            e.preventDefault();
            var data = $(this).serialize();
            var url = '/post/add';

            $.ajax({
                data: data,
                type: 'POST',
                url: url,
                success: function(response){
                    console.log(response);
                    alert('Пост создан');
                    $('#post-form')[0].reset();
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



