$(document).ready(function () {
    // Середня доля розробки і доставки
    var splneAreaColors = getChartColorsArray("#line_chart_datalabel");
    var splneAreaColorsOptions = {
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
        },
        series: [{
            name: 'Розробка',
            data: [280, 256, 247, 285, 275, 259, 260, 250, 242, 246, 278]
        }, {
            name: 'Доставка',
            data: [202, 153, 165, 204, 206, 211, 170, 166, 186, 156, 191]
        }],
        colors: splneAreaColors,
        yaxis: {
            title: {
                text: 'Години'
            },
        },
        xaxis: {
            title: {
                text: 'Спринти'
            },
            categories: [
                "MV Release 1.0",
                "MV Release 1.1",
                "MV Release 1.2",
                "MV Release 1.3",
                "MV Release 1.4",
                "MV Release 1.5",
                "MV Release 1.6",
                "MV Release 1.7",
                "MV Release 1.8",
                "MV Release 1.9",
                "MV Release 1.10"
            ],
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    }
    var AverageShareOfDevelopmentAndDeliveryChart = new ApexCharts(
        document.querySelector("#line_chart_datalabel"),
        splneAreaColorsOptions
    );
    AverageShareOfDevelopmentAndDeliveryChart.render();


    // Time-to-Market
    var TimeToMarketColors = getChartColorsArray("#line_chart_dashed");
    var TimeToMarketOptions = {
        chart: {
            height: 350,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            data: [20.8377, 24.8944, 28.6916, 38.4304, 51.7222, 73.1667]
        }],
        colors: TimeToMarketColors,
        grid: {
            borderColor: '#f1f1f1',
        },
        xaxis: {
            categories: [
                '1 Story Point',
                '2 Story Point',
                '3 Story Point',
                '5 Story Point',
                '8 Story Point',
                '13 Story Point',
            ],
            title: {
                text: 'Годин в середньому'
            }
        }
    }
    var TimeToMarketChart = new ApexCharts(
        document.querySelector("#line_chart_dashed"),
        TimeToMarketOptions
    );
    TimeToMarketChart.render();


// Тривалість перебування задач у статусах
    var TasksInStatusesTimeColors = getChartColorsArray("#spline_area");
    var TasksInStatusesTimeOptions = {
        chart: {
            height: 380,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            data: [0.5162,10.8432,0.5233,0.4658,0.4932,0.5439,2.1928,3.0227,3.0487,3.0032,4.9497]
        }],
        colors: TasksInStatusesTimeColors,
        grid: {
            borderColor: '#f1f1f1',
        },
        yaxis: {
            title: {
                text: 'Статуси задач'
            }
        },
        xaxis: {
            categories: [
                'ToDo',
                'InProgress',
                'Autotesting',
                'ForReview',
                'InReview',
                'ForTesting',
                'InTesting',
                'ForBuild',
                'InBuild',
                'BuildTesting',
                'ProdTesting',
            ],
            title: {
                text: 'Годин в середньому'
            }
        }
    }
    var TasksInStatusesTimeChart = new ApexCharts(
        document.querySelector("#spline_area"),
        TasksInStatusesTimeOptions
    );
    TasksInStatusesTimeChart.render();


    // Частота змін опису задач після початку спринта
    var DescriptionChangeColors = getChartColorsArray("#column_chart");
    var DescriptionChangeOptions = {
        chart: {
            height: 380,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: DescriptionChangeColors,
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [3, 3],
            curve: 'smooth'
        },
        series: [{
            name: "Задач зі зміненим описом",
            data: [3, 0, 5, 1, 5, 2, 2, 1, 1, 0, 3]
        }],
        grid: {
            row: {
                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.2
            },
            borderColor: '#f1f1f1'
        },
        markers: {
            style: 'inverted',
            size: 0
        },
        xaxis: {
            categories: ['MV Release 1.0', 'MV Release 1.1', 'MV Release 1.2', 'MV Release 1.3', 'MV Release 1.4', 'MV Release 1.5', 'MV Release 1.6', 'MV Release 1.7', 'MV Release 1.8', 'MV Release 1.9', 'MV Release 1.10'],
            title: {
                text: 'Спринти'
            }
        },
        yaxis: {
            title: {
                text: 'Кількість задач'
            },
            min: 0,
            max: 20
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    toolbar: {
                        show: false
                    }
                },
                legend: {
                    show: false
                },
            }
        }]
    }
    var DescriptionChangeChart = new ApexCharts(
        document.querySelector("#column_chart"),
        DescriptionChangeOptions
    );
    DescriptionChangeChart.render();


