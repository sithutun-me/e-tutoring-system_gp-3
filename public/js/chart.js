
// import Chart from 'chart.js/auto';

window.demo = {


  initStudentInteractionChart: async function () {


    gradientBarChartConfigurationStudent = {
      plugins: {
        legend: {
          display: false
        },
        datalabels: {
          display: false
        },
      },
      maintainAspectRatio: false,
      legend: {
        display: false
      },
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
            padding: 20,
            color: "#333333",
            font: {
              size: 13,
              family: 'Poppins'
            }
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
            color: "#333333",
            font: {
              size: 13,
              family: 'Poppins'
            }
          }
        }
      }
    };


    var ctx = document.getElementById("StudentInteractionChart").getContext("2d");


    async function getStudentInteractionData() {
      try {
        const response = await fetch('/student-inactivity');
        const data = await response.json(); // Assume the response is a JSON object
        // Returning the student interaction data for 7, 30, 60 days
        return [data.inactive_7_days, data.inactive_30_days, data.inactive_60_days];
      } catch (error) {
        console.error('Error fetching student interaction data:', error);
        return [0, 0, 0]; // Fallback data in case of an error
      }
    }



    const data = await getStudentInteractionData();
    console.log('Fetched data:', data);
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
          barThickness: 50,
          maxBarThickness: 50,
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
          data: data,
        }]
      },
      options: gradientBarChartConfigurationStudent,
    });
    console.log('Chart Initialized:', myChart);


  },


  initTutorMessagesChart: async function () {

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
        },
        datalabels: {
          display: false
        },
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
          suggestedMax: 5,
          gridLines: {
            color: 'rgba(88, 92, 95, 0.1)',
            zeroLineColor: "rgba(88, 92, 95, 0.5)",
          },
          ticks: {
            stepSize: 1,
            color: "#333333",
            font: {
              size: 13,
              family: 'Poppins'
            }
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
            color: "#333333",
            autoSkip: false,
            font: {
              size: 13,
              family: 'Poppins'
            },
            callback: function (value, index) {
              const label = this.chart.data.labels[index]
              return label.split(" ")[0];  // Only the first word
            }
          }
        }
      }
    };


    async function getAverageMessagesPerTutor() {
      try {
        const response = await fetch('/average_messages');
        const data = await response.json(); // API response with "labels" and "data"

        // Extracting labels and message count data
        const labels = data.labels;
        const messageCounts = data.data;
        var now = new Date();

        var day = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
        // Calculate average message count per tutor
        const averageMessageCounts = messageCounts.map(count => {
          // If count is greater than 0, calculate the average
          return count > 0 ? (count / day).toFixed(2) : 0;
        });

        // Returning both labels and average message counts in a single object
        return { labels, averageMessageCounts };
      } catch (error) {
        console.error('Error fetching average messages:', error);

        // Fallback in case of an error
        return {
          labels: [],
          averageMessageCounts: []
        };
      }
    }

    const { labels, averageMessageCounts } = await getAverageMessagesPerTutor();

    var ctx = document.getElementById("TutorMessagesChart").getContext("2d");
    // ctx.width = data.length * 50;

    var myChart = new Chart(ctx, {
      type: 'bar',
      responsive: true,
      legend: {
        display: false
      },
      data: {
        labels: labels,
        datasets: [{
          label: "Count",
          fill: true,
          backgroundColor: '#004AAD',
          hoverBackgroundColor: '#004AAD',
          borderColor: '#004AAD',
          borderWidth: 1,
          borderDash: [],
          borderDashOffset: 0.0,
          data: averageMessageCounts,
        }]
      },
      options: gradientBarChartConfigurationTutor,
    });
  },
  initUsedBrowsersChart: async function () {

    pieChartConfiguration = {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: 45
      },
      plugins: {
        datalabels: {
          display: true,
          color: '#fff',
          font: {
            size: 13,
            family: 'Poppins'
          },
          formatter: (value) => value,
          anchor: 'center',
          align: 'center',
        },
        legend: {
          position: 'right',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            color: '#333333',
            font: {
              size: 13,
              family: 'Poppins'
            },
            // generateLabels: function (chart) {
            //   return labels.map((label, index) => ({
            //     text: label, // Show all labels in legend
            //     fillStyle: labelColors[index % labelColors.length], // Use correct colors for all labels
            //     hidden: false
            //   }));
            // }
          }
        }
      }
    };

    async function getBrowserPieData() {
      try {
        const response = await fetch('/browser-chart');
        const chartData = await response.json(); // This is an array

        const total = chartData.reduce((sum, item) => sum + item.count, 0);

        // Extract browser names and counts into separate arrays
        const labels = chartData.map(item => item.browser);
        const percentages = chartData.map(item => {
          return total > 0 ? Math.round((item.count / total) * 100) : 0;
        });

        console.log('Fetched browser data:', labels);
        return { labels, percentages };

      } catch (error) {
        console.error('Error fetching browser pie data:', error);
        return {
          labels: [],
          percentages: []
        };
      }
    }
    const { labels, percentages } = await getBrowserPieData();
    const labelColors = ["#00B312", "#004AAD", "#D73030"];
    var ctx = document.getElementById("UsedBrowsersChart").getContext("2d");

    var myChart = new Chart(document.getElementById("UsedBrowsersChart"), {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          backgroundColor: labelColors,
          data: percentages
        }]
      },
      options: pieChartConfiguration
    });
  }
}