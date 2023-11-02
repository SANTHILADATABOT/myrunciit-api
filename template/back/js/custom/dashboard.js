	var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData1;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = cost_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: MYR [[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdiv");
	});
	
		var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartRate1;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = "Ratings";
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: [[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartrate");
	});
	
	//chart retention
			var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chart_retention;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = "percentage";
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: [[value]]</b>";
		graph.type = "line";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartretention");
	});
	var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData2;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = value_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: "+currency+"[[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdiv2");
	});
	
////////////sales order by type///////////////
var chart_data_val=chartData3;

    $("#salesType").change(function () {
       
        var stype = $('#salesType').val();
        if(stype=="pickup")
        {
            $("#chartdivdel").css("display","none");
            $("#chartdiv3").css("display","none");
            $("#chartdivpick").css("display","block");
           
        }
        if(stype=="delivery")
        {
            $("#chartdivdel").css("display","block");
            $("#chartdiv3").css("display","none");
            $("#chartdivpick").css("display","none");
           
        }
        if(stype=="both"){
             $("#chartdivdel").css("display","none");
            $("#chartdiv3").css("display","block");
            $("#chartdivpick").css("display","none");
        }
        
    });
	var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chart_data_val;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = pl_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: [[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdiv3");
	});
	
		var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData_pick;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = pl_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: [[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdivpick");
		$("#chartdivpick").css("display","none");
	});
		var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData_delivery;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = pl_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: [[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdivdel");
		$("#chartdivdel").css("display","none");
	});


	
	var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData4;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = pl_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: "+currency+"[[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdiv4");
	});
	
	
	var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData5;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = pl_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: [[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdiv5");
	});
	
		var chart;
	AmCharts.ready(function() {
		// SERIAL CHART
		chart = new AmCharts.AmSerialChart();
		chart.dataProvider = chartData6;
		chart.categoryField = "country";
		chart.startDuration = 1;
	
		// AXES
		// category
		var categoryAxis = chart.categoryAxis;
		categoryAxis.labelRotation = 45; // this line makes category values to be rotated
		categoryAxis.gridAlpha = 0;
		categoryAxis.fillAlpha = 1;
		categoryAxis.fillColor = "#FAFAFA";
		categoryAxis.gridPosition = "start";
	
		// value
		var valueAxis = new AmCharts.ValueAxis();
		valueAxis.dashLength = 5;
		valueAxis.title = pl_txt;
		valueAxis.axisAlpha = 0;
		chart.addValueAxis(valueAxis);
	
		// GRAPH
		var graph = new AmCharts.AmGraph();
		graph.valueField = "visits";
		graph.colorField = "color";
		graph.balloonText = "<b>[[category]]: "+currency+"[[value]]</b>";
		graph.type = "column";
		graph.lineAlpha = 0;
		graph.fillAlphas = 1;
		chart.addGraph(graph);
	
		// CURSOR
		var chartCursor = new AmCharts.ChartCursor();
		chartCursor.cursorAlpha = 0;
		chartCursor.zoomable = false;
		chartCursor.categoryBalloonEnabled = false;
		chart.addChartCursor(chartCursor);
	
		chart.creditsPosition = "top-right";
	
		// WRITE
		chart.write("chartdiv6");
	});
	
	
	
	
	
	
	$(document).ready(function() {
		var opts1 = {
			lines: 10, // The number of lines to draw
			angle: 0, // The length of each line
			lineWidth: 0.3, // The line thickness
			pointer: {
				length: 0.45, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#242d3c' // Fill color
			},
			limitMax: 'true', // If true, the pointer will not go past the end of the gauge
			colorStart: '#9365b8', // Colors
			colorStop: '#9365b8', // just experiment with them
			strokeColor: '#ddd', // to see which ones work best for you
			generateGradient: true
		};
	
		var opts2 = {
			lines: 10, // The number of lines to draw
			angle: 0, // The length of each line
			lineWidth: 0.3, // The line thickness
			pointer: {
				length: 0.45, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#242d3c' // Fill color
			},
			limitMax: 'true', // If true, the pointer will not go past the end of the gauge
			colorStart: '#00a65a', // Colors
			colorStop: '#00a65a', // just experiment with them
			strokeColor: '#ddd', // to see which ones work best for you
			generateGradient: true
		};
	
		var opts3 = {
			lines: 10, // The number of lines to draw
			angle: 0, // The length of each line
			lineWidth: 0.3, // The line thickness
			pointer: {
				length: 0.45, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#242d3c' // Fill color
			},
			limitMax: 'true', // If true, the pointer will not go past the end of the gauge
			colorStart: '#303641', // Colors
			colorStop: '#303641', // just experiment with them
			strokeColor: '#ddd', // to see which ones work best for you
			generateGradient: true
		};
	
	
		var target = document.getElementById('gauge1'); // your canvas element
		var gauge = new Gauge(target).setOptions(opts1); // create sexy gauge!
		gauge.maxValue = stock_max; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(stock); // set actual value
		gauge.setTextField(document.getElementById("gauge1-txt"));
		
		var target = document.getElementById('gauge2'); // your canvas element
		var gauge = new Gauge(target).setOptions(opts2); // create sexy gauge!
		gauge.maxValue = sale_max; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(sale); // set actual value
		gauge.setTextField(document.getElementById("gauge2-txt"));
	
		var target = document.getElementById('gauge3'); // your canvas element
		var gauge = new Gauge(target).setOptions(opts3); // create sexy gauge!
		gauge.maxValue = destroy_max; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(destroy); // set actual value
		gauge.setTextField(document.getElementById("gauge3-txt"));
		
		var target = document.getElementById('gauge4'); // your canvas element
		var gauge = new Gauge(target).setOptions(opts2); // create sexy gauge!
		gauge.maxValue = sale_max; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(sale); // set actual value
		gauge.setTextField(document.getElementById("gauge4-txt"));
		
		var updateGauge;
		var gaugeSwitch = document.getElementById('auto-gauge1');
		gaugeSwitch.checked = false;
		new Switchery(gaugeSwitch);
	
		gaugeSwitch.onchange = function() {
			if (gaugeSwitch.checked) {
				updateGauge = setInterval(function() {
					gauge.set(activeit.randomInt(1, 1500));
				}, 2000)
			} else {
				clearInterval(updateGauge);
			}
		};
	
		var updateGauge;
		var gaugeSwitch = document.getElementById('auto-gauge2');
		gaugeSwitch.checked = false;
		new Switchery(gaugeSwitch);
	
		gaugeSwitch.onchange = function() {
			if (gaugeSwitch.checked) {
				updateGauge = setInterval(function() {
					gauge.set(activeit.randomInt(1, 1500));
				}, 2000)
			} else {
				clearInterval(updateGauge);
			}
		};
	
		var updateGauge;
		var gaugeSwitch = document.getElementById('auto-gauge3');
		gaugeSwitch.checked = false;
		new Switchery(gaugeSwitch);
	
		gaugeSwitch.onchange = function() {
			if (gaugeSwitch.checked) {
				updateGauge = setInterval(function() {
					gauge.set(activeit.randomInt(1, 1500));
				}, 2000)
			} else {
				clearInterval(updateGauge);
			}
		};
		
		var updateGauge;
		var gaugeSwitch = document.getElementById('auto-gauge4');
		gaugeSwitch.checked = false;
		new Switchery(gaugeSwitch);
	
		gaugeSwitch.onchange = function() {
			if (gaugeSwitch.checked) {
				updateGauge = setInterval(function() {
					gauge.set(activeit.randomInt(1, 1500));
				}, 2000)
			} else {
				clearInterval(updateGauge);
			}
		};
	
	});