// Швидкість команди
    var TeamSpeedColors = getChartColorsArray("#column_chart_datalabel");
    var TeamSpeedOptions = {
        chart: {
            height: 380,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val;
            },
            offsetY: -22,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        series: [{
            name: 'Кількість Story Points',
            data: [64, 64, 65, 64, 65, 65, 63, 64, 62, 62, 63]
        }],
        colors: TeamSpeedColors,
        grid: {
            borderColor: '#f1f1f1',
        },
        xaxis: {
            title: {
                text: 'Спринти'
            },
            categories: ["MV Release 1.0", "MV Release 1.1", "MV Release 1.2", "MV Release 1.3", "MV Release 1.4", "MV Release 1.5", "MV Release 1.6", "MV Release 1.7", "MV Release 1.8", "MV Release 1.9", "MV Release 1.10"],
            position: 'bottom',
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                }
            },
            tooltip: {
                enabled: true,
                offsetY: -35,
            }
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                }
            }

        },
    }
    var TeamSpeedChart = new ApexCharts(
        document.querySelector("#column_chart_datalabel"),
        TeamSpeedOptions
    );
    TeamSpeedChart.render();


// Середня доля багів
    var AverageBugPercentageColors = getChartColorsArray("#bar_chart");
    var AverageBugPercentageoptions = {
        chart: {
            height: 380,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: AverageBugPercentageColors,
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [3, 3],
            curve: 'smooth'
        },
        series: [{
            name: "Задач зі зміненим описом",
            data: [0.0000, 0.0000, 4.7619, 0.0000, 4.0000, 4.1667, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000]
        }
        ],
        grid: {
            row: {
                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.2
            },
            borderColor: '#f1f1f1'
        },
        markers: {
            style: 'inverted',
            size: 0
        },
        xaxis: {
            categories: ['MV Release 1.0', 'MV Release 1.1', 'MV Release 1.2', 'MV Release 1.3', 'MV Release 1.4', 'MV Release 1.5', 'MV Release 1.6', 'MV Release 1.7', 'MV Release 1.8', 'MV Release 1.9', 'MV Release 1.10'],
            title: {
                text: 'Спринти'
            }
        },
        yaxis: {
            title: {
                text: 'Відсоток багів відносно кількості задач'
            },
            min: 0,
            max: 15
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    toolbar: {
                        show: false
                    }
                },
                legend: {
                    show: false
                },
            }
        }]
    }
    var AverageBugPercentagechart = new ApexCharts(
        document.querySelector("#bar_chart"),
        AverageBugPercentageoptions
    );
    AverageBugPercentagechart.render();


// Тривалість життя багів
    var BugLifetimeColors = getChartColorsArray("#mixed_chart");
    var BugLifetimeoptions = {
        chart: {
            height: 380,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: BugLifetimeColors,
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [3, 3],
            curve: 'smooth'
        },
        series: [{
            name: "Час життя багів в годинах",
            data: [19, 21, 21, 24, 21, 17, 26, 19, 22, 21, 23, 22, 20]
        }],
        grid: {
            row: {
                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.2
            },
            borderColor: '#f1f1f1'
        },
        markers: {
            style: 'inverted',
            size: 0
        },
        xaxis: {
            categories: ['MV-54', 'MV-107', 'MV-118', 'MV-265', 'MV-295', 'MV-341', 'MV-427', 'MV-454', 'MV-469', 'MV-478', 'MV-491', 'MV-500', 'MV-582'],
            title: {
                text: 'Баги'
            }
        },
        yaxis: {
            title: {
                text: 'Відсоток багів відносно кількості задач'
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    toolbar: {
                        show: false
                    }
                },
                legend: {
                    show: false
                },
            }
        }]
    }
    var BugLifetimechart = new ApexCharts(
        document.querySelector("#mixed_chart"),
        BugLifetimeoptions
    );
    BugLifetimechart.render();


