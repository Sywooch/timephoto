<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.02.15
 * Time: 1:27
 */
/* @var $form CActiveForm */
/* @var $camera Camera */
/* @var $locations [] */
/* @var $categories [] */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$i = 1;
?>


<div class="dashboard-index-wrap">
  <div class="row">
    <div class="col-md-4">

      <div class="panel ">
        <div class="panel-heading">
          <h4 class="panel-title text-center"> камер подключено</h4>

        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4">
              <span><?= (isset($stats['disk_space']['free'])) ? floor($stats['disk_space']['free']) : 0 ?>%</span>
            </div>
            <div class="col-xs-8">
              <div id="morris-donut-chart" class="height-xs"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="col-md-4">

      <div class="panel ">
        <div class="panel-heading">
          <h4 class="panel-title text-center">Траффик с камер</h4>

        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4"><i class="fa fa-dashboard"></i></div>
            <div class="col-xs-8">
              <table class="camera-traffic">
                <tr>
                  <td class="text-right">
                    <?= (isset($stats['traff_by_camera']['day'])) ? $stats['traff_by_camera']['day'] : 0 ?> Гб
                  </td>
                  <td>по тревоге</td>
                </tr>
                <tr>
                  <td class="text-right">
                    <?= (isset($stats['traff_by_camera']['week'])) ? $stats['traff_by_camera']['week'] : 0 ?> Гб
                  </td>
                  <td>по движению</td>
                </tr>
                <tr>
                  <td class="text-right">
                    <?= (isset($stats['traff_by_camera']['month'])) ? $stats['traff_by_camera']['month'] : 0 ?> Гб
                  </td>
                  <td>по расписанию</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="col-md-4">

      <div class="panel ">
        <div class="panel-heading">
          <h4 class="panel-title text-center">Кадров за неделю</h4>

        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4"><i class="fa fa-server"></i></div>
            <div class="col-xs-8">
              <table class="images-per-week">
                <tr>
                  <td class="text-right">
                    <?= (isset($stats['image_per_week']['ALERT'])) ? $stats['image_per_week']['ALERT'] : 0 ?>
                  </td>
                  <td>по тревоге</td>
                </tr>
                <tr>
                  <td class="text-right">
                    <?= (isset($stats['image_per_week']['MOVE'])) ? $stats['image_per_week']['MOVE'] : 0 ?>
                  </td>
                  <td>по движению</td>
                </tr>
                <tr>
                  <td class="text-right">
                    <?= (isset($stats['image_per_week']['SCHEDULE'])) ? $stats['image_per_week']['SCHEDULE'] : 0 ?>
                  </td>
                  <td>по расписанию</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="clearfix"></div>
</div>

<script src="/template/plugins/morris/raphael.min.js"></script>
<script src="/template/plugins/morris/morris.js"></script>
<script>
  var handleMorrisDonusChart = function () {
    Morris.Donut({
      element: 'morris-donut-chart',
      data: [
        {label: 'Свободно', value: <?= (isset($stats['disk_space']['free'])) ? $stats['disk_space']['free'] : 0 ?>},
        {label: 'Занято', value: <?= (isset($stats['disk_space']['load'])) ? $stats['disk_space']['load'] : 0 ?>}
      ],
      formatter: function (y) {
        return y + "%"
      },
      resize: true,
      colors: ['#B5D5D2', '#558C98']
    });
  };


  var MorrisChart = function () {
    "use strict";
    return {
      //main function
      init: function () {
        handleMorrisDonusChart();
      }
    };
  }();
</script>

<script>
  $(document).ready(function () {
    App.init();
    MorrisChart.init();
  });
</script>
