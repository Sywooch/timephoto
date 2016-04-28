<?php
/* @var $this PageController */
/* @var $pages Page[] */

$this->registerJsFile(Yii::$app->homeUrl . "fw/tinymce/js/tinymce/tinymce.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);

$has_logo = file_exists(Yii::getAlias('@app') . '/web/images/watermark-logo.png') ? true : false;
?>
    <div class="col-md-6">
        <div class="panel panel-inverse">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="pages-table">
                            <tr>
                                <td>Логотип сайта</td>

                                <td>
                                    <div class="logo-wrap">
                                            <img src="/images/<?=$has_logo? 'watermark-logo.png' : 'no-logo.png' ?>" alt="" id="logo-img">
                                        <div class="overlay js-overlay js-edit-logo"><i class="fa fa-edit"></i></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <a href="#" class="btn btn-danger m-t-10 <?=$has_logo? '' : 'hidden' ?>" id="js-delete-logo"><i
                                            class="fa fa-times"></i> Удалить логотип</a>
                                    <input class="hidden" type="file" name="logo" id="logo-input">

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {

            App.init();
        });
    </script>

<?php
$js = <<<JS
    $(document).ready(function(){
        $('#js-delete-logo').on('click', function(){
        $.ajax({
            url: "/admin/settings/delete-logo",
            type: "post",
            dataType: "json"
        }).done(function(response) {
            if(response.success == true){
                swal('Изображение успешно удалено');
                $('#logo-img').attr('src','/images/no-logo.png');
                $('#js-delete-logo').addClass('hidden');
            }else{
                swal('Изображение не удалено');
            }

        });
    });

    $('.js-edit-logo, #logo-img').on('click', function(){
        $('#logo-input').click();
    });
    $('#logo-input').on('change', function(){
        var file = this.files[0];
        var reader = new FileReader();
        img_data = '';
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            var img_data = e.target.result;
            $('#logo-img').attr('src', img_data);
            $.ajax({
                url: "/admin/settings/upload-logo",
                type: "post",
                data: {
                    logo : img_data
                },
                dataType: "json"
            }).done(function(response) {
                if(response.success == ''){
                    swal('Изображение не сохранено');
                }else{
                    swal('Изображение успешно сохранено');
                }

            });
        };
        $('#js-delete-logo').removeClass('hidden');
        reader.readAsDataURL(file);
    });
    });
JS;

$this->registerJs($js);