// Кількість переводів в статус ToDo з різних статусів
    var TodoColors = getChartColorsArray("#radial_chart");
    var Todooptions = {
        chart: {
            height: 380,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val;
            },
            offsetY: -22,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        series: [{
            name: 'Кількість перевідкритих задач',
            data: [0, 0, 0, 0, 2, 0, 0, 1, 0, 2, 2]
        }],
        colors: TodoColors,
        grid: {
            borderColor: '#f1f1f1',
        },
        xaxis: {
            title: {
                text: 'Спринти'
            },
            categories: ["MV Release 1.0", "MV Release 1.1", "MV Release 1.2", "MV Release 1.3", "MV Release 1.4", "MV Release 1.5", "MV Release 1.6", "MV Release 1.7", "MV Release 1.8", "MV Release 1.9", "MV Release 1.10"],
            position: 'bottom',
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            tooltip: {
                enabled: true,
                offsetY: -35,
            }
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                }
            }

        },
    }
    var Todochart = new ApexCharts(
        document.querySelector("#radial_chart"),
        Todooptions
    );
    Todochart.render();

    $(document).on('click', '#submitForm',function(e) {
        let dateFrom = $('#dateFrom').val();
        let dateTo = $('#dateTo').val();
        $.ajax({
            url: '/api/metrics',
            data: {
                dateFrom: dateFrom,
                dateTo: dateTo
            },
            success: function(response) {
                AverageShareOfDevelopmentAndDeliveryChart.updateOptions({
                    series: response.data.AverageShareOfDevelopmentAndDeliveryChart.data,
                    xaxis: {
                        title: {text: 'Спринти'},
                        categories: response.data.AverageShareOfDevelopmentAndDeliveryChart.sprints
                    }
                });
                TimeToMarketChart.updateOptions({
                    series: response.data.TimeToMarketChart
                });
                TasksInStatusesTimeChart.updateOptions({
                    series: response.data.TasksInStatusesTimeChart
                });
                DescriptionChangeChart.updateOptions({
                    series: response.data.DescriptionChangeChart.data,
                    xaxis: {
                        title: {text: 'Спринти'},
                        categories: response.data.DescriptionChangeChart.sprints
                    }
                });
                TeamSpeedChart.updateOptions({
                    series: response.data.TeamSpeedChart.data,
                    xaxis: {
                        title: {text: 'Спринти'},
                        categories: response.data.TeamSpeedChart.sprints
                    }
                });
                AverageBugPercentagechart.updateOptions({
                    series: response.data.AverageBugPercentagechart.data,
                    xaxis: {
                        title: {text: 'Спринти'},
                        categories: response.data.AverageBugPercentagechart.sprints
                    }
                });
                BugLifetimechart.updateOptions({
                    series: response.data.BugLifetimechart.data,
                    xaxis: {
                        title: {text: 'Задачі-баги'},
                        categories: response.data.BugLifetimechart.sprints
                    }
                });
                Todochart.updateOptions({
                    series: response.data.Todochart.data,
                    xaxis: {
                        title: {text: 'Спринти'},
                        categories: response.data.Todochart.sprints
                    }
                });
            }
        });
        return false;
    });
});

function getChartColorsArray(chartId) {
    var colors = $(chartId).attr('data-colors');
    var colors = JSON.parse(colors);
    return colors.map(function(value){
        var newValue = value.replace(' ', '');
        if(newValue.indexOf('--') != -1) {
            var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
            if(color) return color;
        } else {
            return newValue;
        }
    })
}
