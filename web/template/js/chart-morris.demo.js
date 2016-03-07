/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 1.4.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.4/
*/


var handleMorrisDonusChart = function() {
    Morris.Donut({
        element: 'morris-donut-chart',
        data: [
            {label: 'Jam', value: 25 },
            {label: 'Frosted', value: 40 },
            {label: 'Custard', value: 25 },
            {label: 'Sugar', value: 10 }
        ],
        formatter: function (y) { return y + "%" },
        resize: true,
        colors: [dark, orange, red, grey]
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