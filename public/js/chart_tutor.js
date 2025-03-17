

window.demo = {

  initStudentInteractionsChart: async function () {

    gradientBarChartConfigurationTutor = {

      barPercentage: 0.8,      
      categoryPercentage: 0.6, 
      elements: {
        bar: {
          maxBarThickness: 50 
        }
      },
      plugins: {
        legend: {
          display: false
        }
      },
      maintainAspectRatio: false,

      tooltips: {
        backgroundColor: '#333333',
        titleFontColor: 'white',
        bodyFontColor: 'white',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest",
        fontSize: 13,
        fontFamily: 'Poppins'
      },
      responsive: true,
      scales: {
        y: {
          suggestedMin: 0,
          suggestedMax: 50,
          gridLines: {
            color: 'rgba(88, 92, 95, 0.1)',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            stepSize: 10,
            fontColor: "#333333",
            fontSize: 13,
            fontFamily: 'Poppins'
          }
        },

        x: {
          grid: {
            color: "transparent",
            drawBorder: false
          },
          gridLines: {
            color: 'transparent',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            padding: 20,
            fontColor: "#333333",
            fontSize: 13,
            fontFamily: 'Poppins',
            autoSkip: false,
            callback: function (value, index) {
              const label = this.chart.data.labels[index]
              return label.split(" ")[0];  // Only the first word
            }
          }
        }
      }
    };


    var ctx = document.getElementById("StudentInteractionCountChart").getContext("2d");

    var myChart = new Chart(ctx, {
      type: 'bar',
      responsive: true,
      legend: {
        display: false
      },
      data: {
        labels: ['Kyaw Kyaw','Su Su','Min Min','Tun Tun','std5','std6','Kyaw Kyaw','Su Su','Min Min','Tun Tun','std5','std6'],
        datasets: [{
          label: "Messages",
          fill: true,
          backgroundColor: '#004AAD',
          hoverBackgroundColor: '#004AAD',
          borderColor: '#004AAD',
          borderWidth: 1,
          borderDash: [],
          borderDashOffset: 0.0,
          data: [10,30,20,25,40,15,10,30,20,25,40,15],
        }]
      },
      options: gradientBarChartConfigurationTutor,
    });
  }
}