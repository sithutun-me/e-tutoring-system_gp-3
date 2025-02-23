
// import Chart from 'chart.js/auto';
window.demo = {
  
    initStudentInteractionChart: function () {
  
      gradientBarChartConfiguration = {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        tooltips: {
          backgroundColor: '#333333',
          titleFontColor: '#333',
          bodyFontColor: '#666',
          bodySpacing: 4,
          xPadding: 12,
          mode: "nearest",
          intersect: 0,
          position: "nearest"
        },
        responsive: true,
        scales: {
          yAxes: [{
            gridLines: {
              color: 'rgba(88, 92, 95, 0.1)',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 20,
              stepSize: 5,
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }],
  
          xAxes: [{
            gridLines: {
              color: 'transparent',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }]
        }
      };
  
  
      var ctx = document.getElementById("StudentInteractionChart").getContext("2d");
  
      // var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);
  
      // gradientStroke.addColorStop(1, 'rgba(29,140,248,0.2)');
      // gradientStroke.addColorStop(0.4, 'rgba(29,140,248,0.0)');
      // gradientStroke.addColorStop(0, 'rgba(29,140,248,0)'); //blue colors
      var myChart = new Chart(ctx, {
        type: 'bar',
        responsive: true,
        legend: {
          display: false
        },
        data: {
          labels: ['7 days', '30 days', '60 days'],
          datasets: [{
            label: "Students",
            fill: true,
            barThickness: 100,
            maxBarThickness: 100,
            backgroundColor: [
              'rgba(0, 74, 173, 1)',  // Color for the first bar
              'rgba(0, 74, 173, 0.8)',  // Color for the second bar
              'rgba(0, 74, 173, 0.5)',  // Color for the third bar
            ],
            hoverBackgroundColor: [
              'rgba(0, 74, 173, 1)',  // Color for the first bar
              'rgba(0, 74, 173, 0.8)',  // Color for the second bar
              'rgba(0, 74, 173, 0.5)',  // Color for the third bar
            ],
            borderColor: [
              'rgba(0, 74, 173, 1)',  // Color for the first bar
              'rgba(0, 74, 173, 0.8)',  // Color for the second bar
              'rgba(0, 74, 173, 0.5)',  // Color for the third bar
            ],
            borderWidth: 1,
            borderDash: [],
            borderDashOffset: 0.0,
            data: [17, 13, 8]
          }]
        },
        options: gradientBarChartConfiguration
      });
    },
    initTutorMessagesChart: function () {
  
      gradientBarChartConfiguration = {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
  
        tooltips: {
          backgroundColor: '#333333',
          titleFontColor: '#333',
          bodyFontColor: '#666',
          bodySpacing: 4,
          xPadding: 12,
          mode: "nearest",
          intersect: 0,
          position: "nearest"
        },
        responsive: true,
        scales: {
          yAxes: [{
  
            gridLines: {
              color: 'rgba(88, 92, 95, 0.1)',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 50,
              stepSize: 10,
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }],
  
          xAxes: [{
            gridLines: {
              color: 'transparent',
              zeroLineColor: "rgba(88, 92, 95, 0.5)",
            },
            ticks: {
              padding: 20,
              fontColor: "#333333",
              fontSize: 13,
              fontFamily: 'Poppins'
            }
          }]
        }
      };
  
  
      var ctx = document.getElementById("TutorMessagesChart").getContext("2d");
  
      var myChart = new Chart(ctx, {
        type: 'bar',
        responsive: true,
        legend: {
          display: false
        },
        data: {
          labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9'],
          datasets: [{
            label: "Messages",
            fill: true,
            backgroundColor: '#004AAD',
            hoverBackgroundColor: '#004AAD',
            borderColor: '#004AAD',
            borderWidth: 2,
            borderDash: [],
            borderDashOffset: 0.0,
            data: [40, 15, 35, 45, 30, 18, 20, 42, 18],
          }]
        },
        options: gradientBarChartConfiguration
      });
    }
  }